<?php

namespace App\Http\Controllers\Cso;

use Carbon\Carbon;
use App\Models\TypeArmada;
use Illuminate\Http\Request;
use App\Models\Hrd\Kondektur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JadwalKondekturController extends Controller
{
    private function getJadwalkondektur(Request $request)
    {
        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $kondektur_id = $request->input('kondektur_id');
        $search = $request->input('search');
        $perPage = $request->query('perpage', 10);

        $kondekturs = Kondektur::all();
        $typearmadas = TypeArmada::all();

        $query = DB::table('kondekturs')
            ->select(
                'kondekturs.id AS kondektur_id',
                'kondekturs.nokondektur AS nokondektur',
                'users.name AS name',
                'users.status AS status',
                'bookings.date_start AS date_start',
                'bookings.date_end AS date_end',
                'tujuans.nama_tujuan AS nama_tujuan',
                'armadas.nobody AS nobody'
            )
            ->join('users', 'kondekturs.user_id', '=', 'users.id')
            ->leftJoin('booking_details', 'kondekturs.id', '=', 'booking_details.kondektur_id')
            ->leftJoin('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->leftJoin('tujuans', 'bookings.tujuan_id', '=', 'tujuans.id')
            ->leftJoin('armadas', 'booking_details.armada_id', '=', 'armadas.id')
            ->whereBetween('bookings.date_start', [$date_start, $date_end]);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('users.name', 'like', '%' . $search . '%')
                        ->orWhere('tujuans.nama_tujuan', 'like', '%' . $search . '%')
                        ->orWhere('armadas.nobody', 'like', '%' . $search . '%');
            });
        }

        if ($kondektur_id) {
            $query->where('kondekturs.id', $kondektur_id);
        }

        $dates = [];
        $start_date = Carbon::parse($date_start);
        $end_date = Carbon::parse($date_end);
        for ($date = $start_date; $date <= $end_date; $date->addDay()) {
            $dates[$date->format('Y-m-d')] = $date->format('d');
        }

        $schedules = $query->paginate($perPage);

        $schedule = [];
        foreach ($kondekturs as $kondektur) {
            foreach ($dates as $date => $day) {
                $schedule[$kondektur->id][$date] = 'Standbye';
            }
        }

        foreach ($schedules as $booking) {
            $current_date = Carbon::parse($booking->date_start);
            $end_date = Carbon::parse($booking->date_end);
            while ($current_date <= $end_date) {
                $schedule[$booking->kondektur_id][$current_date->format('Y-m-d')] = $booking->nama_tujuan .
                '<br>' . $booking->nobody;
                $current_date->addDay();
            }
        }

        return compact('kondekturs', 'dates', 'schedule', 'date_start', 'date_end', 'search', 'schedules', 'perPage');
    }

    public function index(Request $request)
    {
        $data = $this->getJadwalkondektur($request);
        return view('layouts.Operasi.jadwalkondektur.index', $data);
    }

    public function showJadwalKondektur(Request $request)
    {
        $data = $this->getJadwalKondektur($request);

        if ($request->has('generate_pdf')) {
            $pdf = Pdf::loadView('layouts.Operasi.jadwalkondektur.pdf', $data);
            $pdf->setPaper('Legal', 'landscape');
            return $pdf->download('jadwal_kondektur.pdf');
        }

        return view('layouts.Operasi.jadwalkondektur.index', $data);
    }

}
