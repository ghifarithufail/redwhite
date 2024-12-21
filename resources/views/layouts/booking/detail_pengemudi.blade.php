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
                    Input Supir Atau Kondektur
                </h4>
                <hr>
                <form action="{{ route('pengemudi/update', $detail->id) }}" method="POST"
                    enctype="multipart/form-korcam">
                    @csrf
                    <div class="row g-6">
                        <div class="col-md-4">
                            <label class="form-label" for="multicol-username">Bus</label>
                            <select class="form-control input-goldbrand" name="armada_id" id="armada_id">
                                <option value="{{ $detail->armada_id }}"> == {{ $detail->armadas->nobody }} - {{ $detail->armadas->type_armada->name }} == </option>
                                @foreach ($armada as $data)
                                    <option value="{{ $data->id }}">{{ $data->nobody }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-password-toggle">
                                <label class="form-label" for="supir_id">Pengemudi</label>
                                <div class="input-group input-group-merge">
                                    <select class="form-control select2" name="supir_id" id="supir_id">
                                        @if ($detail->pengemudis)
                                        <option value="{{ $detail->supir_id }}"> == {{ $detail->pengemudis->users->name }}
                                            == </option>
                                        @foreach ($pengemudi as $data)
                                            <option value="{{ $data->id }}">{{ $data->users->name }}</option>
                                        @endforeach
                                        @else
                                            <option value=""> == Silahkan Pilih Pengemudi == </option>
                                            @foreach ($pengemudi as $data)
                                                <option value="{{ $data->id }}">{{ $data->users->name }}</option>
                                            @endforeach
                                        @endif
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-password-toggle">
                                <label class="form-label" for="kondektur_id">Kondektur</label>
                                <div class="input-group input-group-merge">
                                    <select class="form-control select2" name="kondektur_id" id="kondektur_id">
                                        @if ($detail->kondekturs)
                                        <option value="{{ $detail->supir_id }}"> == {{ $detail->kondekturs->users->name }}
                                            == </option>
                                        @foreach ($kondektur as $data)
                                            <option value="{{ $data->id }}">{{ $data->users->name }}</option>
                                        @endforeach
                                    @else
                                        <option value=""> == Silahkan Pilih Kondektur ==</option>
                                        @foreach ($kondektur as $data)
                                            <option value="{{ $data->id }}">{{ $data->users->name }}</option>
                                        @endforeach
                                        
                                    @endif
                                    
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pt-6 mt-3">
                        <button type="submit" id="submitBtn" class="btn btn-primary me-3">Submit</button>
                        <a href="{{ url()->previous() }}" class="btn btn-warning">Back</a>
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