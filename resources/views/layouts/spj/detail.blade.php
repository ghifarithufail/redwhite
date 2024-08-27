@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>
    <div class="card text-center">
        <h5 class="card-header">SPJ</h5>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Nama Bus</th>
                        <th>Pengemudi</th>
                        <th>Kondektur</th>
                        <th class="text-center">SPJ</th>
                        <th class="text-center">Biaya Lain</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($detail as $data)
                        <tr>
                            <td>{{ $data->armadas ? $data->armadas->nobody : '' }}</td>
                            <td>{{ $data->pengemudis ? $data->pengemudis->users->name : '-' }}</td>
                            <td>{{ $data->kondekturs ? $data->kondekturs->users->name : '-'}}</td>
                            <td class="text-center">
                                @if ($data->is_out == null && $data->supir_id != null && $data->kondektur_id != null)
                                    <form method="POST" action="{{ route('spj/keluar', $data->id) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn rounded-pill btn-danger"
                                            fdprocessedid="c80zr4">SPJ KELUAR</button>
                                    </form>
                                    
                                @elseif($data->supir_id == null || $data->kondektur_id == null)
                                    <button type="button" class="btn rounded-pill btn-warning" fdprocessedid="c80zr4" disabled>Supir Dan Kondektur Blm ditentukan Silahkan hubungi CSO</button>
                                
                                @elseif($data->is_out != null && $data->spjs->user_keluar == null)
                                <a href="{{ url('spj/print/out', $data->spjs->id) }}">
                                    <button type="button" class="btn rounded-pill btn-danger" fdprocessedid="c80zr4">SPJ
                                        KELUAR DETAIL</button>
                                </a>
                                
                                @elseif($data->is_in == null)
                                    <a href="{{ route('spj/print_in', $data->spjs->id) }}">
                                        <button type="button" class="btn rounded-pill btn-success" fdprocessedid="c80zr4">SPJ Masuk</button>
                                    </a>
                                @elseif($data->is_in != null && $data->spjs->user_masuk == null)
                                    <a href="{{ url('spj/print/in', $data->spjs->id) }}">
                                        <button type="button" class="btn rounded-pill btn-success" fdprocessedid="c80zr4">SPJ
                                            Masuk DETAIL</button>
                                    </a>
                                @else
                                    done
                                @endif
                            </td>
                            @if ($data->spjs && $data->spjs->user_masuk == null)
                            <td class="text-center">
                                <a href="{{ route('spj/data', $data->spjs->id) }}">
                                    <button type="button" class="btn rounded-pill btn-warning" fdprocessedid="c80zr4">Biaya
                                        Lain</button>
                                </a>
                            </td>
                            @else
                            <td class="text-center">
                                    <button type="button" class="btn rounded-pill btn-warning" fdprocessedid="c80zr4" disabled>Biaya
                                        Lain</button>
                            </td>
                            @endif

                        </tr>
                    @endforeach

                </tbody>
            </table>
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
