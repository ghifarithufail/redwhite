<?php

namespace App\Exports;

use App\Models\Keuangan\TypePayment;
use App\Models\Partai;
use App\Models\Payment;
use App\Models\Spj;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SpjExport implements FromView, ShouldAutoSize
{

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $request = $this->request;

        $customer = $request->input('customer');
        $no_booking = $request->input('no_booking');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $spjs = Spj::with('booking_details')
            ->orderBy('created_at', 'desc');

        if ($request['no_booking']) {
            $spjs->whereHas('booking_details.bookings', function ($bookings) use ($request) {
                $bookings->where('no_booking', $request['no_booking']);
            });
        };

        if ($request['customer']) {
            $spjs->whereHas('booking_details.bookings', function ($bookings) use ($request) {
                $bookings->where('customer','like',  '%'.$request['customer'].'%');
            });
        };

        if ($request['start_date']) {
            $spjs->whereHas('booking_details.bookings', function ($bookings) use ($request) {
                $bookings->whereDate('date_start', '>=', $request['start_date']);
            });
        };

        if ($request['end_date']) {
            $spjs->whereHas('booking_details.bookings', function ($bookings) use ($request) {
                $bookings->whereDate('date_end', '<=', $request['end_date']);
            });
        };


        $spj = $spjs->get();

        return view('layouts.spj.excel', [
            'spj' => $spj,
            'request' => [
                'customer' => $customer,
                'no_booking' => $no_booking,
                'start_date' => $start_date,
                'end_date' => $end_date
            ],
        ]);
    }
}
