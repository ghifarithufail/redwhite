<?php

namespace App\Http\Controllers;

use App\Exports\SpjExport;
use App\Models\Spj;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Booking_detail;
use App\Models\Cso\BookingDetail;
use App\Models\Hrd\Kondektur;
use App\Models\Hrd\Pengemudi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Excel;

class SpjController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customer = $request->input('customer');
        $no_booking = $request->input('no_booking');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

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

        if ($request['start_date']) {
            $bookings->whereDate('date_start', '>=', $request['start_date']);
        }

        if ($request['end_date']) {
            $bookings->whereDate('date_start', '<=', $request['end_date']);
        }


        // $booking = $bookings->get();
        $booking = $bookings->paginate(10)->appends($request->all());


        return view('layouts.spj.index', [
            'booking' => $booking,
            'request' => [
                'customer' => $customer,
                'no_booking' => $no_booking,
                'start_date' => $start_date,
                'end_date' => $end_date,
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

        
        $bus = $spj->booking_details->armada_id;
        $armada = Booking_detail::where('armada_id', $bus)
        ->orderBy('created_at', 'desc')
        ->skip(1) // Melewati data pertama
        ->first(); // Mengambil data kedua
    

         $km_akhir = $armada && $armada->spjs ? $armada->spjs->km_masuk : null;

        return view('layouts.spj.create_out', [
            'spj' => $spj,
            'km_akhir' => $km_akhir,
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

        $spj_count_null = Booking_detail::where('booking_id', $spj->booking_details->booking_id)
                        ->where('is_in', null)->count();

        // $spj_count_null = $booking_detail->
        \Log::info($spj_count_null);

        if($spj_count_null == 0){
            $booking = Booking::where('id', $spj->booking_details->booking_id)->first();
            $booking->booking_status = 0;
            $booking->save();
        }

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

        $customer = $request->input('customer');
        $no_booking = $request->input('no_booking');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $spjs = DB::table('bookings as b')
            ->join('booking_details as bd', 'b.id', '=', 'bd.booking_id')
            ->join('spjs as s', 'bd.id', '=', 's.booking_detail_id')
            ->select(
                'b.no_booking',
                'b.customer',
                'b.date_start',
                'b.date_end',
                DB::raw('SUM(s.uang_jalan) AS total_uang_berangkat'),
                DB::raw('
            SUM(
                COALESCE(s.bbm, 0) + 
                COALESCE(s.uang_makan, 0) + 
                COALESCE(s.parkir, 0) + 
                COALESCE(s.tol, 0) + 
                COALESCE(s.biaya_lain, 0)
            ) AS bop
        '),
                DB::raw('
            SUM(
                s.uang_jalan - (
                    COALESCE(s.bbm, 0) + 
                    COALESCE(s.uang_makan, 0) + 
                    COALESCE(s.parkir, 0) + 
                    COALESCE(s.tol, 0) + 
                    COALESCE(s.biaya_lain, 0)
                )
            ) AS sisa_biaya_keluar
        ')
            )
            ->groupBy('b.no_booking');


        if ($request['customer']) {
            $spjs->where('b.customer','like', '%'.$request['customer'].'%');
        };

        if ($request['no_booking']) {
            $spjs->where('b.no_booking', $request['no_booking']);
        };

        if ($request['start_date']) {
            $spjs->whereDate('date_start', '>=', $request['start_date']);
        }

        if ($request['end_date']) {
            $spjs->whereDate('date_end', '<=', $request['end_date']);
        }

        $spj = $spjs->paginate(10);

        return view('layouts.spj.report', [
            'spj' => $spj,
            'request' => [
                'customer' => $customer,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'no_booking' => $no_booking
            ],
        ]);
    } 	

    public function excel(Request $request){
        return Excel::download(new SpjExport($request), 'Spj_report.xlsx');

    }
}
