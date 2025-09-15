@extends('main')

@section('title', 'Jadwal Sewa Bus')

@section('head')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/main.min.css' rel='stylesheet' />
@endsection

@section('content')
    <h3>Jadwal Sewa Bus</h3>

    <div class="col-12">
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-6 col-12 mb-4">
                        <label for="start" class="form-label">Tanggal Pemakaian</label>
                        <div class="input-group input-daterange">
                            <input type="date" id="date_start" name="date_start" class="form-control"
                                value="{{ request('date_start') }}">
                            <span class="input-group-text">s/d</span>
                            <input type="date" id="date_end" name="date_end" class="form-control"
                                value="{{ request('date_end') }}">
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



    <div class="card mt-4">
        <div class="card-body">
            <div class="table-container">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.17/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.17/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                events: '/schedule/jadwalbus/data'
            });

            calendar.render();
        });
    </script>
@endsection
