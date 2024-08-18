@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
            integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Select2 JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <div class="card text-center">
        <h5 class="card-header">Report Bookings</h5>
    </div>
    <div class="card mt-4">
        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        {{-- <label for="date1">Kecamatan:</label> --}}
                        <input type="date" style="height: 40px" class="form-control"
                            placeholder="kelurahan atau kecamatan" value="{{ $request['date_start'] }}" name="date_start"
                            id="date_start">
                    </div>
                    <div class="col-sm-3 mt-2">
                        {{-- <label for="date1">Kecamatan:</label> --}}
                        <input type="date" style="height: 40px" class="form-control"
                            placeholder="kelurahan atau kecamatan" value="{{ $request['date_end'] }}" name="date_end"
                            id="date_end">
                    </div>
                    {{-- <div class="col-sm-3 mt-2">
                        <input type="date" class="form-control" placeholder="NIK" name="tanggal" id="tanggal">
                    </div> --}}
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="Nama Customer" name="customer"
                            id="customer">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="No Booking" name="no_booking"
                            id="no_booking">
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
                        <th>No Booking</th>
                        <th>Tanggal Booking</th>
                        <th>Jumlah Bus</th>
                        <th>Nama Pemesan</th>
                        <th>Tujuan</th>
                        <th>Pendapatan</th>
                        {{-- <th class="text-center">Actions</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($booking as $data)
                        <tr>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->date_start }}</td>
                            <td>{{ $data->total_bus }}</td>
                            <td>{{ $data->customer }}</td>
                            <td>{{ $data->tujuan->nama_tujuan }}</td>
                            <td>{{ number_format($data->total_pendapatan) }}</td>
                            {{-- <td>{{ $data->lokasi_jemput }}</td> --}}
                            {{-- <td>
                                @foreach ($data->tujuans() as $item)
                                    {{ $item->nama_tujuan }}
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td> --}}
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        // Jika terdapat pesan error dari server, tampilkan pesan toastr
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endsection
