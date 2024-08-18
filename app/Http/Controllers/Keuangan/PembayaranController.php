<?php

namespace App\Http\Controllers\Keuangan;

use Carbon\Carbon;
use App\Models\Cso\Booking;
use App\Models\TypePayment;
use Illuminate\Http\Request;
use App\Models\Keuangan\Pembayaran;
use App\Http\Controllers\Controller;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve inputs from the request
        $search = $request->input('search');
        $date_start = $request->input('date_start', Carbon::now()->format('Y-m-d'));
        $date_end = $request->input('date_end', Carbon::now()->format('Y-m-d'));
        $type_id = $request->input('type_id');
        $bookingId = $request->input('booking_id');
        $perPage = $request->input('per_page', 10);

        // Convert date_start and date_end to start and end of day
        if ($date_start && $date_end) {
            $start = Carbon::parse($date_start)->startOfDay();
            $end = Carbon::parse($date_end)->endOfDay();
        } else {
            $start = null;
            $end = null;
        }

        // Start querying Pembayaran with eager loaded bookings relationship
        $pembayaransQuery = Pembayaran::with('booking');

        // Apply date range filter
        if ($start && $end) {
            $pembayaransQuery->whereBetween('created_at', [$start, $end]);
        }

        // Apply search filter
        if ($search) {
            $pembayaransQuery->where(function ($query) use ($search) {
                $query->where('type_payment_id', 'like', "%$search%")
                      ->orWhereHas('booking', function ($query) use ($search) {
                          $query->where('no_booking', 'like', "%$search%");
                      });
            });
        }

        // Apply payment type filter
        if ($type_id) {
            $pembayaransQuery->where('type_payment_id', $type_id);
        }

        // Apply booking ID filter
        if ($bookingId) {
            $pembayaransQuery->where('booking_id', $bookingId);
        }

        // Paginate the results
        $pembayarans = $pembayaransQuery->paginate($perPage);

        // Pass data to the view
        return view('layouts.keuangan.pembayaran.index', compact('pembayarans', 'date_start', 'date_end', 'search', 'type_id', 'bookingId', 'perPage'));

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $booking = Booking::all();
        $typepayment = TypePayment::all();
        $pembayarans = Pembayaran::with('booking', 'typepayment')->get(); // Eager load relationships

        return view('layouts.keuangan.pembayaran.create', compact('booking', 'typepayment', 'pembayarans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'type_id' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        // Simpan data pembayaran
        Pembayaran::create($request->all());

        return redirect()->route('pembayaran')->with('success', 'Pembayaran berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Fetch the booking by ID
    $bookings = Booking::with('pembayarans')->find($id);

    // Check if the booking exists
    if (!$bookings) {
        return redirect()->back()->with('error', 'Booking not found');
    }

    // Fetch the payment types (assuming you need them for the form)
    $type = TypePayment::all();

    // Pass the data to the view
    return view('layouts.keuangan.pembayaran.index', compact('bookings', 'type'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
