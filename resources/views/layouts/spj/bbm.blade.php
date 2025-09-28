@extends('main')
@section('content')

    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>

    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    Harga BBm
                </h4>
                <hr>
                <form action="{{ route('update_harga_bbm') }}" method="POST" enctype="multipart/form-korcam">
                    @csrf
                    <div class="row g-6">
                        <div class="col-md-4">
                            <label class="form-label" for="multicol-username">Type</label>
                            <input type="text" class="form-control" placeholder="type" name="type" value="{{$harga_bbm->type}}" readonly>

                        </div>
                        <div class="col-md-4">
                            <div class="form-password-toggle">
                                <label class="form-label" for="supir_id">Harga</label>
                                <input type="text" class="form-control" placeholder="harga" name="harga" value="{{$harga_bbm->harga}}">
                            </div>
                        </div>
                    </div>
                    <div class="pt-6 mt-3">
                        <button type="submit" id="submitBtn" class="btn btn-primary me-3">Submit</button>
                        {{-- <a href="{{ url()->previous() }}" class="btn btn-warning">Back</a> --}}
                    </div>
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                // Inisialisasi Select2
                $('#supir_id, #kondektur_id, #armada_id').select2({
                    placeholder: "Pilih opsi",
                    allowClear: true
                });
            });
        </script>
    @endsection
