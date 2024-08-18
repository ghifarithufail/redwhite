<?php

namespace App\Http\Controllers\Cso;

use Carbon\Carbon;
use App\Models\TypeArmada;
use Illuminate\Http\Request;
use App\Models\Hrd\Pengemudi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JadwalPengemudiController extends Controller
{
    private function getJadwalPengemudi(Request $request)
    {
        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $pengemudi_id = $request->input('pengemudi_id');
        $search = $request->input('search');
        $perPage = $request->query('perpage', 10);

        $pengemudis = Pengemudi::all();
        $typearmadas = TypeArmada::all();

        $query = DB::table('pengemudis')
            ->select(
                'pengemudis.id AS pengemudi_id',
                'pengemudis.nopengemudi AS nopengemudi',
                'users.name AS name',
                'users.status AS status',
                'bookings.date_start AS date_start',
                'bookings.date_end AS date_end',
                'tujuans.nama_tujuan AS nama_tujuan',
                'armadas.nobody AS nobody'
            )
            ->join('users', 'pengemudis.user_id', '=', 'users.id')
            ->leftJoin('booking_details', 'pengemudis.id', '=', 'booking_details.supir_id')
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

        if ($pengemudi_id) {
            $query->where('pengemudis.id', $pengemudi_id);
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
        foreach ($pengemudis as $pengemudi) {
            foreach ($dates as $date => $day) {
                $schedule[$pengemudi->id][$date] = 'Standbye';
                if (!isset($totalArmadas[$date])) {
                    $totalArmadas[$date] = 0;
                }
            }
        }

        foreach ($schedules as $booking) {
            $current_date = Carbon::parse($booking->date_start);
            $end_date = Carbon::parse($booking->date_end);
            while ($current_date <= $end_date) {
                $schedule[$booking->pengemudi_id][$current_date->format('Y-m-d')] = $booking->nama_tujuan .
                '<br>' . $booking->nobody;
                $totalArmadas[$current_date->format('Y-m-d')]++;
                $current_date->addDay();
            }
        }

        return compact('pengemudis', 'dates', 'schedule', 'date_start', 'date_end', 'search', 'schedules', 'perPage', 'totalArmadas');
    }

    public function index(Request $request)
    {
        $data = $this->getJadwalPengemudi($request);
        return view('layouts.Operasi.jadwalPengemudi.index', $data);
    }

    public function showJadwalPengemudi(Request $request)
    {
        $data = $this->getJadwalPengemudi($request);

        if ($request->has('generate_pdf')) {
            $pdf = Pdf::loadView('layouts.Operasi.jadwalpengemudi.pdf', $data);
            $pdf->setPaper('Legal', 'landscape');
            return $pdf->download('jadwal_pengemudi.pdf');
        }

        return view('layouts.Operasi.jadwalPengemudi.index', $data);
    }

}
