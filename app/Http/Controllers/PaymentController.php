<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TypePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // $date_start = $request->input('date_start', now()->format('Y-m-d'));
    // $date_end = $request->input('date_end', now()->format('Y-m-d'));
    $date_start = $request->input('date_start');
    $date_end = $request->input('date_end');
    $customer = $request->input('customer');
    $no_booking = $request->input('no_booking');

    $bookings = Booking::with('payments')->where('payment_status', '2')
    
        ->orderBy('created_at', 'desc');

    if ($request['date_start']) {
        $bookings->where('created_at', '>=', $request['date_start']);
    }

    if ($request['date_end']) {
        $bookings->where('created_at', '<=', $request['date_end']);
    }

    if ($request['customer']) {
        $bookings->where('customer', 'like', '%' . $request['customer'] . '%');
    }
    if ($request['no_booking']) {
        $bookings->where('no_booking', $request['no_booking']);
    }

    $booking = $bookings->paginate(10);

    return view('layouts.payment.index', [
        'booking' => $booking,
        'request' => [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'customer' => $customer,
            'no_booking' => $no_booking,
        ],
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $booking = Booking::find($id);
        $type = TypePayment::orderby('name', 'asc')->get();

        $payment_count = Payment::where('booking_id', $booking->id)->count();

        return view('layouts.payment.create', [
            'booking' => $booking,
            'type' => $type,
            'payment_count' => $payment_count,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validasi data request
            $validatedData = $request->validate([
                'booking_id' => 'required',
                'type_payment_id' => 'required',
                'jmlh_bayar' => 'required',
                'price' => 'required',
                'image' => 'nullable|image', // Menambahkan validasi bahwa image bisa nullable
            ]);

            // Buat instance Payment dengan data validasi
            $payment = new Payment($validatedData);

            // Hitung nomor payment selanjutnya
            $count = Payment::whereMonth("created_at", date("m"))
                ->whereYear("created_at", date("Y"))
                ->count();

            $next = $count + 1;
            $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

            // Set nomor payment
            $payment->no_payment = "BK/PP/INV/" . date("Y") . "/" . $array_bln[date('n')] . "/" . $next;
            $payment->user_id = Auth::user()->id;

            // Cari booking terkait
            $booking = Booking::where('id', $payment->booking_id)->first();

            // Hitung total pembayaran saat ini untuk booking terkait
            $totalPayment = Payment::where('booking_id', $payment->booking_id)->sum('price');

            // Periksa jika total pembayaran saat ini + pembayaran baru melebihi grand_total dari booking
            if (($totalPayment + $payment->price) > $booking->grand_total) {
                return redirect()->back()->with('error', 'Total pembayaran melebihi Harga booking.');
            }

            // Jika ada file image dalam request, simpan file tersebut
            if ($request->hasFile('image')) {
                $payment->image = $request->file('image')->store('payments');
            }

            // Simpan payment
            $payment->save();

            $totalPayment = Payment::where('booking_id', $payment->booking_id)->sum('price');
            // Hitung total pembayaran untuk booking terkait

            // Update total payment dalam booking
            $booking->total_payment = $totalPayment;
            $booking->save();

            // Jika total payment sama dengan grand total booking, update status payment
            if ($booking->grand_total == $booking->total_payment) {
                $booking->payment_status = 1;
                $booking->save();
            }

            DB::commit();
            return redirect('payment/invoice/' . $payment->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);
            return redirect()->back()->with('error', 'Gagal menyimpan Pembayaran ' . $e->getMessage());
        }
    }

    public function invoice($id)
    {
        $payments = Payment::find($id);

        return view('layouts.payment.invoice', [
            'payments' => $payments,
        ]);
    }

    public function report(Request $request)
{
    $date_start = $request->input('date_start', now()->format('Y-m-d'));
    $date_end = $request->input('date_end', now()->format('Y-m-d'));

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
        'price'
    ])
        ->leftJoin('bookings as b', 'payments.booking_id', '=', 'b.id')
        ->leftJoin('tujuans as t', 'b.tujuan_id', '=', 't.id')
        ->where('b.payment_status', 1)
        ->whereDate("payments.created_at", ">=", $date_start)
        ->whereDate("payments.created_at", "<=", $date_end)
        ->orderBy('payments.created_at');

    $payments = $payment->get()->groupBy(function ($date) {
        return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
    });

    $totalPrices = [];

    foreach ($payments as $date => $group) {
        $totalPrices[$date] = $group->sum('price');
    }

    return view('layouts.payment.report', [
        'payments' => $payments,
        'totalPrices' => $totalPrices,
        'request' => [
            'date_start' => $date_start,
            'date_end' => $date_end,
        ],
    ]);
}

}
