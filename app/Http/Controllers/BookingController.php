<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Armada;
use App\Models\Cso\Tujuan;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Hrd\Pengemudi;
use App\Models\Booking_detail;
use App\Models\Hrd\Kondektur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $booking = Booking::with('details')->orderBy('created_at', 'desc')->get();

        return view('layouts.bookings.index', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $start = $request->input('start', now()->format('Y-m-d'));
        $end = $request->input('end', now()->format('Y-m-d'));

        $tanggal_mulai = date('Y-m-d', strtotime($request->input('start')));
        $tanggal_akhir = date('Y-m-d', strtotime($request->input('end')));

        $bus = collect();

        $buses = Armada::whereDoesntHave('booking_details.bookings', function ($query) use ($tanggal_mulai, $tanggal_akhir) {
            $query->whereDate('date_start', '<=', $tanggal_akhir)
                ->whereDate('date_end', '>=', $tanggal_mulai)
                ->where('booking_status', 1);
        })
            ->orderBy('id', 'asc');

        if ($request['type']) {
            $buses = $buses->where('keterangan', $request['type']);
        }

        $bus = $buses->get();

        $allBusesFull = $bus->isEmpty();

        return view('layouts.booking.create', [
            'bus' => $bus,
            'allBusesFull' => $allBusesFull,
            'request' => [
                'start' => $start,
                'end' => $end,
            ],
        ]);
    }

    public function report()
    {
        $booking = Booking::with('details')->whereHas('details', function ($details) {
            $details->whereNotNull('is_in');
        })
            ->orderBy('created_at', 'DESC')->get();

        return view('layouts.booking.report', [
            'booking' => $booking,
        ]);
    }

    public function laporan(Request $request)
    {
        $customer = $request->input('customer');
        $no_booking = $request->input('no_booking');
        $tanggal = $request->input('tanggal');
        $date_start = $request->input('date_start', now()->startOfMonth()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->endOfMonth()->format('Y-m-d'));

        $bookings = Booking::with('details')->whereHas('details', function ($details) {
            $details->whereNotNull('is_in');
        })
            ->orderBy('created_at', 'DESC');

        if ($request['date_start']) {
            $bookings->whereDate('date_start', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $bookings->whereDate('date_end', '<=', $request['date_end']);
        }

        if ($request['customer']) {
            $bookings->where('customer', 'like', '%' . $request['customer'] . '%');
        };

        if ($request['no_booking']) {
            $bookings->where('no_booking', $request['no_booking']);
        };

        if ($request['tanggal']) {
            $bookings->where('date_start', $request['tanggal']);
        };

        $booking = $bookings->get();

        return view('layouts.booking.laporan', [
            'booking' => $booking,
            'request' => [
                'customer' => $customer,
                'tanggal' => $tanggal,
                'no_booking' => $no_booking,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }

    public function showDetail($id)
    {
        // Fetch the booking with its details
        $booking = Booking::with('details')->findOrFail($id);
        // Return a view and pass the booking data
        return view('layouts.booking.detail', compact('booking'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'customer' => 'nullable',
                'telephone' => 'nullable',
                'date_start' => 'nullable',
                'date_end' => 'nullable',
                'telephone' => 'nullable',
                'total_bus' => 'nullable',
                'harga_std' => 'required',
                'grand_total' => 'required',
                'keterangan' => 'nullable',
                'biaya_jemput' => 'nullable',
                'lokasi_jemput' => 'required',
                'diskon' => 'nullable',
                'tujuan_id' => 'nullable',
            ]);

            $booking = new Booking($validatedData);

            $count = Booking::whereMonth("created_at", date("m"))
                ->whereYear("created_at", date("Y"))
                ->count();

            $next = $count + 1;
            $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

            $booking->no_booking = "PP/WST/" . date("Y") . "/" . $array_bln[date('n')] . "/" . $next;

            $booking->save();

            $diskon = $request->diskon / $request->total_bus;

            foreach ($request->input('bus_id') as $value) {
                $detail = new Booking_detail();
                $detail->booking_id = $booking->id;
                $detail->armada_id = $value;
                $detail->harga_std = $request->harga_std;
                $detail->diskon = null;
                $detail->total_harga = null;
                $detail->save();
            }

            DB::commit();
            // return redirect('booking/detail/' . $booking->id);
            // return redirect()->back();
            return redirect('/bookings')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);

            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function pengemudi($id)
    {
        $booking = Booking::find($id);
        $pengemudi = Pengemudi::whereHas('users', function($users){
            $users->orderBy('name','asc');
        })->get();
        $kondektur = Kondektur::whereHas('users', function($users){
            $users->orderBy('name','asc');
        })->get();

        $buses = Armada::whereDoesntHave('booking_details.bookings', function ($query) use ($booking) {
            $query->whereDate('date_start', '<=', $booking->date_end)
                ->whereDate('date_end', '>=', $booking->date_start)
                ->where('booking_status', 1);
        })
            ->orderBy('id', 'asc')->get();

        return view('layouts.booking.edit', [
            'booking' => $booking,
            'pengemudi' => $pengemudi,
            'kondektur' => $kondektur,
            'buses' => $buses,

        ]);
    }

    public function kondektur($id)
    {
        $booking = Booking::find($id);
        $kondektur = Kondektur::orderBy('created_at', 'desc')->get();

        return view('layouts.booking.edit', [
            'booking' => $booking,
            'kondektur' => $kondektur,

        ]);
    }

    public function detail($id)
    {
        $booking = Booking::find($id);
        $pengemudi = Pengemudi::orderBy('created_at', 'desc')->get();
        $kondektur = Kondektur::orderBy('created_at', 'desc')->get();

        return view('layouts.booking.detail', [
            'booking' => $booking,
            'pengemudi' => $pengemudi,
            'kondektur' => $kondektur,

        ]);
    }

    // BookingController.php
    public function store_detail(Request $request)
    {
        try {
            DB::beginTransaction();

            $bookingId = $request->input('id'); // Retrieve bookingId from request data
            $detail = Booking_detail::where('id', $bookingId)->first(); // Find Booking_detail by bookingId

            $booking = Booking::where('id', $detail->booking_id)->first();

            if (!$detail) {
                return response()->json(['error' => 'Booking detail not found'], 404);
            }

            if (!$booking) {
                return response()->json(['error' => 'Booking not found'], 404);
            }

            $detail->jemput = $request->input('jemput');
            $detail->biaya_jemput = $request->input('biaya_jemput');
            $detail->save();

            $total_biaya_jemput = $detail->where('booking_id', $detail->booking_id)->sum('biaya_jemput');

            $booking->biaya_jemput = $total_biaya_jemput;
            $booking->grand_total = $detail->biaya_jemput + $booking->grand_total;
            $booking->save();

            DB::commit();

            return response()->json(['success' => 'Data updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update_pengemudi(Request $request)
    {
        try {
            DB::beginTransaction();

            $bookingId = $request->input('booking_id');
            $detail = Booking_detail::where('id', $bookingId)->first();

            if (!$detail) {
                return response()->json(['error' => 'Booking detail not found'], 404);
            }

            $detail->supir_id = $request->input('supir_id');
            $detail->Kondektur_id = $request->input('kondektur_id');
            $detail->armada_id = $request->input('armada_id');
            // \Log::info($detail->armada_id);
            // return 123;

            $detail->save();

            DB::commit();

            return response()->json(['success' => 'Data updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }


    public function jadwal()
    {

        $jadwal = Booking_detail::whereHas('bookings', function ($bookings) {
            $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
            $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

            $bookings->whereDate('date_start', '>=', $startOfMonth)
                ->whereDate('date_end', '<=', $endOfMonth);
        })
            ->whereNotNull('pengemudi_id')->get();

        // $jadwal = Booking::with('details')->whereDate('date_start', '>=', Carbon::now())
        // ->whereDate('date_end', '<=', Carbon::now())->get();

        return view('layouts.jadwal.index', [
            'jadwal' => $jadwal
        ]);
    }

    // public function getTujuan(Request $request)
    // {
    //     $tujuan = [];
    //     $tujuan = Tujuan::where('nama_tujuan', 'LIKE', "%$request->name%")->get();

    //     return response()->json($tujuan);
    // }

    public function getTujuan(Request $request)
    {
        $tujuan = Tujuan::with('typearmadas')->where('nama_tujuan', 'LIKE', "%$request->name%")->get();
        return response()->json($tujuan);
    }

    public function getTotalHargaStd(Request $request)
    {
        $tujuanId = $request->tujuan_id;
        $totalHargaStd = 0;

        if ($tujuanId) {
            $tujuan = Tujuan::findOrFail($tujuanId);
            $totalHargaStd = $tujuan->harga_std;
        }

        return response()->json(['total_harga_std' => $totalHargaStd]);
    }

    public function edit(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $pengemudi = Pengemudi::orderBy('created_at', 'desc')->get();

        $start = $booking->date_start;
        $end = $booking->date_end;

        $tanggal_mulai = date('Y-m-d', strtotime($request->input('start')));
        $tanggal_akhir = date('Y-m-d', strtotime($request->input('end')));

        $selectedBuses = $booking->details->pluck('armada_id')->toArray();

        // Ambil bus yang sudah dipilih dalam booking atau tidak memiliki booking_status 1
        $buses = Armada::where(function ($query) use ($tanggal_mulai, $tanggal_akhir, $selectedBuses) {
            $query->whereDoesntHave('booking_details.bookings', function ($query) use ($tanggal_mulai, $tanggal_akhir) {
                $query->whereDate('date_start', '<=', $tanggal_akhir)
                    ->whereDate('date_end', '>=', $tanggal_mulai)
                    ->where('booking_status', 1);
            })
                ->orWhereIn('id', $selectedBuses);
        })
            ->orderBy('id', 'asc');

        if ($request['type']) {
            $buses = $buses->where('keterangan', $request['type']);
        }

        $bus = $buses->get();

        foreach ($bus as $item) {
            $item->selected = in_array($item->id, $selectedBuses);
        }

        $allBusesFull = $bus->isEmpty();

        return view('layouts.booking.editReservation', [
            'booking' => $booking,
            'pengemudi' => $pengemudi,
            'bus' => $bus,
            'allBusesFull' => $allBusesFull,
            'request' => [
                'start' => $start,
                'end' => $end,
            ],
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // Simpan perubahan pada Booking utama
            $booking = Booking::find($id);
            $booking->customer = $request->input('customer');
            $booking->telephone = $request->input('telephone');
            $booking->date_start = $request->input('date_start');
            $booking->date_end = $request->input('date_end');
            $booking->total_bus = $request->input('total_bus');
            $booking->grand_total = $request->input('grand_total');
            $booking->keterangan = $request->input('keterangan');
            $booking->biaya_jemput = $request->input('biaya_jemput');
            $booking->diskon = $request->input('diskon');
            $booking->tujuan_id = $request->input('tujuan_id');
            $booking->harga_std = $request->input('harga_std');
            $booking->save();

            if($booking->grand_total > $booking->total_payment){
                $booking->payment_status = '2';
                $booking->save();
                \Log::info('booking payment : '.$booking->payment_status);
            }

            // Simpan atau perbarui Booking_detail untuk setiap bus_id yang dipilih
            foreach ($request->input('bus_id') as $value) {
                // Cari Booking_detail berdasarkan booking_id dan armada_id
                $detail = Booking_detail::where('booking_id', $booking->id)
                    ->where('armada_id', $value)
                    ->first();

                // Jika Booking_detail tidak ditemukan, buat baru
                if (!$detail) {
                    $detail = new Booking_detail();
                    $detail->booking_id = $booking->id;
                    $detail->armada_id = $value;
                }

                // Update atau setel nilai-nilai yang diperlukan
                $detail->harga_std = $request->input('harga_std');
                $detail->diskon = null; // Atur sesuai kebutuhan
                $detail->total_harga = null; // Atur sesuai kebutuhan

                // Simpan Booking_detail
                $detail->save();
            }

            DB::commit();

            \Log::info('Booking updated: ' . $booking);

            return response()->json(["success" => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Booking update error: ' . $e->getMessage());

            return response()->json(["error" => $e->getMessage()], 422);
        }
    }


    public function updateBusReservation(Request $request)
    {
        try {
            DB::beginTransaction();

            $bookingId = $request->input('booking_id');
            $detail = Booking_detail::where('id', $bookingId)->first();


            if (!$detail) {
                return response()->json(['error' => 'Booking detail not found'], 404);
            }

            $detail->armada_id = $request->input('bus_id');
            $detail->save();

            DB::commit();

            return response()->json(['success' => 'Data updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function updateDateReservation(Request $request)
    {
        try {
            DB::beginTransaction();

            $bookingId = $request->input('booking_id');
            $booking = Booking::where('id', $bookingId)->first();

            if (!$booking) {
                return response()->json(['error' => 'Booking not found'], 404);
            }

            $booking->date_start = $request->input('start');
            $booking->date_end = $request->input('end');
            $booking->save();


            DB::commit();

            return response()->json(['success' => 'Data updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
