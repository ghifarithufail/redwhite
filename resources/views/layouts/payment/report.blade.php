@extends('main')
@section('content')
    <h3>Laporan Booking</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-3 col-4 mb-4">
                            <label for="start" class="form-label">Nama</label>
                            <div class="input-group input-daterange">
                                <input type="text" id="nama" name="nama" value="{{ $request['nama'] }}" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3 col-4 mb-4">
                            <label for="start" class="form-label">No Booking</label>
                            <div class="input-group input-daterange">
                                <input type="text" id="no_booking" name="no_booking" value="{{ $request['no_booking'] }}" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4 col-4 mb-4">
                            <label for="start" class="form-label">Tanggal Pemakaian</label>
                            <div class="input-group input-daterange">
                                <input type="date" id="start_date" name="start_date" value="{{ $request['start_date'] }}" class="form-control" >
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="end_date" value="{{ $request['end_date'] }}" name="end_date" class="form-control" >
                            </div>
                        </div>
                        
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        {{-- <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('cso.bookingtglPDF', ['date_start' => $date_start, 'date_end' => $date_end]) }}"
                                class="btn btn-secondary w-100 text-nowrap" target="_blank">Download PDF</a>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('export.bookings', ['date_start' => $date_start, 'date_end' => $date_end]) }}"
                                class="btn btn-success w-100 text-nowrap" target="_blank">Export Excel</a>
                        </div> --}}
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
                        <th style="font-size: 14px">Nomor booking</th>
                        <th style="font-size: 14px">Nama Customer</th>
                        <th style="font-size: 14px">Tanggal Wisata</th>
                        <th style="font-size: 14px">Diskon</th>
                        <th style="font-size: 14px">Total Biaya</th>
                        <th style="font-size: 14px">Status</th>
                        <th style="font-size: 14px">action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach ($booking as $data)
                    <tr>
                        <td>{{$data->no_booking}}</td>
                        <td>{{$data->customer}}</td>
                        <td>{{ \Carbon\Carbon::parse($data->date_start)->format('j F Y') }} - {{ \Carbon\Carbon::parse($data->date_end)->format('j F Y') }}</td>
                        <td class="text-right">{{number_format($data->diskon)}}</td>
                        <td class="text-right">{{number_format($data->grand_total)}}</td>
                        <td>
                            @if ($data->payment_status == '2')
                                <span class="badge bg-label-danger me-1">Belum Lunas</span>
                            @else
                                <span class="badge bg-label-success me-1">Lunas</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('payment/detail_report', $data->id) }}">
                                <button type="button" class="btn rounded-pill btn-primary" fdprocessedid="c80zr4">Detail</button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3">
                {{$booking->links()}}
            </div>
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
