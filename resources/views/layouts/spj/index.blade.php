@extends('main')
@section('content')

    <div class="card text-center">
        <h5 class="card-header">SPJ</h5>
    </div>
    <div class="card mt-4">
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-3">
                        <input type="text" class="form-control" placeholder="Nama Customer" name="customer"
                            id="customer">
                    </div>
                    <div class="col-sm-3 mt-3">
                        <input type="text" class="form-control" placeholder="No Booking" name="no_booking"
                            id="no_booking">
                    </div>
                    <div class="col-sm-2 mt-3">
                        <input type="date" class="form-control" placeholder="No Booking" name="start_date"
                            id="no_booking">
                    </div>
                    <div class="col-sm-2 mt-3">
                        <input type="date" class="form-control" placeholder="No Booking" name="end_date"
                            id="no_booking">
                    </div>
                    <div class="col-sm-2 mt-2">
                        <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2" style="height: 40px"
                            id="search_btn">Search</button>
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
                        <th>Nama</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($booking as $data)
                        <tr>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->customer }}</td>
                            <td>{{ $data->date_start }}</td>
                            <td>{{ $data->date_end }}</td>
                            <td>
                                <div class="dropdown text-center">
                                    <a href="{{ route('spj/detail', $data->id) }}">
                                        <button type="button" class="btn rounded-pill btn-warning"
                                            fdprocessedid="c80zr4">SPJ</button>
                                    </a>
                                </div>
                                {{-- <form  method="POST" action="{{ route('spj/keluar', $data->details->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn rounded-pill btn-warning"
                                            fdprocessedid="c80zr4">SPJ KELUAR</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
