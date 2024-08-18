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
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="search-field">
                <form action="{{ route('booking.index') }}" method="GET">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </form>
            </div>
            <div>
                <a href="{{ route('booking.index') }}" class="m-0"><h5> Daftar Konsumen Booking</h5></a>
                <p class="m-0">Total : {{ App\Models\Booking::count() }} </p>
            </div>
            <div class="add-new-role">
                <!-- Tombol "Add New Role" -->
                <a href="{{ route('booking.create') }}" class="btn btn-primary mb-2 text-nowrap">
                    + Booking
                </a>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.85">
                <thead>
                    <tr>
                        <th style="font-size: 14px">No Booking</th>
                        <th style="font-size: 14px">Nama Customer</th>
                        <th style="font-size: 14px">Telephone</th>
                        <th style="font-size: 14px">Tanggal Berangkat</th>
                        <th style="font-size: 14px">Tanggal Pulang</th>
                        {{-- <th>Penjemputan</th> --}}
                        <th style="font-size: 14px">Tujuan</th>
                        <th class="text-center" style="font-size: 14px">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($booking as $data)
                        <tr>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->customer }}</td>
                            <td>{{ $data->telephone }}</td>
                            <td>{{ $data->date_start }}</td>
                            <td>{{ $data->date_end }}</td>
                            {{-- <td>{{ $data->lokasi_jemput }}</td> --}}
                            <td>
                                @foreach ($data->tujuans() as $item)
                                    {{ $item->nama_tujuan }}
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('booking/pengemudi', $data->id) }}">
                                    <button type="button" class="btn rounded-pill btn-primary" fdprocessedid="c80zr4">Input
                                        Pengemudi</button>
                                </a>
                                <a href="{{ route('booking/edit', ['id' => $data->id, 'start' => $data->date_start, 'end' => $data->date_end]) }}">
                                    <button type="button" class="btn rounded-pill btn-warning"
                                        fdprocessedid="c80zr4">detail</button>
                                </a>
                            </td>
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
