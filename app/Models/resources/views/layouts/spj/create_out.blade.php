@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>

    <div class="card text-center">
        <h5 class="card-header">SPJ KELUAR</h5>
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

                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->end_date)->format('Y-m-d') }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Armada :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="{{ $spj->booking_details->armadas->nobody }} - {{ $spj->booking_details->armadas->nopolisi }} "
                                    disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="@foreach ($spj->booking_details->bookings->tujuans() as $key => $item){{ $item->nama_tujuan }}@if (!$loop->last), @endif @endforeach"
                                disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-4">No SPJ:</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="{{ $spj->no_spj }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4">
                        SPJ KELUAR DETAIL
                    </h4>
                    <hr>
                    @if ($spj->km_keluar == null)
                        <form action="{{ route('spj/print_out/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="control-label col-sm-4">No SPJ:</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        value="{{ $spj->no_spj }}"
                                        disabled class="form-control" />
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Lokasi Jemput :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" class="form-control input-quantity" name="lokasi_jemput" required>
                                </div>
                            </div>

                            <input type="text" value="{{ $spj->id }}" name="spj_id" readonly
                                class="form-control" hidden/>

                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Jam Jemput :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="datetime-local" class="form-control input-quantity" name="jam_jemput"
                                        required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Km Keluar :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="number" class="form-control input-quantity" name="km_keluar" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Uang Jalan :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="number" class="form-control input-quantity" name="uang_jalan" required>
                                </div>
                            </div>
                            <div class="pt-5 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            </div>
                        </form>
                </div>
            @else
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-3">No SPJ :</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{ $spj->no_spj }}" disabled class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Lokasi Jemput :</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{ $spj->lokasi_jemput }}" disabled class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">Waktu Jemput</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" value="{{ $spj->jam_jemput }}" disabled class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Uang Jalan :</label>
                        <div class="col-sm-9">
                            <input type="text" value="Rp. {{ number_format($spj->uang_jalan) }} " disabled
                                class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3">KM Keluar :</label>
                        <div class="col-sm-9">
                            <input type="text" value="{{ $spj->km_keluar }}" disabled class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="pt-5 d-flex justify-content-center">
                    <a href="{{ route('spj/print', $spj->id) }}" target="_blank">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Print SPJ Keluar</button>
                    </a>
                </div>
                @endif
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
    </script>
@endsection
