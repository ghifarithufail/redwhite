@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>
    <h3>Pembayaran</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-4 col-6 mb-4">
                            <label for="start" class="form-label">Booking Dibuat</label>
                            <div class="input-group input-daterange">
                                <input type="date" id="date_start" name="date_start" value="{{ $request['date_start'] }}" class="form-control" >
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="date_end" value="{{ $request['date_end'] }}" name="date_end" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3 col-2 mb-4">
                            <label for="customer" class="form-label">customer</label>
                            <input type="text" id="customer" name="customer" value="{{ $request['customer'] }}" class="form-control">
                        </div>
                        <div class="col-md-3 col-2 mb-4">
                            <label for="no_booking" class="form-label">No Booking</label>
                            <input type="text" id="no_booking" name="no_booking" value="{{ $request['no_booking'] }}" class="form-control">
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
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No Booking</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Tanggal Booking Dibuat</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($booking as $data)
                        <tr>
                            <td>{{ $data->customer }}</td>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->date_start }}</td>
                            <td>{{ $data->date_end }}</td>
                            <td>{{ $data->created_at }}</td>

                            <td>
                                <div class="dropdown text-center">
                                    <a href="{{ route('payment/create', $data->id) }}" target="_blank">
                                        <button type="button" class="btn rounded-pill btn-warning"
                                            fdprocessedid="c80zr4">Payment</button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="d-flex justify-content-end p-3">
                {{ $booking->links() }}
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Jika terdapat pesan sukses dari server, tampilkan pesan toastr
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        // Jika terdapat pesan error dari server, tampilkan pesan toastr
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
@endsection
