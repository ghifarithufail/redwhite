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
        <h5 class="card-header">Bookings</h5>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>No Booking</th>
                        <th>Pesan</th>
                        <th>Tanggal Operasi</th>
                        <th>Jumlah Bus</th>
                        <th>Lama Hari</th>
                        <th>Total Hari Operasi</th>
                        <th>Nama Pemesan</th>
                        <th>Tujuan</th>
                        <th>Harga Standar</th>
                        <th>Diskon</th>
                        <th>Biaya Jemput</th>
                        <th>Total Biaya Booking</th>
                        <th>Total Pengeluaran</th>
                        <th>Total Pendapatan</th>
                        {{-- <th class="text-center">Actions</th> --}}
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($booking as $data)
                        <tr>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->created_at }}</td>
                            <td>{{ $data->date_start }}</td>
                            <td>{{ $data->total_bus }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ $data->customer }}</td>
                            <td>{{ $data->tujuan->nama_tujuan }}</td>
                            <td>{{ number_format($data->harga_std) }}</td>
                            <td>{{ number_format($data->diskon) }}</td>
                            <td>{{ number_format($data->biaya_jemput) }}</td>
                            <td>{{ number_format($data->grand_total) }}</td>
                            <td>{{ number_format($data->total_pengeluaran) }}</td>
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
