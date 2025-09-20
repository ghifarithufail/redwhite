@extends('main')

@section('content')
<div class="card text-center">
    <h5 class="card-header">Report Bus Dalam Perjalanan</h5>
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
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Summary --}}
<div class="card mt-4">
    <div class="card-body">
        <h6 class="mb-3">Summary</h6>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle" style="zoom: 0.9">
                <thead class="table-light">
                    <tr>
                        <th>Total Bus</th>
                        <th>Total Pengemudi</th>
                        <th>Total Kondektur</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $bus->total() }}</td>
                        <td>{{ $bus->pluck('pengemudis.id')->unique()->count() }}</td>
                        <td>{{ $bus->pluck('kondekturs.id')->filter()->unique()->count() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
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
                        <th>Bus</th>
                        <th>Pengemudi</th>
                        <th>Kondektur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bus as $item)
                        <tr>
                            <td>{{ $item->bookings->no_booking }}</td>
                            <td>{{ $item->armadas->nobody }}</td>
                            <td>{{ $item->pengemudis->users->name }}</td>
                            <td>{{ $item->kondekturs ? $item->kondekturs->users->name : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada data</td>
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
                    {{ $bus->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
