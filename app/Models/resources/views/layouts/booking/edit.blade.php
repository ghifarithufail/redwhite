@extends('main')
@section('content')

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>
    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    Booking Detail {{ $booking->start_date }}
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
                                    value="{{ Carbon\Carbon::parse($booking->date_start)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->date_end)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        {{-- <div class="form-group">
                            <label class="control-label col-sm-3">Total Penumpang :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->total_passanger }}" disabled
                                    class="form-control" />
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label class="control-label col-sm-4">Penjemputan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->lokasi_jemput }}" disabled class="form-control" />

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="@foreach ($booking->tujuans() as $key => $item){{ $item->nama_tujuan }}@if (!$loop->last), @endif @endforeach"
                                    disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 mt-4">
                        <h4>
                            Detail Bus
                        </h4>
                        <hr>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nomor Body</th>
                                    <th>Pengemudi</th>
                                    <th>Kondektur</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($booking->details as $detail)
                                    <tr>
                                        <td>{{ $detail->armadas->nobody }}</td>
                                        <td>{{ $detail->pengemudis ? $detail->pengemudis->nopengemudi : '' }} - {{ $detail->pengemudis ? $detail->pengemudis->users->name : '' }}</td>
                                        <td>{{ $detail->kondekturs ? $detail->kondekturs->nokondektur : '' }} - {{ $detail->kondekturs ? $detail->kondekturs->users->name : '' }}</td>
                                        {{-- <td>{{ $detail->Kondektur_id }}</td> --}}
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary launch-modal"
                                                data-bs-toggle="modal" data-bs-target="#basicModal"
                                                data-supir="{{ $detail->pengemudi_id }}"
                                                data-bus="{{ $detail->armadas->nobody }}"
                                                data-kondektur="{{ $detail->kondektur_id }}"
                                                data-booking-id="{{ $detail->id }}"
                                                data-armada-id="{{ $detail->armada_id }}">
                                                Input
                                            </button>
                                            <input type="hidden" name="bookingId" id="bookingId"
                                                value="{{ $detail->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <!-- Modal -->
                            <div class="modal fade" id="basicModal" tabindex="-1" style="display: none;"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form id="form">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel1">Edit Pengemudi Dan Kondektur
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="bus" class="form-label">Bus</label>
                                                        <input type="text" id="bus" class="form-control" disabled
                                                            placeholder="Enter Name">
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="pengemudi_id" class="form-label">Pengemudi</label>
                                                        {{-- <input type="number" name="supir_id" id="supir_id"
                                                            class="form-control"> --}}
                                                        <select class="form-select" id="pengemudi_id" name="pengemudi_id"
                                                            aria-label="Default select example" fdprocessedid="6aeghl">
                                                            <option value="" selected disabled>Silahkan pilih pengemudi</option>
                                                            @foreach ($pengemudi as $item)
                                                                <option value="{{ $item->id }}"> {{ $item->nopengemudi }} - {{ $item->users->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col mb-0">
                                                        <label for="kondektur_id" class="form-label">Kondektur</label>
                                                        <select class="form-select" id="kondektur_id" name="kondektur_id"
                                                            aria-label="Default select example" fdprocessedid="6aeghl">
                                                            <option value="" selected disabled>Silahkan pilih Kondektur</option>
                                                            @foreach ($kondektur as $item)
                                                                <option value="{{ $item->id }}"> {{ $item->nokondektur }} - {{ $item->users->name}}</option>
                                                            @endforeach
                                                        </select>
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
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.querySelectorAll('.launch-modal').forEach(item => {
                                item.addEventListener('click', event => {
                                    const pengemudiId = item.getAttribute('data-supir');
                                    const bus = item.getAttribute('data-bus');
                                    const kondekturId = item.getAttribute('data-kondektur');

                                    document.getElementById('bus').value = bus;
                                    document.getElementById('pengemudi_id').value = pengemudiId;
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
                $('.launch-modal').click(function() {
                    var pengemudiId = $(this).data('pengemudi');
                    var bus = $(this).data('bus');
                    var kondekturId = $(this).data('kondektur');
                    var bookingId = $(this).data('booking-id'); // Mengambil ID booking

                    $('#bus').val(bus);
                    $('#pengemudi_id').val(pengemudiId);
                    $('#kondektur_id').val(kondekturId);
                    $('#bookingId').val(bookingId); // Mengatur nilai ID booking di input tersembunyi
                });

                $('#saveChangesBtn').click(function() {
                    var bookingId = $('#bookingId').val();
                    var pengemudiId = $('#pengemudi_id').val();
                    var kondekturId = $('#kondektur_id').val();

                    $.ajax({
                        url: '/booking/update-data',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: bookingId,
                            pengemudi_id: pengemudiId,
                            kondektur_id: kondekturId,
                            booking_id: bookingId
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
                })
            });
        </script>
    @endsection
