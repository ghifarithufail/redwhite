@extends('main')
@section('content')
<div class="card text-center">
    <h5 class="card-header">Bookings Report</h5>
</div>

@php
    $totalSum = 0;
    $totalPendapatan = 0;
@endphp
@foreach ($booking as $data)
    @php
        $bookingTotal = 0;
        foreach ($data->details as $item) {
            $difference = $item->harga_std - $item->total_pengeluaran;
            $totalSum += $difference;
            $bookingTotal += $difference;
        }
        $totalPendapatanPerBooking = ($bookingTotal + $data->biaya_jemput) - $data->diskon;
        $totalPendapatan += $totalPendapatanPerBooking;
    @endphp
@endforeach
<div class="card text-center mt-4">
    <h5 class="card-header">Jumlah Pendapatan : {{ number_format($totalPendapatan) }}</h5>
</div>
@foreach ($booking as $data)
    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <td width="170"><strong>No Booking</strong></td>
                    <td width="1%">:</td>
                    <td>{{ $data->no_booking }}</td>
                </tr>
                <tr>
                    <td width="170"><strong>Nama</strong></td>
                    <td width="1%">:</td>
                    <td>{{ $data->customer }}</td>
                </tr>
                <tr>
                    <td width="170"><strong>Telephone</strong></td>
                    <td width="1%">:</td>
                    <td>0{{ $data->telephone }}</td>
                </tr>
                <tr>
                    <td width="170"><strong>Tujuan</strong></td>
                    <td width="1%">:</td>
                    <td>{{ $data->tujuan->nama_tujuan }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th style="text-align: right">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $bookingTotal = 0;
                        @endphp
                        @foreach ($data->details as $item)
                            @php
                                $difference = $item->harga_std - $item->total_pengeluaran;
                                $bookingTotal += $difference;
                            @endphp
                            <tr>
                                <td><b>Bus</b></td>
                                <td style="text-align: right"><b><i>{{ $item->armadas->nobody }}</i></b></td>
                            </tr>
                            <tr>
                                <td>Harga</td>
                                <td style="text-align: right">{{ number_format($item->harga_std) }}</td>
                            </tr>
                            <tr>
                                <td>Pengeluaran</td>
                                <td style="text-align: right">{{ number_format($item->total_pengeluaran) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: right">Total</td>
                                <td style="text-align: right"><b>{{ number_format($difference) }}</b></td>
                            </tr>
                        @endforeach
                        <tr>
                            <th style="text-align: right">Jumlah : </th>
                            <th style="text-align: right"><b>{{ number_format($bookingTotal) }}</b></th>
                        </tr>
                        <tr>
                            <th style="text-align: right">Biaya Jemput : </th>
                            <th style="text-align: right">{{ number_format($data->biaya_jemput) }}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right">Diskon : </th>
                            @if ($data->diskon)
                                <th style="text-align: right">-{{ number_format($data->diskon) }}</th>
                            @else
                                <th style="text-align: right">0</th>
                            @endif
                        </tr>
                        @php
                            $totalPendapatanPerBooking = $bookingTotal + $data->biaya_jemput - $data->diskon;
                        @endphp
                        <tr>
                            <th style="text-align: right">Total Pendapatan : </th>
                            <th style="text-align: right"><b>{{ number_format($totalPendapatanPerBooking) }}</b></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endforeach
@endsection
