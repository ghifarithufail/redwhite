@extends('main')
@section('content')

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>
    <div class="row mt-4">
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-dollar-circle'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Yang Harus Dibayar</b></p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{ number_format($booking->grand_total) ?? 0 }}</span>
                    </p>
                </div>
            </div>
        </div>
        @if ($booking->grand_total == $booking->total_payment)
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success"><i class='bx bx-dollar'></i></span>
                            </div>
                            <h4 class="ms-1 mb-0"></h4>
                        </div>
                        <p class="mb-1"><b>Yang sudah Dibayar</b></p>
                        <p class="mb-0">
                            <span class="fw-medium me-1">{{ number_format($booking->total_payment) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-dollar'></i></span>
                            </div>
                            <h4 class="ms-1 mb-0"></h4>
                        </div>
                        <p class="mb-1"><b>Yang sudah Dibayar</b></p>
                        <p class="mb-0">
                            <span class="fw-medium me-1">{{ number_format($booking->total_payment) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class='bx bxs-dollar-circle'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Selisih Pembayaran</b></p>
                    <p class="mb-0">
                        <span
                            class="fw-medium me-1">{{ number_format($booking->grand_total - $booking->total_payment ?? 0) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    @if ($booking->payment_status == '2')
                        Booking Detail  <span class="badge bg-label-danger me-1">Belum Lunas</span>
                    @else
                        Booking Detail  <span class="badge bg-label-success me-1">Lunas</span>
                    @endif
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
                        <div class="form-group">
                            <label class="control-label col-sm-3">No Booking :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->no_booking }}" disabled
                                    class="form-control" />
                            </div>
                        </div>

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
                            Detail Payment 
                        </h4>
                        <hr>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No pembayaran</th>
                                    <th>Tipe Pembayaran</th>
                                    <th>Biaya Yang dibayarkan</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($booking->payments as $detail)
                                    <tr>
                                        <td>{{ $detail->no_payment }}</td>
                                        <td>{{ $detail->type_payments->name}}</td>
                                        <td>{{ number_format($detail->price)}}</td>
                                        <td>
                                            <a href="{{ asset('uploads/' . $detail->image) }}" target="_blank">
                                                <img src="{{ asset('uploads/' . $detail->image) }}" alt="Payment Image" style="width: 100px; height: auto;">
                                            </a>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="text-center">
                            <a href="{{ route('booking/print', $booking->id) }}"
                                target="_blank" 
                                class="btn btn-danger">
                                Print
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    @endsection
