@extends('main')
@section('content')

<div class="container">
    <h2>Create Booking</h2>
    <div class="col-12">
        {{-- <div class="card mb-4"> --}}
            <div class="card-body">
                <form>
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-12 mb-4">
                            <label for="date_start" class="form-label">Tanggal Berangkat</label>
                            {{-- <input type="date" class="form-control" name="start" id="start" value="{{ old('start', $booking->start) }}" > --}}
                        </div>
                        <div class="col-md-3 col-12 mb-4">
                            <label for="date_end" class="form-label">Tanggal Pulang</label>
                            {{-- <input type="date" class="form-control"  id="end" name="end" value="{{ old('end', $booking->end) }}"> --}}
                        </div>
                        {{-- <div class="col-md-3 col-12 mb-4">
                            <label for="type_id" class="form-label">Type Armada</label>
                            <select id="type_id" name="type_id" class="form-select">
                                <option value="">Pilih Type Armada</option>
                                @foreach($typearmadas as $type)
                                <option value="{{ $type->id }}" {{ $request['type_id'] == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class="col-md-3 col-12 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
