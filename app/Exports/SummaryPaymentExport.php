<?php

namespace App\Exports;

use App\Models\Keuangan\TypePayment;
use App\Models\Partai;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SummaryPaymentExport implements FromView, ShouldAutoSize
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $request = $this->request;

        $date_start = $request->input('date_start', now()->format('Y-m-01'));
        $date_end = $request->input('date_end', now()->format('Y-m-d'));
        $type_payment = TypePayment::orderBy('name', 'asc')->get();

        $tipe_pembayaran = $request->input('tipe_pembayaran');

        $payment = Payment::select([
            'payments.created_at',
            'b.customer',
            'no_payment',
            'b.date_start',
            'b.date_end',
            DB::raw('DATEDIFF(b.date_end, b.date_start) + 1 AS total_days'),
            'b.total_bus',
            't.nama_tujuan',
            'jmlh_bayar as pembayaran_ke',
            'price',
            'tp.name as tipe_pembayaran'
        ])
            ->leftJoin('bookings as b', 'payments.booking_id', '=', 'b.id')
            ->leftJoin('tujuans as t', 'b.tujuan_id', '=', 't.id')
            ->leftJoin('type_payments as tp', 'tp.id', '=', 'payments.type_payment_id')
            ->where('b.payment_status', 1)
            ->whereDate("payments.created_at", ">=", $date_start)
            ->whereDate("payments.created_at", "<=", $date_end)
            ->orderBy('payments.created_at');

        if ($request['tipe_pembayaran']) {
            $payment = $payment->where('type_payment_id', $request['tipe_pembayaran']);
        }


        $payments = $payment->get()->groupBy(function ($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
        });

        $totalPrices = [];

        foreach ($payments as $date => $group) {
            $totalPrices[$date] = $group->sum('price');
        }

        return view('layouts.payment.summary_report_excel', [
            'payments' => $payments,
            'totalPrices' => $totalPrices,
            'request' => [
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }
}
