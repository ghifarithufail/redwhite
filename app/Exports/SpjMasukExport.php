<?php

namespace App\Exports;

use App\Models\Booking_detail;
use App\Models\Keuangan\TypePayment;
use App\Models\Partai;
use App\Models\Payment;
use App\Models\Spj;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SpjMasukExport implements FromView, ShouldAutoSize
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
        $no_spj = $request->input('no_spj');
        $no_booking = $request->input('no_booking');

        $spj = Spj::where('date_masuk', '!=', null)->orderBy('date_masuk','desc')
                ->whereDate('date_keluar', '>=', $start_date)
                ->whereDate('date_keluar', '<=', $end_date);
        
        if ($request['no_spj']) {
            $spj->where('no_spj', $request['no_spj']);
        }

        if ($request['no_booking']) {
            $spj->whereHas('booking_details.bookings', function($bookings) use($request){
                $bookings->where('no_booking', $request['no_booking']);
            });
        }

        $spj = $spj->get();

        return view('layouts.report.spj_masuk_excel',
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
}
