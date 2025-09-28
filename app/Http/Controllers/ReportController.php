<?php

namespace App\Http\Controllers;

use App\Exports\DriverExport;
use App\Exports\ReportPendapatanExport;
use App\Exports\SpjMasukExport;
use App\Models\Booking;
use App\Models\Booking_detail;
use App\Models\Hrd\Pengemudi;
use App\Models\Spj;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function report_driver(Request $request)
    {

        $start_date = $request->input('start_date', now()->format('Y-m-01'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $pengemudi = $request->input('pengemudi');
        $kondektur = $request->input('kondektur');

        $driver = Booking_detail::whereHas('spjs', function ($q) use ($start_date, $end_date) {
            $q->whereNotNull('date_masuk')
                ->whereDate('date_masuk', '>=', $start_date)
                ->whereDate('date_masuk', '<=', $end_date);
        });

        if ($request['pengemudi']) {
            $driver->whereHas('pengemudis.users', function ($users) use ($request) {
                $users->where('name', 'like', '%' . $request['pengemudi'] . '%');
            });
        }

        if ($request['kondektur']) {
            $driver->whereHas('kondekturs.users', function ($users) use ($request) {
                $users->where('name', 'like', '%' . $request['kondektur'] . '%');
            });
        }

        $driver = $driver->paginate(20)->appends($request->all());

        return view(
            'layouts.report.driver',
            [
                'driver' => $driver,
                'request' => [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'pengemudi' => $pengemudi,
                    'kondektur' => $kondektur,
                ],
            ]
        );
    }

    public function driver_excel(Request $request)
    {
        return Excel::download(new DriverExport($request), 'Driver_report.xlsx');
    }

    public function report_bus_keluar(Request $request)
    {
        $start_date = $request->input('start_date', now()->format('Y-m-01'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));

        $bus = Booking_detail::whereHas('spjs', function ($q) use ($start_date, $end_date) {
            $q->whereNull('date_masuk')
                ->whereNotNull('date_keluar')
                ->whereDate('date_keluar', '>=', $start_date)
                ->whereDate('date_keluar', '<=', $end_date);
        });

        $bus = $bus->paginate(20)->appends($request->all());

        return view(
            'layouts.report.bus_keluar',
            [
                'bus' => $bus,
                'request' => [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ],
            ]
        );
    }

    public function report_spj_masuk(Request $request)
    {
        $start_date = $request->input('start_date', now()->format('Y-m-01'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $no_spj = $request->input('no_spj');
        $no_booking = $request->input('no_booking');

        $spj = Spj::where('date_masuk', '!=', null)->orderBy('date_masuk', 'desc')
            ->whereDate('date_keluar', '>=', $start_date)
            ->whereDate('date_keluar', '<=', $end_date);

        if ($request['no_spj']) {
            $spj->where('no_spj', $request['no_spj']);
        }

        if ($request['no_booking']) {
            $spj->whereHas('booking_details.bookings', function ($bookings) use ($request) {
                $bookings->where('no_booking', $request['no_booking']);
            });
        }

        $spj = $spj->paginate(20)->appends($request->all());

        return view(
            'layouts.report.spj_masuk',
            [
                'spj' => $spj,
                'request' => [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'no_spj' => $no_spj,
                    'no_booking' => $no_booking,
                ],
            ]
        );
    }

    public function report_pendapatan(Request $request)
    {
        $start_date = $request->input('start_date', now()->format('Y-m-01'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));
        $no_booking = $request->input('no_booking');


        $bookings = Booking::with('bookingDetails.spjs')
            ->orderBy('created_at', 'desc')
            ->whereDate('date_end', '>=', $start_date)
            ->whereDate('date_end', '<=', $end_date);

        if ($no_booking) {
            $bookings->where('no_booking', $no_booking);
        }

        $bookings = $bookings->paginate(20)->appends($request->all());

        // mapping per halaman
        $data = $bookings->getCollection()->map(function ($booking) {
            $dateStart = Carbon::parse($booking->date_start);
            $dateEnd   = Carbon::parse($booking->date_end);

            $uangMakan  = $booking->bookingDetails->sum(fn($detail) => optional($detail->spjs)->uang_makan ?? 0);
            $uangMakan2 = $booking->bookingDetails->sum(fn($detail) => optional($detail->spjs)->uang_makan_2 ?? 0);
            $jmlhHari  = $dateStart->diffInDays($dateEnd) + 1;

            $bbm = $booking->bookingDetails->sum(fn($detail) => optional($detail->spjs)->bbm ?? 0);
            $parkir = $booking->bookingDetails->sum(fn($detail) => optional($detail->spjs)->parkir ?? 0);
            $tol = $booking->bookingDetails->sum(fn($detail) => optional($detail->spjs)->tol ?? 0);
            $total_uang_makan = $uangMakan + $uangMakan2;

            $pendapatan = $booking->harga_std + $booking->biaya_jemput - $booking->diskon
                - ($bbm + $parkir + $tol + $total_uang_makan);

            return [
                'no_booking'       => $booking->no_booking,
                'jmlh_spj'         => $booking->total_bus,
                'jmlhHari'         => $jmlhHari,
                'harga_std'        => $booking->harga_std,
                'biaya_jemput'     => $booking->biaya_jemput,
                'diskon'           => $booking->diskon,
                'total_bbm'        => $bbm,
                'total_uang_makan' => $total_uang_makan,
                'parkir'           => $parkir,
                'tol'              => $tol,
                'pendapatan'       => $pendapatan,
            ];
        });

        // ganti collection asli dengan hasil map
        $bookings->setCollection($data);

        return view('layouts.report.total_pendapatan', [
            'data' => $bookings,
            'request' => [
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'no_booking' => $no_booking,
            ],
        ]);
    }

    public function report_pendapatan_excel(Request $request)
    {
        return Excel::download(new ReportPendapatanExport($request), 'report_pendapatan.xlsx');
    }

    public function spj_masuk_excel(Request $request)
    {
        return Excel::download(new SpjMasukExport($request), 'spj_masuk.xlsx');
    }
}
