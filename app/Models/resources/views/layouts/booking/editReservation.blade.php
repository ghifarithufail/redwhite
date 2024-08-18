@extends('main')
@section('content')

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>
    <style>
        .bus-items {
            height: 250px;
            overflow-y: auto;
            /* Menambahkan scrollbar jika konten melebihi ketinggian */
        }
    </style>

    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    Booking Detail EDIT {{ $booking->no_booking }}
                </h4>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">

                        <div class="form-group">
                            <label class="control-label col-sm-3">Name :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->customer }}" disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Start Date :</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->date_start)->format('Y-m-d') }}"
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">

                        <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="@foreach ($booking->tujuans() as $key => $item){{ $item->nama_tujuan }}@if (!$loop->last), @endif @endforeach"
                                    disabled class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->date_end)->format('Y-m-d') }}"
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">
                    <form>
                        <div class="form-group row">
                            <div class="col-sm-3 mt-2">
                                <input type="date" class="form-control" placeholder="NIK" name="start"
                                    value="{{ $request['start'] }}" id="start">
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="date" class="form-control" placeholder="NIK" name="end"
                                    value="{{ $request['end'] }}" id="end">
                            </div>
                            <div class="col-sm-3 mt-2">
                                <select class="form-control input-goldbrand" name="type">
                                    <option value="">- Choose Single / Double -</option>
                                    <option value="SINGEL GLASS">SINGEL GLASS</option>
                                    <option value="DOUBLE GLASS">DOUBLE GLASS</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2"
                                    style="height: 40px" id="search_btn">Search</button>
                            </div>
                        </div>
                        <input type="hidden" name="bookingId" id="date_bookingId" value="{{ $booking->id }}">
                    </form>

                    @if (!request()->filled('start') && !request()->filled('end'))
                        <div>
                            <h4 class="mt-5 col-xs-12 text-center">Silahkan Pilih Tanggal Terlebih Dahulu</h4>
                        </div>
                    @elseif ($bus->isNotEmpty())
                        <div id="wizard-property-listing" class="bs-stepper vertical mt-2 linear">
                            <div class="bs-stepper-content">
                                <div id="personal-details"
                                    class="content active dstepper-block fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row g-3 bus-items" style="zoom: 0.8">
                                        <div class="col-12">
                                            <div class="row g-3">
                                                @foreach ($bus as $data)
                                                    <div class="col-sm-2 mb-md-0 mb-2">
                                                        <div class="form-check custom-option custom-option-icon">
                                                            <label class="form-check-label custom-option-content"
                                                                for="{{ $data->id }}">
                                                                <span class="custom-option-body">
                                                                    <i class="bx bx-bus"></i>
                                                                    <span
                                                                        class="custom-option-title">{{ $data->nobody }}</span>
                                                                    <small>{{ $data->nopolisi }}</small>
                                                                </span>
                                                                <input name="armada_id" class="form-check-input"
                                                                    type="checkbox" value="{{ $data->id }}"
                                                                    id="{{ $data->id }}" disabled>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($allBusesFull)
                        <div class="text-center">
                            <h4 class="mt-5 col-xs-12 text-center">Semua bus telah terisi penuh untuk tanggal yang dipilih.</h4>
                        </div>
                    @endif
                    <div class="col-xs-12 mt-4">
                        <h4>
                            Detail Bus
                        </h4>
                        <hr>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bus</th>
                                    <th>Supir</th>
                                    <th>Kondektur</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($booking->details as $detail)
                                    <tr>
                                        <td>{{ $detail->armadas->nobody }}</td>
                                        <td>{{ $detail->pengemudis ? $detail->pengemudis->nopengemudi : '' }} -
                                            {{ $detail->pengemudis ? $detail->pengemudis->users->name : '' }}</td>
                                        <td>{{ $detail->Kondektur_id }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary launch-modal"
                                                data-bs-toggle="modal" data-bs-target="#basicModal"
                                                data-supir="{{ $detail->supir_id }}"
                                                data-bus="{{ $detail->armadas->nobody }}"
                                                data-kondektur="{{ $detail->Kondektur_id }}"
                                                data-booking-id="{{ $detail->id }}">
                                                Edit Bus
                                            </button>
                                            <input type="hidden" name="bookingId" id="bookingId"
                                                value="{{ $detail->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form id="date_reservation">
                            <div class="text-center mt-4">
                                <button class="btn btn-primary" id="saving">Save Reservation</button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <!-- Modal -->
                            <div class="modal fade" id="basicModal" tabindex="-1" style="display: none;"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form id="form">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Edit Supir Dan Kondektur
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="personal-details"
                                                    class="content active dstepper-block fv-plugins-bootstrap5 fv-plugins-framework">
                                                    <div class="row g-3 bus-items" style="zoom: 0.8">
                                                        <div class="col-12">
                                                            <div class="row g-3">
                                                                @foreach ($bus as $data)
                                                                    <div class="col-sm-2 mb-md-0 mb-2">
                                                                        <div
                                                                            class="form-check custom-option custom-option-icon">
                                                                            <label
                                                                                class="form-check-label custom-option-content"
                                                                                for="bus_{{ $data->id }}">
                                                                                <span class="custom-option-body">
                                                                                    <i class="bx bx-bus"></i>
                                                                                    <span
                                                                                        class="custom-option-title">{{ $data->nobody }}</span>
                                                                                    <small>{{ $data->nopolisi }}</small>
                                                                                </span>
                                                                                <input name="armada_id"
                                                                                    class="form-check-input"
                                                                                    type="radio"
                                                                                    value="{{ $data->id }}"
                                                                                    id="bus_{{ $data->id }}">
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save
                                                    changes</button>
                                            </div>
                                        </div>
                                        <!-- Hidden input for bookingId -->
                                        <input type="hidden" name="bookingId" id="bookingId">

                                        <!-- Hidden input for bus_id -->
                                        <input type="hidden" name="bus_id" id="bus_id">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.querySelectorAll('.launch-modal').forEach(item => {
                                item.addEventListener('click', event => {
                                    const supirId = item.getAttribute('data-supir');
                                    const bus = item.getAttribute('data-bus');
                                    const kondekturId = item.getAttribute('data-kondektur');

                                    document.getElementById('bus').value = bus;
                                    document.getElementById('supir_id').value = supirId;
                                    document.getElementById('kondektur_id').value = kondekturId;
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                // Existing script for bus update
                $('.launch-modal').click(function() {
                    var supirId = $(this).data('supir');
                    var bus = $(this).data('bus');
                    var kondekturId = $(this).data('kondektur');
                    var bookingId = $(this).data('booking-id');

                    // Set the hidden input values
                    $('#bus_id').val(bus);
                    $('#supir_id').val(supirId);
                    $('#kondektur_id').val(kondekturId);
                    $('#bookingId').val(bookingId);
                });

                $('#saveChangesBtn').click(function() {
                    var bookingId = $('#bookingId').val();
                    var supirId = $('#supir_id').val();
                    var kondekturId = $('#kondektur_id').val();
                    var busId = $('input[name="armada_id"]:checked').val();

                    if (!busId) {
                        alert("Please select a bus.");
                        return;
                    }

                    $.ajax({
                        url: '/booking/update-reservation',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            booking_id: bookingId,
                            supir_id: supirId,
                            kondektur_id: kondekturId,
                            bus_id: busId
                        },
                        success: function(response) {
                            alert("Data saved, page will be refreshed");
                            location.reload();
                        },
                        error: function(response) {
                            var response = response.responseJSON;
                            alert(response.error);
                        }
                    });
                });

                // New script for date update
                $('#saving').click(function() {
                    var bookingId = $('#date_bookingId').val();
                    var startDate = $('#start').val();
                    var endDate = $('#end').val();

                    $.ajax({
                        url: '/booking/update-date',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            booking_id: bookingId,
                            start: startDate,
                            end: endDate
                        },
                        success: function(response) {
                            alert("Date updated successfully, page will be refreshed");
                            location.reload();
                        },
                        error: function(response) {
                            var response = response.responseJSON;
                            alert(response.error);
                        }
                    });
                });
            });
        </script>
    @endsection
