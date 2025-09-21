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

class DriverExport implements FromView, ShouldAutoSize
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

        $driver = $driver->get();

        return view('layouts.report.excel_driver',
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
}
