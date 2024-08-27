@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>
    <div class="card text-center">
        <h5 class="card-header">SPJ MASUK</h5>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    Booking Detail
                </h4>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">

                        <div class="form-group">
                            <label class="control-label col-sm-3">Name :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $spj->booking_details->bookings->customer }}" disabled
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Start Date :</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->start_date)->format('Y-m-d') }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Armada :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="{{ $spj->booking_details->armadas ? $spj->booking_details->armadas->merk : '-' }} - {{ $spj->booking_details->armadas ? $spj->booking_details->armadas->nopolisi : '-' }}"
                                    disabled class="form-control" />

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->end_date)->format('Y-m-d') }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $spj->booking_details->bookings->tujuan_id }}" disabled
                                    class="form-control" />
                            </div>
                        </div> --}}
                    </div>

                    <h4 class="mt-4">
                        SPJ MASUK DETAIL
                    </h4>
                    <hr>
                    @if ($spj->km_masuk == null)
                        <form action="{{ route('spj/print_in/store') }}" method="POST" enctype="multipart/form-data"
                            id="spj_in">
                            @csrf
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">KM Masuk :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="number" class="form-control input-quantity" name="km_masuk" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">BBM :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" class="form-control input-quantity" name="bbm" id="bbm"
                                        required>
                                </div>
                            </div>
                            <input type="text" value="{{ $spj->id }}" name="spj_id" readonly class="form-control"
                                hidden />

                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Uang Makan :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" class="form-control input-quantity" name="uang_makan"
                                        id="uang_makan" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Parkir :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" class="form-control input-quantity" name="parkir" id="parkir"
                                        required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Tol :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" class="form-control input-quantity" name="tol" id="tol"
                                        required>
                                </div>
                            </div>
                            <div class="pt-5 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            </div>
                        </form>
                    @else
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-3">No SPJ :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="{{ $spj->no_spj }}" disabled class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">KM Masuk</label>
                                <div class="col-sm-9">
                                    <input type="text" value="{{ $spj->km_masuk }}" disabled class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">BBM :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp. {{ number_format($spj->bbm) }}" disabled
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">Uang Makan</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp. {{ number_format($spj->uang_makan) }}" disabled
                                        class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-sm-4">Parkir :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp. {{ number_format($spj->parkir) }} " disabled
                                        class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">Tol :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp. {{ number_format($spj->tol) }}" disabled
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Biaya Lain :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp .{{ number_format($spj->biaya_lain) }}" disabled
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Keterangan :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="{{ $spj->keterangan_spj }}" disabled
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="pt-5 d-flex justify-content-center">
                            <form method="POST" action="{{ route('spj/save_detail_in', $spj->id) }}" target="_blank"> 
                                @csrf
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Print SPJ Masuk</button>
                            </form>
                            <a href="/spj/detail/{{ $spj->booking_details->booking_id }}" onclick="return confirm('Apakah Anda yakin bahwa SPJ Masuk ini telah selesai?')">
                                <button type="submit" class="btn btn-success me-sm-3 me-1">SPJ Masuk SELESAI</button>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Jika terdapat pesan sukses dari server, tampilkan pesan toastr
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        // Jika terdapat pesan error dari server, tampilkan pesan toastr
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        $(document).ready(function() {
            function formatNumber(value) {
                return value.replace(/\D/g, "") // Remove non-digit characters
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Add commas
            }

            function removeCommas(value) {
                return value.replace(/,/g, ''); // Remove commas
            }

            function setupInputFormatting(inputId) {
                $('#' + inputId).on('input', function() {
                    var input = $(this);
                    var value = input.val();
                    input.val(formatNumber(value));
                });
            }

            setupInputFormatting('bbm');
            setupInputFormatting('uang_makan');
            setupInputFormatting('parkir');
            setupInputFormatting('tol');

            $('#spj_in').on('submit', function() {
                var fields = ['bbm', 'uang_makan', 'parkir', 'tol'];
                fields.forEach(function(fieldId) {
                    var input = $('#' + fieldId);
                    var value = input.val();
                    input.val(removeCommas(value));
                });
            });
        });
    </script>
@endsection
