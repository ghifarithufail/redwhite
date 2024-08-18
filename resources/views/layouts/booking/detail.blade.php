@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
            integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Select2 JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>Booking Detail {{ $booking->start_date }}</h4>
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
                        <h4>Detail Bus</h4>
                        <hr>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nomor Body</th>
                                    <th>Lokasi Jemput</th>
                                    <th>Biaya Jemput</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($booking->details as $detail)
                                    <tr>
                                        <td>{{ $detail->armadas->nobody }}</td>
                                        <td>{{ $detail ? $detail->jemput : '' }}</td>
                                        <td>{{ number_format($detail ? $detail->biaya_jemput : '') }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary launch-modal"
                                                data-bs-toggle="modal" data-bs-target="#basicModal"
                                                data-jemput="{{ $detail->jemput }}"
                                                data-bus="{{ $detail->armadas->nobody }}"
                                                data-biaya-jemput="{{ $detail->biaya_jemput }}"
                                                data-booking-id="{{ $detail->id }}">
                                                Lokasi Jemput
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
                                                <h5 class="modal-title" id="exampleModalLabel1">Penjemputan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="bus" class="form-label">Bus</label>
                                                        <input type="text" id="bus" class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col mb-0">
                                                        <label for="jemput" class="form-label">Lokasi Jemput</label>
                                                        <input type="text" name="jemput" id="jemput"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col mb-0">
                                                        <label for="biaya_jemput" class="form-label">Biaya Jemput</label>
                                                        <input type="number" name="biaya_jemput" id="biaya_jemput"
                                                            class="form-control">
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
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary" id="save">Simpan</button>
                            </div>
                        </div>
                        <script>
                            document.querySelectorAll('.launch-modal').forEach(item => {
                                item.addEventListener('click', event => {
                                    const jemput = item.getAttribute('data-jemput');
                                    const biayaJemput = item.getAttribute('data-biaya-jemput');
                                    const bookingId = item.getAttribute('data-booking-id');

                                    document.getElementById('jemput').value = jemput;
                                    document.getElementById('biaya_jemput').value = biayaJemput;
                                    document.getElementById('bookingId').value = bookingId;
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            document.getElementById("save").addEventListener("click", function() {
                // Tampilkan pesan toastr
                toastr.success('Booking Behasil');

                // Tunda navigasi ke halaman booking untuk memberi waktu pada pengguna melihat pesan toastr
                setTimeout(function() {
                    // Arahkan ke halaman booking
                    window.location.href = "/booking";
                }, 1400); // Tunda selama 2 detik (dalam milidetik)
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.launch-modal').click(function() {
                    var jemput = $(this).data('jemput');
                    var biayaJemput = $(this).data('biaya-jemput');
                    var bus = $(this).data('bus');
                    var bookingId = $(this).data('booking-id');

                    $('#bus').val(bus);
                    $('#jemput').val(jemput);
                    $('#biaya_jemput').val(biayaJemput);
                    $('#bookingId').val(bookingId);
                });

                $('#saveChangesBtn').click(function() {
                    var bookingId = $('#bookingId').val();
                    var jemput = $('#jemput').val();
                    var biayaJemput = $('#biaya_jemput').val();

                    $.ajax({
                        url: '/booking/store-detail',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: bookingId,
                            jemput: jemput,
                            biaya_jemput: biayaJemput,
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
