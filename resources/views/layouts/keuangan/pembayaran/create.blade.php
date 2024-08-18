@extends('main')
@section('content')
<div class="card-header" style="zoom: 0.8">
                <h4>Booking Detail</h4>
                <hr>
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('pembayaran.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="booking_id" class="form-label">Booking ID</label>
                        {{-- <input type="text" id="booking_id" name="booking_id" class="form-control" value="{{ $data->booking->id }}" disabled> --}}
                        {{-- <input type="hidden" name="booking_id" value="{{ $data->booking->id }}"> --}}
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="type_payment_id">Type Pembayaran</label>
                        <select id="type_payment_id" name="type_payment_id" class="select2 form-select" data-allow-clear="true">
                            <option value="">{{ __('Select Type') }}</option>
                            @foreach($pembayaran as $data)
                                <option value="{{ $data->id }}" {{ old('type_payment_id') == $data->id ? 'selected' : '' }}>
                                    {{ $data->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label for="price" class="form-label">Bayar Rp.</label>
                        <input type="number" id="price" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="type_id" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <option value="1">DP</option>
                            <option value="2">Pembayaran 2</option>
                            <option value="3">Lunas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Pembayaran</button>
                </form>

            </div>
        </div>
    </div>

@endsection
