@extends('main')
@section('content')
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
                                <input type="text" value="{{ $spj->booking_details->bookings->customer }}" disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Start Date :</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->start_date)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($spj->booking_details->bookings->end_date)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Armada :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $spj->booking_details->armadas->merk }} - {{ $spj->booking_details->armadas->nopolisi }} " disabled class="form-control"/>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $spj->booking_details->bookings->tujuan_id }}" disabled class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 mt-4">
                        <h4>
                            BIAYA LAINYA
                        </h4>
                        <hr>
                        <form action="{{ route('spj/biaya_lain') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Biaya Lain :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="number" value="{{$spj->biaya_lain}}" class="form-control input-quantity" name="biaya_lain" required>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $spj->id }}" name="spj_id" readonly class="form-control" />
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">keterangan :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" value="{{$spj->keterangan_spj}}" class="form-control input-quantity" name="keterangan_spj" required>
                                </div>
                            </div>
                            <div class="pt-5 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
