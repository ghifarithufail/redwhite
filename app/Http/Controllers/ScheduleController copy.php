<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Armada;
use App\Models\TypeArmada;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input filter tanggal
        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $type_id = $request->input('type_id');

         // Ambil semua armada sesuai dengan filter
        $armadas = Armada::with('type_armada')
        ->when($type_id, function ($query) use ($type_id) {
            return $query->where('type_id', $type_id);
        })
        ->select('id', 'nobody', 'type_id')
        ->get();
        // $armadas = Armada::with('type_armada')->select('id', 'nobody', 'type_id')->get();
        $totalArmadas = $armadas->count();
        $typearmadas = TypeArmada::orderBy('created_at', 'desc')->get();

        // Dapatkan tanggal awal dan akhir bulan ini
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Query untuk menghitung total booking dalam bulan ini
        $totalBookingsThisMonth = DB::table('bookings')
            ->whereBetween('date_start', [$startOfMonth, $endOfMonth])
            ->count();

        // Menghitung total armada yang sudah dibooking dalam bulan ini
        $bookedArmadasThisMonth = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.booking_id')
            ->whereBetween('bookings.date_start', [$startOfMonth, $endOfMonth])
            ->distinct('booking_details.armada_id')
            ->count('booking_details.armada_id');

        // Menghitung armada yang belum dibooking dalam bulan ini
        $unbookedArmadasThisMonth = $totalArmadas - $bookedArmadasThisMonth;

        // Hitung persentase armada yang sudah dibooking dan yang belum dibooking
        $bookedPercentage = ($totalArmadas > 0) ? ($bookedArmadasThisMonth / $totalArmadas) * 100 : 0;
        $unbookedPercentage = 100 - $bookedPercentage;

        // Query untuk menghitung jumlah booking per hari dan total pendapatan
        $bookings = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.booking_id')
            ->join('armadas', 'booking_details.armada_id', '=', 'armadas.id')
            ->when($type_id, function ($query) use ($type_id) {
                return $query->where('armadas.type_id', $type_id);
            })
            ->whereBetween('bookings.date_start', [$date_start, $date_end])
            ->select(
                DB::raw('DATE(bookings.date_start) as date'),
                DB::raw('COUNT(DISTINCT booking_details.armada_id) as total_armada')
                // DB::raw('SUM(bookings.price) as total_earnings') // Ganti total_price dengan price atau kolom yang sesuai
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format data untuk grafik
        $chartLabels = $bookings->pluck('date');
        $chartData = $bookings->pluck('total_armada');
        $earnings = $bookings->pluck('total_earnings');

        return view('layouts.Operasi.schedule.index', compact(
            'armadas', 'typearmadas', 'chartLabels', 'chartData',
            'earnings', 'date_start', 'date_end', 'totalArmadas',
            'totalBookingsThisMonth', 'bookedArmadasThisMonth', 'unbookedArmadasThisMonth',
            'bookedPercentage', 'unbookedPercentage' // Sisipkan persentase armada yang sudah dibooking dan belum dibooking
        ));
    }

    public function showBusSchedule(Request $request)
    {
        // Ambil input filter tanggal
        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $search = $request->input('search');
        $types = $request->input('type_id');
        $type_id = $request->input('type_id');
        $perPage = $request->query('perpage', 10);

        // Ambil semua armada
        $armadas = Armada::with('type_armada')
            ->when($type_id, function ($query) use ($type_id) {
                return $query->where('type_id', $type_id);
            })
            ->select('id', 'nobody', 'type_id')
            ->get();
        $typearmadas = TypeArmada::all();

        // Query jadwal booking dengan filter tanggal dan pencarian
        $query = DB::table('armadas')
            ->select('armadas.id AS armada_id', 'armadas.nobody AS nobody', 'type_armadas.name AS name',
                'bookings.date_start AS date_start', 'bookings.date_end AS date_end', 'tujuans.nama_tujuan AS nama_tujuan')
            ->leftJoin('booking_details', 'booking_details.armada_id', '=', 'armadas.id')
            ->leftJoin('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->join('type_armadas', 'armadas.type_id', '=', 'type_armadas.id')
            ->leftJoin('tujuans', 'bookings.tujuan_id', '=', 'tujuans.id')
            ->whereBetween('bookings.date_start', [$date_start, $date_end]);

        // Tambahkan kondisi pencarian jika ada input search
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('armadas.nobody', 'like', '%' . $search . '%')
                        ->orWhere('tujuans.nama_tujuan', 'like', '%' . $search . '%')
                        ->orWhere('type_armadas.name', 'like', '%' . $search . '%');
            });
        }

        $query->whereBetween('bookings.date_start', [$date_start, $date_end]);
        if ($types) {
            $query->where('armadas.type_id', $types);
        }

        // Tentukan tanggal dalam rentang tanggal yang dipilih
        $dates = [];
        $start_date = Carbon::parse($date_start);
        $end_date = Carbon::parse($date_end);
        for ($date = $start_date; $date <= $end_date; $date->addDay()) {
            $dates[$date->format('Y-m-d')] = $date->format('d');
        }

        $schedules = $query->paginate($perPage);

        // Siapkan jadwal
        $schedule = [];
        foreach ($armadas as $armada) {
            foreach ($dates as $date => $day) {
                $schedule[$armada->id][$date] = 'Kosong';
            }
        }

        // Isi jadwal berdasarkan booking
        foreach ($schedules as $booking) {
            $current_date = Carbon::parse($booking->date_start);
            $end_date = Carbon::parse($booking->date_end);
            while ($current_date <= $end_date) {
                $schedule[$booking->armada_id][$current_date->format('Y-m-d')] = $booking->nama_tujuan;
                $current_date->addDay();
            }
        }

        $data = compact('armadas', 'dates', 'schedule', 'date_start', 'date_end', 'search', 'schedules', 'perPage', 'typearmadas');

        // Tambahkan logika untuk generate PDF
        if ($request->has('generate_pdf')) {
            $pdf = Pdf::loadView('layouts.Operasi.jadwalbus.pdf', $data);
            $pdf->setPaper('F4', 'landscape');
            return $pdf->download('jadwal_bus.pdf');
        }

        return view('layouts.Operasi.jadwalbus.index', $data);
    }

    public function jadwalbusToPdf(Request $request)
    {
        // Ambil data yang sama dengan yang digunakan dalam showBusSchedule
        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());
        $search = $request->input('search');
        $types = $request->input('type_id');
        $type_id = $request->input('type_id');
        $perPage = $request->query('perpage', 10);

        $armadas = Armada::with('type_armada')
            ->when($type_id, function ($query) use ($type_id) {
                return $query->where('type_id', $type_id);
            })
            ->select('id', 'nobody', 'type_id')
            ->get();
        $typearmadas = TypeArmada::all();

        $query = DB::table('armadas')
            ->select('armadas.id AS armada_id', 'armadas.nobody AS nobody', 'type_armadas.name AS name',
                'bookings.date_start AS date_start', 'bookings.date_end AS date_end', 'tujuans.nama_tujuan AS nama_tujuan')
            ->leftJoin('booking_details', 'booking_details.armada_id', '=', 'armadas.id')
            ->leftJoin('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->join('type_armadas', 'armadas.type_id', '=', 'type_armadas.id')
            ->leftJoin('tujuans', 'bookings.tujuan_id', '=', 'tujuans.id')
            ->whereBetween('bookings.date_start', [$date_start, $date_end]);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('armadas.nobody', 'like', '%' . $search . '%')
                         ->orWhere('tujuans.nama_tujuan', 'like', '%' . $search . '%')
                         ->orWhere('type_armadas.name', 'like', '%' . $search . '%');
            });
        }

        $query->whereBetween('bookings.date_start', [$date_start, $date_end]);
        if ($types) {
            $query->where('armadas.type_id', $types);
        }

        $dates = [];
        $start_date = Carbon::parse($date_start);
        $end_date = Carbon::parse($date_end);
        for ($date = $start_date; $date <= $end_date; $date->addDay()) {
            $dates[$date->format('Y-m-d')] = $date->format('d');
        }

        $schedules = $query->paginate($perPage);

        $schedule = [];
        foreach ($armadas as $armada) {
            foreach ($dates as $date => $day) {
                $schedule[$armada->id][$date] = 'Kosong';
            }
        }

        foreach ($schedules as $booking) {
            $current_date = Carbon::parse($booking->date_start);
            $end_date = Carbon::parse($booking->date_end);
            while ($current_date <= $end_date) {
                $schedule[$booking->armada_id][$current_date->format('Y-m-d')] = $booking->nama_tujuan;
                $current_date->addDay();
            }
        }

        $data = compact('armadas', 'dates', 'schedule', 'date_start', 'date_end', 'search', 'schedules', 'perPage', 'typearmadas');

        $date_start = $request->input('date_start', now()->startOfMonth()->toDateString());
        $date_end = $request->input('date_end', now()->endOfMonth()->toDateString());

        $pdf = PDF::loadView('pdf.schedule', compact('date_start', 'date_end'));
        return $pdf->download('jadwalbus.pdf');
        $pdf = PDF::loadView('layouts.Operasi.jadwalbus.pdf', $data);
        $pdf->setPaper('legal', 'landscape');
        return $pdf->download('jadwal_bus.pdf');
    }
}
