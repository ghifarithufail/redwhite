
@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report SPJ</h5>
    </div>
    <div class="card mt-4">

        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    {{-- <div class="col-sm-3 mt-2">
                        <input type="date" class="form-control" placeholder="NIK" name="tanggal" id="tanggal">
                    </div> --}}
                    <div class="col-sm-3 mt-2">
                        {{-- <label for="date1">Kecamatan:</label> --}}
                        <input type="date" style="height: 40px" class="form-control"
                            placeholder="kelurahan atau kecamatan" value="{{ $request['date_start'] }}" name="date_start" id="date_start">
                    </div>
                    <div class="col-sm-3 mt-2">
                        {{-- <label for="date1">Kecamatan:</label> --}}
                        <input type="date" style="height: 40px" class="form-control"
                            placeholder="kelurahan atau kecamatan" value="{{ $request['date_end'] }}" name="date_end" id="date_end">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="No SPJ" name="no_spj" id="no_spj">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="No Booking" name="no_booking" id="no_booking">
                    </div>
                    <div class="col-sm-2">
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
                        <th>No SPJ</th>
                        <th>No Booking</th>
                        <th>Bus</th>
                        <th>Jam Jemput</th>
                        <th>BBM</th>
                        <th>Uang Makan</th>
                        <th>Parkir</th>
                        <th>Tol</th>
                        <th>Biaya Lain</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($spj as $data)
                        <tr>
                            <td>{{ $data->no_spj }}</td>
                            <td>{{ $data->booking_details->bookings->no_booking }}</td>
                            <td>{{ $data->booking_details->armadas->nobody }}</td>
                            <td>{{ $data->jam_jemput }}</td>
                            <td>{{ $data->bbm }}</td>
                            <td>{{ $data->uang_makan }}</td>
                            <td>{{ $data->parkir }}</td>
                            <td>{{ $data->tol }}</td>
                            <td>{{ $data->biaya_lain ? $data->biaya_lain : '-' }}</td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
