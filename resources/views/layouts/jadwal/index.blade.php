@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Jadwal Supir</h5>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Pengemudi</th>
                        <th>No Booking</th>
                        <th>Nomor Body</th>
                        <th>Tujuan</th>
                        <th>Tanggal Berangkat</th>
                        <th>Tanggal Pulang</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($jadwal as $data)
                        <tr>
                            <td>{{ $data->pengemudis ? $data->pengemudis->users->name : '' }}</td>
                            <td>{{ $data->bookings->no_booking }}</td>
                            <td>{{ $data->armadas->nobody }}</td>
                            <td>{{ $data->bookings->tujuan->nama_tujuan }}</td>
                            <td>{{ $data->bookings->date_start }}</td>
                            <td>{{ $data->bookings->date_end }}</td>
                            <td>
                                <div class="dropdown text-center">
                                    <a href="{{ route('booking/edit', $data->id) }}">
                                        <button type="button" class="btn rounded-pill btn-warning"
                                            fdprocessedid="c80zr4">detail</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
