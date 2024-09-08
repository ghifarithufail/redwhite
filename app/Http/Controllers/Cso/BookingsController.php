<?php

namespace App\Http\Controllers\Cso;

use Carbon\Carbon;
use App\Models\Cso\Tujuan;
use App\Models\TypeArmada;
use App\Models\Cso\Booking;
use App\Models\Admin\Armada;
use Illuminate\Http\Request;
use App\Models\Cso\BookingDetail;
use Illuminate\Support\Facades\DB;
use App\Exports\Cso\BookingsExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;


class BookingsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $date_start = $request->input('date_start', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $date_end = $request->input('date_end', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $type_id = $request->input('type_id');
        $perPage = $request->input('per_page', 20);

        // Query untuk Booking
        $query = Booking::with(['bookingDetails', 'armada', 'pengemudi', 'kondektur', 'spjs', 'tujuan','users'])
            ->select([
                'bookings.*',
                DB::raw('count(booking_details.id) as total_buses'),
                DB::raw('CONCAT(bookings.date_start, " s/d ", bookings.date_end) as date_range'),
                DB::raw('DATEDIFF(bookings.date_end, bookings.date_start) as duration_days')
            ])
            ->leftJoin('booking_details', 'bookings.id', '=', 'booking_details.booking_id')
            ->groupBy('bookings.id')
            ->orderBy('created_at','desc');

        // Query untuk Armada yang belum terbooking
        $unavailableBusIds = BookingDetail::join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->whereBetween('bookings.date_start', [$date_start, $date_end])
            ->orWhereBetween('bookings.date_end', [$date_start, $date_end])
            ->pluck('booking_details.armada_id');

        // Query Armada yang tersedia
        $availableBuses = Armada::whereNotIn('id', $unavailableBusIds)
            ->when($type_id, function ($query) use ($type_id) {
                return $query->where('type_id', $type_id);
            })
            ->get();

        // Melakukan pencarian berdasarkan kriteria pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bookings.id', 'like', "%$search%")
                    ->orWhere('no_booking', 'like', "%$search%")
                    ->orWhere('customer', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%")
                    ->orWhereHas('tujuan', function ($q) use ($search) {
                        $q->where('nama_tujuan', 'like', "%$search%");
                    })
                    ->orWhereHas('bookingDetails.armada', function ($q) use ($search) {
                        $q->where('nobody', 'like', "%$search%");
                    });
            });
        }

        $bookings = $query->paginate($perPage);

        // Menambahkan durasi hari dan format tanggal ke setiap booking
        Carbon::setLocale('id'); // Set locale ke bahasa Indonesia

        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->date_start);
            $endDate = Carbon::parse($booking->date_end);

            // Selisih hari
            $duration_days = $startDate->diffInDays($endDate) + 1; // Tambah 1 untuk menghitung tanggal yang sama sebagai 1 hari

            if ($startDate->isSameDay($endDate)) {
                // Tanggal sama
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY');
            } else {
                // Tanggal berbeda
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY') . " s/d " . $endDate->isoFormat('dddd, DD-MM-YYYY');
            }

            $booking->duration_days = $duration_days;
        }

        return view('layouts.bookings.index', compact('bookings', 'availableBuses'));
    }

    public function create(Request $request)
    {
        // Mendapatkan input dari pengguna
        $start = $request->input('date_start', Carbon::now()->format('Y-m-d'));
        $end = $request->input('date_end', Carbon::now()->format('Y-m-d'));
        $type_id = $request->input('type_id');

        // Mendapatkan ID bus yang sudah dipilih dari session
        $selectedBusIds = $request->session()->get('selected_bus_ids', []);

        // Query untuk mendapatkan armada yang tidak memiliki booking aktif
        $availableBuses = Armada::whereDoesntHave('bookingDetails', function ($query) use ($start, $end) {
            $query->whereHas('booking', function ($subQuery) use ($start, $end) {
                $subQuery->where('booking_status', 1) // Hanya booking aktif
                         ->where(function ($q) use ($start, $end) {
                             $q->whereDate('date_start', '<=', $end)
                               ->whereDate('date_end', '>=', $start);
                         });
            });
        })
        ->when($type_id, function ($query) use ($type_id) {
            return $query->where('type_id', $type_id); // Menyaring berdasarkan type armada jika ada
        })
        ->whereDoesntHave('bookings', function ($query) {
            $query->where('booking_status', 1);
        })
        ->get();

        // Mendapatkan data lain untuk view
        $typearmadas = TypeArmada::all();
        $tujuans = Tujuan::all();
        $firstBooking = Booking::first();
        $newBookingNumber = $firstBooking ? $firstBooking->generateBookingNumber() : (new Booking())->generateBookingNumber();


        // Mengembalikan view dengan data yang diperlukan
        return view('layouts.bookings.create', [
            'availableBuses' => $availableBuses,
            'selectedBusIds' => $selectedBusIds,
            'tujuans' => $tujuans,
            'typearmadas' => $typearmadas,
            'request' => [
                'start' => $start,
                'end' => $end,
                'type_id' => $type_id,
                'newBookingNumber' => $newBookingNumber,
            ]
        ]);
    }

    public function store(Request $request)
{
    // Validasi input dari pengguna
    $validatedData = $request->validate([
        'customer' => 'required|string|max:255',
        'telephone' => 'required|numeric',
        'date_start' => 'required|date',
        'date_end' => 'required|date',
        'type_id' => 'required|integer|exists:type_armadas,id',
        'tujuan_id' => 'required|integer|exists:tujuans,id',
        'total_bus' => 'required|integer',
        'bus_ids' => 'required|array',
        'bus_ids.*' => 'integer|exists:armadas,id',
        'pengemudi_id' => 'required|integer|exists:pengemudis,id',
        'kondektur_id' => 'required|integer|exists:kondekturs,id',
        // Tambahkan validasi lain jika diperlukan
    ]);

    // Tambahkan nomor booking yang dihasilkan dan nilai default lainnya
    $validatedData['no_booking'] = (new Booking())->generateBookingNumber();
    $validatedData['harga_std'] = 0;
    $validatedData['total_payment'] = 0;
    $validatedData['penalty_price'] = 0;
    $validatedData['diskon'] = 0;
    $validatedData['biaya_jemput'] = 0;
    $validatedData['grand_total'] = 0;
    $validatedData['total_pengeluaran'] = 0;
    $validatedData['total_pendapatan'] = 0;
    $validatedData['payment_status'] = 2;
    $validatedData['booking_status'] = 1;

    // Buat booking baru menggunakan mass assignment
    $booking = Booking::create($validatedData);

    // Menyimpan detail booking untuk setiap bus yang dipilih
    foreach ($validatedData['bus_ids'] as $busId) {
        $bookingDetailData = [
            'booking_id' => $booking->id,
            'armada_id' => $busId,
            'pengemudi_id' => $validatedData['pengemudi_id'],
            'kondektur_id' => $validatedData['kondektur_id'],
            'is_out' => 0,
            'is_in' => 0,
            'jemput' => '',
            'biaya_jemput' => 0,
            'harga_std' => 0,
            'total_harga' => 0,
            'diskon' => 0,
            'total_sisa_uang_jalan' => 0,
            'total_pengeluaran' => 0,
        ];
        BookingDetail::create($bookingDetailData);
    }

    // Perbarui session jika diperlukan
    $request->session()->put('selected_bus_ids', $validatedData['bus_ids']);

    // Redirect ke halaman lain atau kembalikan view dengan pesan sukses
    return redirect()->route('bookings.index')->with('success', 'Booking berhasil disimpan');
}


    // public function store(Request $request)
    // {
    //     // Debug request input
    //     // dd($request->all());

    //     // Logging the request data
    //     Log::info($request->all());

    //     // Validasi input
    //     $validatedData = $request->validate([
    //         'tujuan_id' => 'required|exists:tujuans,id',
    //         'start' => 'required|date|after_or_equal:today',
    //         'end' => 'required|date|after:date_start',
    //         'customer' => 'required|string|max:255',
    //         'telephone' => 'required|string|max:255',
    //         'harga_std' => 'required|numeric',
    //         'total_bus' => 'required|numeric',
    //         'diskon' => 'nullable|numeric',
    //         'biaya_jemput' => 'nullable|numeric',
    //         'grand_total' => 'required|numeric',
    //         'keterangan' => 'nullable|string',
    //         'armada_id' => 'required|array',
    //         'armada_id.*' => 'exists:armadas,id',
    //     ]);

    //     // Konversi format tanggal ke d-m-Y
    //     $dateStart = Carbon::createFromFormat('Y-m-d', $validatedData['date_start'])->format('d-m-Y');
    //     $dateEnd = Carbon::createFromFormat('Y-m-d', $validatedData['date_end'])->format('d-m-Y');

    //     // Generate booking number
    //     $bookingNumber = (new Booking())->generateBookingNumber();

    //     // Menyimpan data booking
    //     $booking = Booking::create([
    //         'tujuan_id' => $validatedData['tujuan_id'],
    //         'start' => $dateStart,
    //         'end' => $dateEnd,
    //         'customer' => $validatedData['customer'],
    //         'telephone' => $validatedData['telephone'],
    //         'harga_std' => $validatedData['harga_std'],
    //         'total_bus' => $validatedData['total_bus'],
    //         'diskon' => $validatedData['diskon'],
    //         'biaya_jemput' => $validatedData['biaya_jemput'],
    //         'grand_total' => $validatedData['grand_total'],
    //         'keterangan' => $validatedData['keterangan'],
    //         'no_booking' => $bookingNumber
    //     ]);

    //     // Menyimpan data armada yang dipilih
    //     $booking->armada()->sync($validatedData['armada_id']);
    //     return redirect('booking/detail/' . $booking->id);
    //     // return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat.');
    // }




    public function edit($id)
    {
        // Mencari booking berdasarkan ID
        $booking = Booking::with('bookingDetails')->find($id);

        // Lanjutkan dengan mengambil data lainnya jika booking ditemukan
        if (!$booking) {
            abort(404, 'Booking not found');
        }

        $bus = Armada::all();
        $tujuans = Tujuan::get();

        // Extract booking details
        $startDate = Carbon::parse($booking->date_start);
        $endDate = Carbon::parse($booking->date_end);
        $startDateFormatted = $startDate->format('Y-m-d');
        $endDateFormatted = $endDate->format('Y-m-d');

        // Check availability for all buses (optional, for UI feedback)
        $allBusesFull = !Armada::whereDoesntHave('bookingDetails', function ($query) use ($startDate, $endDate) {
        $query->whereHas('booking', function ($query) use ($startDate, $endDate) {
            $query->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date_start', [$startDate, $endDate])
                ->orWhereBetween('date_end', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->where('date_start', '<', $startDate)
                    ->where('date_end', '>', $endDate);
                });
            });
        });
        })->exists();

        // Fetch available buses for selection
        $availableBuses = Armada::whereDoesntHave('bookingDetails', function ($query) use ($startDate, $endDate, $id) {
        $query->whereHas('booking', function ($query) use ($startDate, $endDate, $id) {
            $query->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date_start', [$startDate, $endDate])
                ->orWhereBetween('date_end', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->where('date_start', '<', $startDate)
                    ->where('date_end', '>', $endDate);
                });
            })->where('id', '!=', $id);
        });
        })->get();

        // Get selected bus IDs from existing booking details
        $selectedBusIds = $booking->bookingDetails->pluck('armada_id')->toArray();

        // Return the view with necessary data
        return view('layouts.bookings.edit', compact('booking', 'availableBuses', 'tujuans', 'allBusesFull', 'startDateFormatted', 'endDateFormatted', 'selectedBusIds'));
        }

        public function update(Request $request, $id)
        {
            // Validasi data input
            $request->validate([
                'tujuan_id' => 'required|integer|exists:tujuans,id',
                'startDate' => 'required|date',
                'endDate' => 'required|date',
                'customer' => 'required|string|max:255',
                'telephone' => 'required|string|max:255',
                'harga_std' => 'required|string',
                'total_bus' => 'required|integer',
                'diskon' => 'nullable|numeric',
                'biaya_jemput' => 'nullable|numeric',
                'grand_total' => 'required|numeric',
                'keterangan' => 'nullable|string',
                'armada_id' => 'required|array',
                'armada_id.*' => 'integer|exists:armadas,id',
            ]);

            // Dapatkan booking berdasarkan ID
            $booking = Booking::findOrFail($id);

            // Konversi harga dari format teks ke integer untuk penyimpanan
            $hargaStd = str_replace(['.', ','], '', $request->input('harga_std')); // Menghapus titik dan koma
            $diskon = str_replace(['.', ','], '', $request->input('diskon'));
            $biayaJemput = str_replace(['.', ','], '', $request->input('biaya_jemput'));
            $grandTotal = str_replace(['.', ','], '', $request->input('grand_total'));

            // Update data booking
            $booking->update([
                'tujuan_id' => $request->tujuan_id,
                'startDate' => $request->start,
                'endDate' => $request->end,
                'customer' => $request->customer,
                'telephone' => $request->telephone,
                'harga_std' => (int)$hargaStd,
                'total_bus' => $request->total_bus,
                'diskon' => $diskon ? (int)$diskon : null,
                'biaya_jemput' => $biayaJemput ? (int)$biayaJemput : null,
                'grand_total' => (int)$grandTotal,
                'keterangan' => $request->keterangan,
            ]);

            // Update atau buat detail booking
            $booking->bookingDetails()->delete(); // Delete old booking details
            $booking->bookingDetails()->createMany(
                array_map(function ($armadaId) use ($booking) {
                    return ['booking_id' => $booking->id, 'armada_id' => $armadaId];
                }, $request->armada_id)
            );

            return redirect()->route('bookings.index')->with('success', 'Booking berhasil diupdate');
        }

        public function exportBookingsToExcel(Request $request)
        {
            $start_date = $request->input('date_start', Carbon::now()->format('Y-m-d'));
            $end_date = $request->input('date_end', Carbon::now()->format('Y-m-d'));

            $title = 'Laporan Bookings';
            $period = 'Periode: ' . Carbon::parse($start_date)->isoFormat('DD MMMM YYYY') . ' s/d ' .
            Carbon::parse($end_date)->isoFormat('DD MMMM YYYY');

            return Excel::download(new BookingsExport($start_date, $end_date, $title, $period), 'bookings.xlsx');
        }


    // public function getTujuan(Request $request)
    // {
    //     $search = $request->name;

    //     $tujuan = Tujuan::where('nama_tujuan', 'like', '%' . $search . '%')->get();

    //     return response()->json($tujuan);
    // }

    // public function getTotalHargaStd(Request $request)
    // {
    //     $tujuan = Tujuan::findOrFail($request->tujuan_id);

    //     $totalHargaStd = $tujuan->harga_std;

    //     return response()->json(['total_harga_std' => number_format($totalHargaStd, 0, ',', '.')]);
    // }

}
