@extends('main')

@section('title', 'Jadwal Sewa Bus')

@section('head')
<style>
    .filled {
        background-color: red;
        color: white;
    }
    .available {
        background-color: white;
        color: green;
    }
    .table-container {
        width: 100%;
        overflow-x: auto;
        overflow-y: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 10px;
        text-align: center;
        min-width: 100px;
    }
    .bus-name {
        text-align: left;
        padding-left: 10px;
        background-color: #f2f2f2;
        position: sticky;
        left: 0;
        z-index: 1;
    }
    thead th {
        position: sticky;
        top: 0;
        background: white;
        z-index: 2;
    }
</style>

@endsection

@section('content')
    <h3>Jadwal Sewa Bus</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('schedule.show', ['date_start' => $date_start, 'date_end' => $date_end]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12 mb-4">
                            <label for="start" class="form-label">
                                <a href="{{ route('schedule.show', ['date_start' => $date_start, 'date_end' => $date_end]) }}" class="text-lx text-red-700">Tanggal Pemakaian</a>
                            </label>
                            <div class="input-group input-daterange">
                                <input type="date" id="date_start" name="date_start" class="form-control" value="{{ $date_start }}">
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="date_end" name="date_end" class="form-control" value="{{ $date_end }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-4">
                            <label for="type_id" class="form-label">Type Armada</label>
                            <select id="type_id" name="type_id" class="select2 form-select" data-allow-clear="false">
                                <option value="">All Types</option>
                                @foreach($typearmadas as $type)
                                    <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('jadwalbus.pdf', ['date_start' => $date_start, 'date_end' => $date_end, 'type_id' => request('type_id')]) }}" class="btn btn-secondary w-100 text-nowrap" target="_blank">Download PDF</a>
                        </div>
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('booking.create') }}" class="btn btn-primary w-100 text-nowrap" target="_blank">+ Booking</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card mt-4">
        <div class="table-container">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover" style="zoom: 0.85">
                    <thead>
                        <tr>
                            <th style="font-size: 14px">No.Body</th>
                            <th style="font-size: 14px">Type Bus</th>
                            @foreach($dates as $date => $day)
                            <th style="font-size: 14px; text-align: center; vertical-align: middle;">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($armadas as $armada)
                            <tr>
                                <td class="bus-name" style="font-size: 14px">{{ $armada->nobody }}</td>
                                <td class="bus-name" style="font-size: 14px">{{ $armada->type_armada->name }}</td>

                                @foreach($dates as $date => $day)
                                <td class="{{ $schedule[$armada->id][$date] == 'Tersedia' ? 'available' : 'filled' }}"
                                    style="background-color: {{ $schedule[$armada->id][$date] == 'Tersedia' ? 'white' : 'red' }};
                                        color: {{ $schedule[$armada->id][$date] == 'Tersedia' ? 'green' : 'white' }};
                                        font-size: 14px;">
                                    @if($schedule[$armada->id][$date] == 'Tersedia')
                                        <a href="{{ route('booking.create', ['armada_id' => $armada->id, 'date' => $date]) }}" style="color: green; text-decoration: none;">
                                            {!! $schedule[$armada->id][$date] !!}
                                        </a>
                                    @else
                                        <a href="{{ route('booking.showDetail', ['id' => $armada->id, 'date' => $date]) }}" style="color: white; text-decoration: none;">
                                            {!! $schedule[$armada->id][$date] !!}
                                        </a>
                                    @endif
                                </td>
                            @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" style="text-align: right; background-color: #ada6ff;"><strong>Total:</strong></td>
                            @foreach($dates as $date => $day)
                                <td style="font-size: 14px;"><strong>{{ $totalArmadas[$date] ?? 0 }}</strong></td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="intro-y col-span-12">
                <div class="card-footer">
                    {{ $schedules->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
