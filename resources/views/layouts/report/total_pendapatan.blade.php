@extends('main')

@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report Total Pendapatan</h5>
    </div>

    {{-- Filter Form --}}
    <div class="card mt-4">
        <div class="card-header">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" id="start_date"
                            value="{{ $request['start_date'] }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" id="end_date"
                            value="{{ $request['end_date'] }}">
                    </div>
                    <div class="col-md-3">
                        <label for="no_booking" class="form-label">No Booking</label>
                        <input type="text" class="form-control" name="no_booking" id="no_booking"
                            value="{{ $request['no_booking'] }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2 align-items-end">
                        <button type="submit" class="btn btn-primary flex-fill" style="height: 40px;">
                            Search
                        </button>
                        <a href="{{ route('report.pendapatan.excel', [
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date'),
                            'no_booking' => request('no_booking'),
                        ]) }}"
                            class="btn btn-success flex-fill text-white" style="height: 40px;">
                            Excel
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered align-middle" style="zoom: 0.9">
                    <thead class="table-light">
                        <tr>
                            <th>No Booking</th>
                            <th>jmlh SPJ</th>
                            <th>Jmlh Hari</th>
                            <th>harga Standar</th>
                            <th>Biaya Jemput</th>
                            <th>Discount</th>
                            <th>Biaya BBM</th>
                            <th>Uang Makan</th>
                            <th>Parkir</th>
                            <th>Biaya Tol</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td>{{ $item['no_booking'] }}</td>
                                <td>{{ $item['jmlh_spj'] }}</td>
                                <td>{{ $item['jmlhHari'] }}</td>
                                <td>{{ number_format($item['harga_std']) }}</td>
                                <td>{{ number_format($item['biaya_jemput']) }}</td>
                                <td>{{ number_format($item['diskon']) }}</td>
                                <td>{{ number_format($item['total_bbm']) }}</td>
                                <td>{{ number_format($item['total_uang_makan']) }}</td>
                                <td>{{ number_format($item['parkir']) }}</td>
                                <td>{{ number_format($item['tol']) }}</td>
                                <td>{{ number_format($item['pendapatan']) }}</td>
                                {{-- <td>0</td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center p-2">
                    {{-- <div>
                    <strong>Total Bus: {{ $bus->total() }}</strong>
                </div> --}}
                    <div>
                        {{ $data->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
