<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Armada;
use App\Models\TypeArmada;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{
    private function getScheduleData(Request $request)
    {
        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $type_id = $request->input('type_id');
        $armada_id = $request->input('armada_id');
        $search = $request->input('search');
        $perPage = $request->query('perpage', 150);

        $armadas = Armada::with('type_armada')
            ->when($type_id, function ($query) use ($type_id) {
                return $query->where('type_id', $type_id);
            })
            ->select('id', 'nobody', 'type_id')
            ->get();

        $typearmadas = TypeArmada::all();

        $query = DB::table('armadas')
            ->select(
                'armadas.id AS armada_id',
                'armadas.nobody AS nobody',
                'type_armadas.name AS type_name',
                'bookings.date_start AS date_start',
                'bookings.date_end AS date_end',
                'tujuans.nama_tujuan AS nama_tujuan'
            )
            ->leftJoin('booking_details', 'booking_details.armada_id', '=', 'armadas.id')
            ->leftJoin('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->leftJoin('tujuans', 'bookings.tujuan_id', '=', 'tujuans.id')
            ->join('type_armadas', 'armadas.type_id', '=', 'type_armadas.id')
            ->whereBetween('bookings.date_start', [$date_start, $date_end])
            ->orWhereBetween('bookings.date_end', [$date_start, $date_end]);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('armadas.nobody', 'like', '%' . $search . '%')
                         ->orWhere('tujuans.nama_tujuan', 'like', '%' . $search . '%')
                         ->orWhere('type_armadas.name', 'like', '%' . $search . '%');
            });
        }

        if ($type_id) {
            $query->where('armadas.type_id', $type_id);
        }

        if ($armada_id) {
            $query->where('armadas.id', $armada_id);
        }

        $dates = [];
        $start_date = Carbon::parse($date_start);
        $end_date = Carbon::parse($date_end);
        for ($date = $start_date; $date <= $end_date; $date->addDay()) {
            $dates[$date->format('Y-m-d')] = $date->format('d');
        }

        $schedules = $query->paginate($perPage);

        $schedule = [];
        $totalArmadas = [];
        foreach ($armadas as $armada) {
            foreach ($dates as $date => $day) {
                $schedule[$armada->id][$date] = 'Tersedia';
                if (!isset($totalArmadas[$date])) {
                    $totalArmadas[$date] = 0;
                }
            }
        }

        foreach ($schedules as $booking) {
            $current_date = Carbon::parse($booking->date_start);
            $end_date = Carbon::parse($booking->date_end);
            while ($current_date <= $end_date) {
                $schedule[$booking->armada_id][$current_date->format('Y-m-d')] = $booking->nama_tujuan;
                if (!isset($totalArmadas[$current_date->format('Y-m-d')])) {
                    $totalArmadas[$current_date->format('Y-m-d')] = 0;
                }
                $totalArmadas[$current_date->format('Y-m-d')]++;
                $current_date->addDay();
            }
        }

        return compact('armadas', 'dates', 'schedule', 'date_start', 'date_end', 'search', 'schedules', 'perPage', 'typearmadas', 'totalArmadas');
    }


    public function index(Request $request)
    {
        $data = $this->getScheduleData($request);
        return view('layouts.Operasi.schedule.index', $data);
    }

    public function showBusSchedule(Request $request)
    {
        $data = $this->getScheduleData($request);

        if ($request->has('generate_pdf')) {
            $pdf = Pdf::loadView('layouts.Operasi.jadwalbus.pdf', $data);
            $pdf->setPaper('F4', 'landscape');
            return $pdf->download('jadwal_bus.pdf');
        }

        return view('layouts.Operasi.jadwalbus.index', $data);
    }

    public function jadwalbusToPdf(Request $request)
    {
        $data = $this->getScheduleData($request);

        $pdf = Pdf::loadView('layouts.Operasi.jadwalbus.pdf', $data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->download('jadwal_bus.pdf');
    }

    public function exportToExcel(Request $request)
    {
        return Excel::download('jadwal_bus.xlsx');
    }
}
