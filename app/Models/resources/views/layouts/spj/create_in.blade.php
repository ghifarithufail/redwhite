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
                {{-- <h4>
                    DATA SPJ KELUAR
                </h4> --}}
                {{-- <hr> --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4">No SPJ:</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="{{ $spj->no_spj }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-5">Tanggal Berangkat :</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->start_date)->format('Y-m-d') }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Tanggal Pulang</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->end_date)->format('Y-m-d') }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Armada :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="{{ $spj->booking_details->armadas->nobody }} - {{ $spj->booking_details->armadas->nopolisi }} "
                                    disabled class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Pengemudi :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="{{ $spj->booking_details->pengemudis->nopengemudi ?? 'Belum di input' }} -
                                    {{ $spj->booking_details->pengemudis->users->name ?? '-' }}"
                                    disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Nama Pemesan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $spj->booking_details->bookings->customer }}" disabled
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="@foreach ($spj->booking_details->bookings->tujuans() as $key => $item){{ $item->nama_tujuan }}@if (!$loop->last), @endif @endforeach"
                                disabled class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Lokasi Jemput :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $spj->lokasi_jemput }}" disabled class="form-control" />
                            </div>
                        </div>

                    </div>

                    <h4 class="mt-4">
                        SPJ MASUK DETAIL
                    </h4>
                    <hr>
                    <div class="col-xs-6 col-sm-12">
                    @if ($spj->km_masuk == null)
                        <form action="{{ route('spj/print_in/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" value="{{ $spj->id }}" name="spj_id" hidden class="form-control" />
                            <div class="col-xs-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">KM Masuk :</label>
                                    <div class="col-sm-12 mt-2">
                                        <input type="number" class="form-control input-quantity" name="km_masuk" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">BBM :</label>
                                    <div class="col-sm-12 mt-2">
                                        <input type="number" class="form-control input-quantity" name="bbm" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Uang Makan :</label>
                                    <div class="col-sm-12 mt-2">
                                        <input type="number" class="form-control input-quantity" name="uang_makan" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">parkir :</label>
                                    <div class="col-sm-12 mt-2">
                                        <input type="number" class="form-control input-quantity" name="parkir" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3">tol :</label>
                                    <div class="col-sm-12 mt-2">
                                        <input type="number" class="form-control input-quantity" name="tol" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">KM Keluar :</label>
                                    <div class="col-sm-12 mt-2">
                                        <input type="number" class="form-control input-quantity" name="km_keluar" readonly>
                                    </div>
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
                                <label class="control-label col-sm-3">KM Masuk</label>
                                <div class="col-sm-9">
                                    <input type="text" value="{{ $spj->km_masuk }}" disabled class="form-control" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3">BBM :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp. {{ number_format($spj->bbm) }}" disabled class="form-control" />
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
                                    <input type="text" value="Rp. {{ number_format($spj->tol) }}" disabled class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Biaya Lain :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="Rp .{{ number_format($spj->biaya_lain) }}" disabled class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">Keterangan :</label>
                                <div class="col-sm-9">
                                    <input type="text" value="{{ $spj->keterangan_spj }}" disabled class="form-control" />
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
