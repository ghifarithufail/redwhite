@extends('main')

@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report Bus SPJ Masuk</h5>
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
                        <label for="no_spj" class="form-label">No SPJ</label>
                        <input type="text" class="form-control" name="no_spj" id="no_spj"
                            value="{{ $request['no_spj'] }}">
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
                        <a href="{{ route('report.spj.masuk.excel', [
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date'),
                            'no_spj' => request('no_spj'),
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
                            <th>No SPJ</th>
                            <th>No Booking</th>
                            <th>Bus</th>
                            <th>Pengemudi</th>
                            <th>Kondektur</th>
                            <th>Uang Berangkat</th>
                            <th>BOP</th>
                            <th>Sisa Uang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spj as $item)
                            <tr>
                                <td>{{ $item->no_spj }}</td>
                                <td>{{ $item->booking_details->bookings->no_booking }}</td>
                                <td>{{ $item->booking_details->armadas->nobody }}</td>
                                <td>{{ $item->booking_details->pengemudis->users->name }}</td>
                                <td>{{ $item->booking_details->kondekturs ? $item->booking_details->kondekturs->users->name : '-' }}
                                </td>
                                <td>{{ number_format($item->uang_jalan) }}</td>
                                <td>{{ number_format($item->pengeluaran) }}</td>
                                <td>{{ number_format($item->sisa_uang_jalan) }}</td>
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
                        {{ $spj->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
