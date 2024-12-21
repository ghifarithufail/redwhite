@extends('main')
@section('content')

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>
    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    Booking Detail 
                </h4>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">

                        <div class="form-group">
                            <label class="control-label col-sm-3">Name :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->customer }}" disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Start Date :</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->date_start)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->date_end)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Total Penumpang :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->total_passanger }}" disabled
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4">Penjemputan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->lokasi_jemput }}" disabled class="form-control" />

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="@foreach ($booking->tujuans() as $key => $item){{ $item->nama_tujuan }}@if (!$loop->last), @endif @endforeach"
                                    disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 mt-4">
                        <h4>
                            Detail Bus
                        </h4>
                        <div class="text-end mb-2">
                            <a href="{{ route('booking/edit', ['id' => $booking->id, 'start' => $booking->date_start, 'end' => $booking->date_end]) }}">
                                <button type="button" class="btn rounded-pill btn-warning">Tambah Bus / Ganti Tujuan</button>
                            </a>
                        </div>
                        <hr>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bus</th>
                                    <th>Supir</th>
                                    <th>Kondektur</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($booking->details as $detail)
                                    <tr>
                                        <td>{{ $detail->armadas?->nobody }}</td>
                                        <td>{{ $detail->pengemudis ? $detail->pengemudis->nopengemudi : '' }} -
                                            {{ $detail->pengemudis ? $detail->pengemudis->users->name : '' }}</td>
                                        <td>{{ $detail->kondekturs ? $detail->kondekturs->nokondektur : '' }} -
                                            {{ $detail->kondekturs ? $detail->kondekturs->users->name : '' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('booking/detail_pengemudi', $detail->id) }}" class="btn btn-primary">
                                                Edit
                                            </a>
                                            <a href="{{ route('delete/bus', $detail->id) }}"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus armada ini?');"
                                                type="button" class="btn btn-danger">
                                                Delete
                                            </a>
                                            <input type="hidden" name="bookingId" id="bookingId"
                                                value="{{ $detail->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            <a href="{{ route('spj/detail', $booking->id) }}"
                                type="button" class="btn btn-warning">
                                kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
