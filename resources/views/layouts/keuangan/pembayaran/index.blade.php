@extends('main')
@section('content')

    <h3>Laporan Pembayaran</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('pembayaran') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-6 mb-4">
                            <label for="start" class="form-label">Tanggal Pembayaran</label>
                            <div class="input-group input-daterange">
                                <input type="date" id="date_start" name="date_start" class="form-control" value="{{ $date_start }}">
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="date_end" name="date_end" class="form-control" value="{{ $date_end }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-2 mb-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" id="search" name="search" class="form-control" value="{{ $search }}">
                        </div>
                        <div class="col-md-2 col-2 mb-4">
                            <label for="type_id" class="form-label">Jenis Pembayaran</label>
                            <select id="type_id" name="type_id" class="form-control">
                                <option value="">Pilih Jenis Pembayaran</option>
                                <option value="1" {{ $type_id == '1' ? 'selected' : '' }}>DP</option>
                                <option value="2" {{ $type_id == '2' ? 'selected' : '' }}>Pembayaran 2</option>
                                <option value="3" {{ $type_id == '3' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-2 mb-4">
                            <label for="booking_id" class="form-label">Booking ID</label>
                            <input type="text" id="booking_id" name="booking_id" class="form-control" value="{{ $bookingId }}">
                        </div>
                        <div class="col-md-2 col-2 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.85">
                <thead>
                    <tr>
                        <th style="font-size: 14px">No Pembayaran</th>
                        <th style="font-size: 14px">Tanggal Pembayaran</th>
                        <th style="font-size: 14px">ID Booking</th>
                        <th style="font-size: 14px">Customer</th>
                        <th style="font-size: 14px">Telephone</th>
                        <th style="font-size: 14px">Pembayaran</th>
                        <th style="font-size: 14px">Bayar Rp.</th>
                        <th style="font-size: 14px">Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($pembayarans as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td>{{ $data->booking->no_booking ?? 'N/A' }}</td>
                        <td>{{ $data->booking->customer ?? 'N/A' }}</td>
                        <td>{{ $data->booking->telephone ?? 'N/A' }}</td>
                        <td>{{ $data->type_payment_id }}</td>
                        <td>{{ $data->price }}</td>
                        <td>{{ $data->status }}</td>
                        <td>
                            <a href="{{ route('pembayaran.create', $data->id) }}">
                                <button type="button" class="btn rounded-pill btn-primary" fdprocessedid="c80zr4">
                                    Bayar
                                </button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $pembayarans->links() }}
            </div>
        </div>
    </div>

@endsection
