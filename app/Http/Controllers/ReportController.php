<?php

namespace App\Http\Controllers;

use App\Exports\DriverExport;
use App\Models\Booking_detail;
use App\Models\Hrd\Pengemudi;
use App\Models\Spj;
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

        $driver = Booking_detail::whereHas('spjs', function($q) use ($start_date, $end_date) {
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

        return view('layouts.report.driver',
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

    public function driver_excel(Request $request){
        return Excel::download(new DriverExport($request), 'Driver_report.xlsx');
    }

    public function report_bus_keluar(Request $request){
        $start_date = $request->input('start_date', now()->format('Y-m-01'));
        $end_date = $request->input('end_date', now()->format('Y-m-d'));

        $bus = Booking_detail::whereHas('spjs', function($q) use ($start_date, $end_date) {
            $q->whereNull('date_masuk')
            ->whereNotNull('date_keluar')
            ->whereDate('date_keluar', '>=', $start_date)
            ->whereDate('date_keluar', '<=', $end_date);
        });

        $bus = $bus->paginate(20)->appends($request->all());

        return view('layouts.report.bus_keluar',
            [
                'bus' => $bus,
                'request' => [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ],
            ]
        );
    }
}
