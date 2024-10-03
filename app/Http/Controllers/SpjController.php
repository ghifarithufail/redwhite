<?php

namespace App\Http\Controllers;

use App\Models\Spj;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Booking_detail;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpjController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer = $request->input('customer');
        $no_booking = $request->input('no_booking');

        $bookings = Booking::whereHas('details', function ($details) {
            $details->where('is_in', null);
        })
            ->where('payment_status', 1)
            ->orderBy('created_at', 'desc');

        if ($request['customer']) {
            $bookings->where('customer', 'like', '%' . $request['customer'] . '%');
        };

        if ($request['no_booking']) {
            $bookings->where('no_booking', $request['no_booking']);
        };

        $booking = $bookings->get();


        return view('layouts.spj.index', [
            'booking' => $booking,
            'request' => [
                'customer' => $customer,
                'no_booking' => $no_booking,
            ],
        ]);
    }

    public function detail($id)
    {
        $detail = Booking_detail::with(['armadas','bookings'])->where('booking_id', $id)->orderBy('created_at', 'desc')->get();

            $pengemudi = Booking_detail::where('booking_id', $id)
            ->where('supir_id', null)
            ->count();

            $kondektur = Booking_detail::where('booking_id', $id)
            ->Where('kondektur_id',null)
            ->count();

        return view('layouts.spj.detail', [
            'detail' => $detail,
            'pengemudi' => $pengemudi,
            'kondektur' => $kondektur,
        ]);
    }

    public function data($id)
    {
        $spj = Spj::where('id', $id)->first();

        return view('layouts.spj.data', [
            'spj' => $spj
        ]);
    }

    public function biaya_lain(Request $request)
    {
        $spj = Spj::where('id', $request->spj_id)->first();
        $spj->biaya_lain = $request->biaya_lain;
        $spj->keterangan_spj = $request->keterangan_spj;
        $spj->save();

        return redirect('/spj/detail/' . $spj->booking_details->bookings->id);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function keluar(Request $request, $id)
    {
        try {
            $detail = Booking_detail::where('id', $id)->first();


            $count = Spj::whereMonth("created_at", date("m"))
                ->whereYear("created_at", date("Y"))
                ->count();

            $next = $count + 1;
            $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

            $spj = new Spj();
            $spj->booking_detail_id = $detail->id;
            $spj->no_spj = "PP/WST/OPS/" . date("Y") . "/" . $array_bln[date('n')] . "/" . $next;
            $spj->type = '1';
            $spj->save();

            $detail->is_out = 1;
            $detail->save();

            DB::commit();
            return redirect('spj/print/out/' . $spj->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);

            return redirect()->back()->with('error', 'Gagal Membuat SPJ Keluar ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function detail_out($id)
    {
        $spj = Spj::find($id);
        $pengemudi = Pengemudi::orderBy('nopengemudi', 'desc')->get();
        $kondektur = Kondektur::orderBy('nokondektur', 'desc')->get();

        return view('layouts.spj.create_out', [
            'spj' => $spj,
            'pengemudi' => $pengemudi,
            'kondektur' => $kondektur
        ]);
    }
    
    public function save_detail_out(Request $request, $id)
    {
        $spj = Spj::find($id);
        $spj->user_keluar = Auth::user()->id;
        $spj->date_keluar = Carbon::now();
        $spj->save();

        return redirect('/spj/print_out/' .$spj->id);
    }

    public function print($id)
    {
        $spj = Spj::with('pengemudis.user')->find($id);

        if (!$spj) {
            return redirect()->back()->with('error', 'SPJ not found');
        }

        return view('layouts.spj.out', [
            'spj' => $spj
        ]);
    }

    public function detail_in($id)
    {
        $spj = Spj::find($id);


        return view('layouts.spj.create_in', [
            'spj' => $spj
        ]);
    }

    public function back($id)
    {
        $detail = Booking_detail::find($id);

        return redirect('spj/detail/' . $detail->id);
    }

    /**
     * Display the specified resource.
     */
    public function store_print_out(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'jam_jemput' => 'required',
                'km_keluar' => 'required',
                'uang_jalan' => 'required',

            ]);

            $spj = Spj::where('id', $request->spj_id)->first();
            $spj->update($validatedData);

            DB::commit();
            // return redirect()->route('payment')->with('success', 'Payment berhasil disimpan');
            return redirect('spj/print/out/' . $spj->id)->with('success', 'SPJ berhasil Dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);

            return redirect()->route('payment')->with('error', 'Gagal menyimpan Pembayaran ' . $e->getMessage());
            // return redirect('spj/print/out/' . $spj->id)->with('error', 'Gagal membuat SPJ ' . $e->getMessage());
        }
    }

    public function store_print_in(Request $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validate([
                'bbm' => 'required',
                'uang_makan' => 'required',
                'parkir' => 'required',
                'tol' => 'required',
                'km_masuk' => 'required',

            ]);
            $pengeluaran = $request->bbm + $request->uang_makan + $request->parkir + $request->tol;

            $spj = Spj::where('id', $request->spj_id)->first();
            $spj->sisa_uang_jalan = $spj->uang_jalan - $pengeluaran - $spj->biaya_lain;
            $spj->pengeluaran = $pengeluaran + $spj->biaya_lain;
            $spj->type = 2;
            $spj->update($validatedData);

            $sisa_uang_jalan = $spj->where('booking_detail_id', $spj->booking_detail_id)->sum('sisa_uang_jalan');
            $detail = Booking_detail::where('id', $spj->booking_detail_id)->first();

            $detail->is_in = 1;
            $detail->total_sisa_uang_jalan = $sisa_uang_jalan;
            $detail->total_pengeluaran = $pengeluaran + $spj->biaya_lain;;
            $detail->save();


            $booking = Booking::where('id', $detail->booking_id)->first();
            $pengeluaran = Booking_detail::where('booking_id', $detail->booking_id)->sum('total_pengeluaran');

            $booking->total_pendapatan = $booking->grand_total - $pengeluaran;
            $booking->total_pengeluaran = $pengeluaran;
            $booking->save();


            DB::commit();
            return redirect('spj/print/in/' . $spj->id)->with('success', 'SPJ berhasil Dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e);
            return redirect('spj/print/in/' . $spj->id)->with('error', 'Gagal menyimpan Pembayaran ' . $e->getMessage());
        }
    }
    
    public function save_detail_in($id)
    {
        $spj = Spj::find($id);
        $spj->user_masuk = Auth::user()->id;
        $spj->date_masuk = Carbon::now();
        $spj->save();

        $booking = Booking::where('id', $spj->booking_details->booking_id)->first();
        $booking->booking_status = 0;
        $booking->save();

        return redirect('/spj/print_out/' .$spj->id);
    }

    public function masuk(Request $request, $id)
    {
        try {
            $detail = Booking_detail::where('id', $id)->first();

            $count = Spj::whereMonth("created_at", date("m"))
                ->whereYear("created_at", date("Y"))
                ->where('type', '2')
                ->count();

            $next = $count + 1;
            $array_bln = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");

            $spj = new Spj();
            $spj->booking_detail_id = $detail->id;
            $spj->no_spj = "SPJ/BKI/PP/" . date("Y") . "/" . $array_bln[date('n')] . "/" . $next;
            $spj->type = '2';
            $spj->save();

            $detail->is_in = 1;
            $detail->save();

            DB::commit();
            return redirect('spj/print/in/' . $spj->id);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::info($e);

            return redirect()->back()->with('error', 'Gagal Membuat SPJ Masuk ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {

        $no_spj = $request->input('no_spj');
        $no_booking = $request->input('no_booking');
        $tanggal = $request->input('tanggal');
        $date_start = $request->input('date_start', now()->startOfMonth()->format('Y-m-d'));
        $date_end = $request->input('date_end', now()->endOfMonth()->format('Y-m-d'));

        $booking = Booking::orderBy('created_at', 'desc')->get();
        $spjs = Spj::with('booking_details')
            ->where('type', 2)
            ->orderBy('created_at', 'desc');

        if ($request['date_start']) {
            $spjs->whereDate('created_at', '>=', $request['date_start']);
        }

        if ($request['date_end']) {
            $spjs->whereDate('created_at', '<=', $request['date_end']);
        }

        if ($request['no_spj']) {
            $spjs->where('no_spj', $request['no_spj']);
        };

        if ($request['no_booking']) {
            $spjs->whereHas('booking_details.bookings', function ($bookings) use ($request) {
                $bookings->where('no_booking', $request['no_booking']);
            });
        };

        $spj = $spjs->get();

        return view('layouts.spj.report', [
            'booking' => $booking,
            'spj' => $spj,
            'request' => [
                'no_spj' => $no_spj,
                'tanggal' => $tanggal,
                'no_booking' => $no_booking,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ],
        ]);
    }
}
