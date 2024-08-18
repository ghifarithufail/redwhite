@extends('main')

@section('title', 'Jadwal kondektur')

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
    <h3>Jadwal Kondektur Wisata</h3>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('kondektur.show', ['date_start' => $date_start, 'date_end' => $date_end]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12 mb-4">
                            <label for="start" class="form-label">
                                <a href="{{ route('kondektur.show', ['date_start' => $date_start, 'date_end' => $date_end]) }}" class="text-lx text-red-700">Tanggal Pemakaian</a>
                            </label>
                            <div class="input-group input-daterange">
                                <input type="date" id="date_start" name="date_start" class="form-control" value="{{ $date_start }}">
                                <span class="input-group-text">s/d</span>
                                <input type="date" id="date_end" name="date_end" class="form-control" value="{{ $date_end }}">
                            </div>
                        </div>

                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        {{-- <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('jadwalkondektur.pdf', ['date_start' => $date_start, 'date_end' => $date_end, 'type_id' => request('type_id')]) }}" class="btn btn-secondary w-100 text-nowrap" target="_blank">Download PDF</a>
                        </div> --}}
                        <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                            <a href="{{ route('booking/create') }}" class="btn btn-primary w-100 text-nowrap" target="_blank">+ Booking</a>
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
                            <th style="font-size: 14px">No.Kondektur</th>
                            <th style="font-size: 14px">Nama Kondektur</th>
                            @foreach($dates as $date => $day)
                            <th style="font-size: 14px; text-align: center; vertical-align: middle;">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kondekturs as $kondektur)
                            <tr>
                                {{-- <td>
                                    <a href="{{ route('booking/detail', $data->id) }}" class="bus-name">{{ $armada->nobody }}</a>
                                </td> --}}
                                <td class="bus-name" style="font-size: 14px">{{ $kondektur->nokondektur }}</td>
                                <td class="bus-name" style="font-size: 14px">{{ $kondektur->users->name }}</td>

                                @foreach($dates as $date => $day)
                                <td class="{{ $schedule[$kondektur->id][$date] == 'Standbye' ? 'available' : 'filled' }}"
                                    style="background-color: {{ $schedule[$kondektur->id][$date] == 'Standbye' ? 'white' : 'red' }};
                                        color: {{ $schedule[$kondektur->id][$date] == 'Standbye' ? 'green' : 'white' }};
                                        font-size: 14px;">
                                    {!! $schedule[$kondektur->id][$date] !!}
                                </td>
                            @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="bus-name" style="font-size: 14px"><strong>Total Armada</strong></td>
                            @foreach($dates as $date => $day)
                                <td style="font-size: 14px;"><strong>{{ $totalArmadas[$date] ?? 0 }}</strong></td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $schedules->appends(request()->input())->links() }}
                    {{-- {{ $schedules->appends(['search' => $search, 'date_start' => $date_start, 'date_end' => $date_end, 'perpage' => $perPage])->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
