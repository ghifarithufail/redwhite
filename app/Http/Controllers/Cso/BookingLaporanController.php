<?php

namespace App\Http\Controllers\Cso;

use Carbon\Carbon;
use App\Models\TypeArmada;
use App\Models\Cso\Booking;
use App\Models\Admin\Armada;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cso\BookingDetail;
use App\Http\Controllers\Controller;

class BookingLaporanController extends Controller
{
    public function bookingLaporan(Request $request)
    {
        $search = $request->input('search');
        $date_start = $request->input('date_start', Carbon::now()->format('Y-m-d'));
        $date_end = $request->input('date_end', Carbon::now()->format('Y-m-d'));
        $type_id = $request->input('type_id');
        $perPage = $request->input('per_page', 10);

        // Query for Booking
        $bookingsQuery = Booking::whereBetween('date_start', [$date_start, $date_end]);

        // Apply additional conditions if necessary
        if ($type_id) {
            $bookingsQuery->where('type_id', $type_id);
        }

        // Get the bookings
        $bookings = $bookingsQuery->get();

        // Query for unavailable buses
        $unavailableBusIds = BookingDetail::join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->whereBetween('bookings.date_start', [$date_start, $date_end])
            ->orWhereBetween('bookings.date_end', [$date_start, $date_end])
            ->pluck('booking_details.armada_id');

        // Query for available buses
        $availableBuses = Armada::whereNotIn('id', $unavailableBusIds)
            ->when($type_id, function ($query) use ($type_id) {
                return $query->where('type_id', $type_id);
            })
            ->get();

        // Search bookings
        if ($search) {
            // Filter the $bookings collection based on search criteria
            $bookings = $bookings->filter(function ($booking) use ($search) {
                return strpos($booking->id, $search) !== false ||
                    strpos($booking->no_booking, $search) !== false ||
                    strpos($booking->customer, $search) !== false ||
                    strpos($booking->telephone, $search) !== false ||
                    $booking->tujuan->nama_tujuan == $search ||
                    $booking->bookingDetails->armada->nobody == $search;
            });
        }

        // Format date and duration
        Carbon::setLocale('id'); // Set locale to Indonesian

        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->date_start);
            $endDate = Carbon::parse($booking->date_end);

            // Calculate duration days
            $duration_days = $startDate->diffInDays($endDate) + 1;

            // Format date range
            if ($startDate->isSameDay($endDate)) {
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY');
            } else {
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY') . " s/d " . $endDate->isoFormat('dddd, DD-MM-YYYY');
            }

            $booking->duration_days = $duration_days;

            // Calculate total_buses - Example based on your previous logic
            $booking->total_buses = $booking->bookingDetails->count(); // Adjust according to your actual structure
        }

        // Hitung jumlah total bis
        $total_buses = $bookings->sum('total_buses');
        $total_harga_dasar = $bookings->sum('harga_std');
        $total_discount =  $bookings->sum('diskon');
        $total_biaya_jemput = $bookings->sum('biaya_jemput');
        $total_bayar = $bookings->sum('grand_total');

        // Query for type armadas
        $typearmadas = TypeArmada::all(); // Pastikan Anda memiliki model TypeArmada

        return view('layouts.bookings.laporan', compact('bookings', 'availableBuses', 'date_start', 'date_end',
            'typearmadas', 'total_buses', 'total_harga_dasar', 'total_discount', 'total_biaya_jemput', 'total_bayar'));
    }


    public function bookingtglPDF(Request $request)
    {
        $start_date = $request->input('date_start', Carbon::now()->format('Y-m-d'));
        $end_date = $request->input('date_end', Carbon::now()->format('Y-m-d'));

        // Fetch bookings based on the date range
        $bookings = Booking::with(['bookingDetails'])->whereBetween('date_start', [$start_date, $end_date])->get();

        // Format date and duration for each booking
        Carbon::setLocale('id'); // Set locale to Indonesian

        // Initialize totals
        $total_harga_dasar = 0;
        $total_discount = 0;
        $total_biaya_jemput = 0;
        $total_bayar = 0;
        $total_buses = 0;

        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->date_start);
            $endDate = Carbon::parse($booking->date_end);

            // Calculate duration days
            $duration_days = $startDate->diffInDays($endDate) + 1;

            // Format date range
            if ($startDate->isSameDay($endDate)) {
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY');
            } else {
                $booking->formatted_date_range = $startDate->isoFormat('dddd, DD-MM-YYYY') . " s/d " . $endDate->isoFormat('dddd, DD-MM-YYYY');
            }

            $booking->duration_days = $duration_days;

            // Sum totals
            $total_harga_dasar += $booking->harga_std;
            $total_discount += $booking->diskon;
            $total_biaya_jemput += $booking->biaya_jemput;
            $total_bayar += $booking->grand_total;
            $total_buses += $booking->bookingDetails->count();
        }

        // Prepare data for the view or PDF
        $data = compact('bookings', 'start_date', 'end_date', 'total_harga_dasar', 'total_discount', 'total_biaya_jemput', 'total_bayar', 'total_buses');

        if ($request->has('generate_pdf')) {
            $pdf = Pdf::loadView('layouts.bookings.pdf', $data);
            $pdf->setPaper('F4', 'landscape');
            return $pdf->download('booking_tgl.pdf');
        }

        return view('layouts.bookings.pdf', $data);
    }



}
