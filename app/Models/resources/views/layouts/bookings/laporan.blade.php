@extends('main')
@section('content')

    <h3>Laporan Booking</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('bookingLaporan') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12 mb-4">
                            <label for="start" class="form-label">Tanggal Pemakaian</label>
                            <div class="input-group input-daterange">
                                <input type="date" id="date_start" name="date_start" class="form-control" value="{{ $date_start }}">
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="date_end" name="date_end" class="form-control" value="{{ $date_end }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('cso.bookingtglPDF', ['date_start' => $date_start, 'date_end' => $date_end]) }}"
                                class="btn btn-secondary w-100 text-nowrap" target="_blank">Download PDF</a>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('export.bookings', ['date_start' => $date_start, 'date_end' => $date_end]) }}"
                                class="btn btn-success w-100 text-nowrap" target="_blank">Export Excel</a>
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
                        <th style="font-size: 14px">No Booking</th>
                        <th style="font-size: 14px">Nama Customer</th>
                        <th style="font-size: 14px">Telephone</th>
                        <th style="font-size: 14px">Tanggal Pemakaian</th>
                        <th style="font-size: 14px">Durasi</th>
                        <th style="font-size: 14px">Tujuan</th>
                        <th style="font-size: 14px">Harga Dasar</th>
                        <th style="font-size: 14px">Total Bis</th>
                        <th style="font-size: 14px">Discount</th>
                        <th style="font-size: 14px">Biaya Jemput</th>
                        <th style="font-size: 14px">Total Bayar</th>
                        <th class="text-center" style="font-size: 14px">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($bookings as $data)
                        <tr>
                            <td>{{ $data->no_booking }}</td>
                            <td>{{ $data->customer }}</td>
                            <td>{{ $data->telephone }}</td>
                            {{-- <td>{{ $data->date_start }}</td> --}}
                            <td>{{ $data->formatted_date_range }}</td>
                            <td>{{ $data->duration_days }} Hari</td>
                            <td>
                                @if ($data->tujuan)
                                    {{ $data->tujuan->nama_tujuan }}
                                @else
                                    Tidak tersedia
                                @endif
                            </td>
                            <td style="text-align: right">{{ number_format($data->harga_std, 0, ',', '.') }}</td>
                            <td style="text-align: right">{{ $data->total_buses }} Bis</td>
                            <td style="text-align: right">{{ number_format($data->diskon, 0, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($data->biaya_jemput, 0, ',', '.') }}</td>
                            <td style="text-align: right">{{ number_format($data->grand_total, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('bookings.edit', $data->id) }}">
                                    <button type="button" class="btn rounded-pill btn-primary" fdprocessedid="c80zr4">
                                        Edit</button>
                                </a>
                                <a href="{{ route('booking/pengemudi', $data->id) }}">
                                    <button type="button" class="btn rounded-pill btn-primary" fdprocessedid="c80zr4">Input
                                        Pengemudi</button>
                                </a>
                                <a href="{{ route('booking.edit', $data->id) }}">
                                    <button type="button" class="btn rounded-pill btn-warning"
                                        fdprocessedid="c80zr4">detail</button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right; background-color: #babcff;">
                            <strong style="color: black;">Total:</strong>
                        </td>
                        <td style="text-align: right; background-color: #babcff; color: black;">
                            {{ number_format($total_harga_dasar, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; background-color: #babcff; color: black;">
                            {{ $total_buses }} Bis
                        </td>
                        <td style="text-align: right; background-color: #babcff; color: black;">
                            {{ number_format($total_discount, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; background-color: #babcff; color: black;">
                            {{ number_format($total_biaya_jemput, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; background-color: #babcff; color: black;">
                            {{ number_format($total_bayar, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
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
