@extends('main')
@section('content')
    <h3>Laporan Booking</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-4">
                            <label for="start" class="form-label">Tanggal Pemakaian</label>
                            <div class="input-group input-daterange">
                                <input type="date" id="date_start" name="date_start" value="{{ $request['date_start'] }}" class="form-control" >
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="date_end" value="{{ $request['date_end'] }}" name="date_end" class="form-control" >
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
                        <th style="font-size: 14px">Tanggal</th>
                        <th style="font-size: 14px">Nama Customer</th>
                        <th style="font-size: 14px">Kwitansi</th>
                        <th style="font-size: 14px">Tanggal Wisata</th>
                        <th style="font-size: 14px">Hari</th>
                        <th style="font-size: 14px">JML UNIT</th>
                        <th style="font-size: 14px">Tujuan Wisata</th>
                        <th style="font-size: 14px">Jenis Pembayaran</th>
                        <th style="font-size: 14px">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach ($payments as $date => $group)
                        <tr>
                            <td colspan="9" style="background-color: #dff0d8;"><strong>JUMLAH TANGGAL
                                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong></td>
                        </tr>
                        @foreach ($group as $data)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</td>
                                <td>{{ $data->customer }}</td>
                                <td>{{ $data->no_payment }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->date_start)->format('d M Y') }}</td>
                                <td>{{ $data->total_days }} Hari</td>
                                <td>{{ $data->total_bus }}</td>
                                <td>{{ $data->nama_tujuan }}</td>
                                <td>
                                    @if ($data->pembayaran_ke == 1)
                                        <b>Pembayaran pertama</b>
                                    @elseif($data->pembayaran_ke == 2)
                                        <b>Pembayaran kedua</b>
                                    @elseif($data->pembayaran_ke == 3)
                                        <b>Pembayaran Ketiga</b>
                                    @elseif($data->pembayaran_ke == 4)
                                        <b>Pelunasan</b>
                                    @endif
                                </td>
                                <td>{{ number_format($data->price) }}</td>
                            </tr>
                        @endforeach
                        <tr style="background-color: #dff0d8;">
                            <td colspan="8" class="text-right"><strong>Total
                                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}:</strong></td>
                            <td><strong>{{ number_format($totalPrices[$date]) }}</strong></td>
                        </tr>
                        @php
                            $grandTotal += $totalPrices[$date];
                        @endphp
                    @endforeach

                    <tr style="background-color: #dff0d8;">
                        <td colspan="8" class="text-right"><strong>Total Pendapatan:</strong></td>
                        <td><strong>{{ number_format($grandTotal) }}</strong></td>
                    </tr>
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
