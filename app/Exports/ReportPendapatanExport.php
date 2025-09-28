<?php

namespace App\Exports;

use App\Models\Booking;
use App\Models\Booking_detail;
use App\Models\Keuangan\TypePayment;
use App\Models\Partai;
use App\Models\Payment;
use App\Models\Spj;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportPendapatanExport implements FromView, ShouldAutoSize
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $request = $this->request;

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

        $bookings = $bookings->get();

        // mapping per halaman
        $data = $bookings->map(function ($booking) {
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
                'tanggal'          => $dateEnd,

            ];
        });


        return view('layouts.report.excel_total_pendapatan', [
            'data' => $data,
            'request' => [
                'start_date' => $start_date,
                'end_date'   => $end_date,
                'no_booking' => $no_booking,
            ],
        ]);
    }
}
