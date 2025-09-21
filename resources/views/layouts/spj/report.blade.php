@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report SPJ</h5>
    </div>
    <div class="card mt-4">

        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        <input type="date" style="height: 40px" class="form-control" placeholder="kelurahan atau kecamatan"
                            value="{{ $request['start_date'] }}" name="start_date" id="start_date">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="date" style="height: 40px" class="form-control"
                            placeholder="kelurahan atau kecamatan" value="{{ $request['end_date'] }}" name="end_date"
                            id="end_date">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="customer" name="customer" id="customer">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="No Booking" name="no_booking"
                            id="no_booking">
                    </div>
                    <div class="col-sm-2 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded text-white mt-2" style="height: 40px;"
                                id="search_btn">Search</button>
                            <a href="{{ route('spj/excel', [
                                'start_date' => $request['start_date'],
                                'end_date' => $request['end_date'],
                                'customer' => $request['customer'],
                                'no_booking' => $request['no_booking'],
                            ]) }}"
                                class="btn btn-success rounded text-white mt-2" style="height: 40px;"
                                id="search_btn">excel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>No Booking</th>
                        <th>Customer</th>
                        <th>Waktu</th>
                        <th>Uang Berangkat</th>
                        <th>BOP</th>
                        <th>Sisa Biaya Keluar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($spj as $data)
                        <tr>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->customer }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->date_start)->translatedFormat('l, d F Y') }} S/D
                                {{ \Carbon\Carbon::parse($data->date_end)->translatedFormat('l, d F Y') }}
                            </td>

                            <td>{{ number_format($data->total_uang_berangkat) }}</td>
                            <td>{{ number_format($data->bop) }}</td>
                            <td>{{ number_format($data->sisa_biaya_keluar) }}</td>
                            <td>
                                <a href="{{ route('spj/report/detail', $data->booking_detail_id) }}">
                                    <button type="button" class="btn rounded-pill btn-primary" fdprocessedid="c80zr4">Detail</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end p-3">
                {{ $spj->links() }}
            </div>
        </div>
    </div>
@endsection
