<?php

namespace App\Http\Controllers\Cso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SeatBookingController extends Controller
{
    public function bookSeats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'seats' => 'required|array',
            'seats.*' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Simpan pemesanan ke database atau proses lainnya
        // Misalnya, menyimpan ke database

        // Contoh penyimpanan sementara dalam array (untuk demonstrasi)
        $bookedSeats = session()->get('bookedSeats', []);
        $bookedSeats = array_merge($bookedSeats, $request->seats);
        session(['bookedSeats' => $bookedSeats]);

        return response()->json(['message' => 'Seats booked successfully!', 'seats' => $request->seats], 200);
    }
}
