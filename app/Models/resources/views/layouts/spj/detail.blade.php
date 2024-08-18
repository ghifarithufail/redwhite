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
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($detail as $data)
                        <tr>
                            <td>{{ $data->armadas ? $data->armadas->nobody : '' }}</td>
                            <td>{{ $data->pengemudi_id }}</td>
                            <td>{{ $data->kondektur_id }}</td>
                            <td class="text-center">
                                @if ($data->is_out == null)
                                    <form method="POST" action="{{ route('spj/keluar', $data->id) }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn rounded-pill btn-danger"
                                            fdprocessedid="c80zr4">SPJ KELUAR</button>
                                    </form>
                                @elseif($data->is_in == null)
                                    {{-- <form method="POST" action="{{ route('spj/print_in', $data->spjs->id) }}"
                                        style="display: inline;">
                                        <button type="submit" class="btn rounded-pill btn-success"
                                            fdprocessedid="c80zr4">SPJ Masuk</button>
                                    </form> --}}
                                    <a href="{{ route('spj/print_in', $data->spjs->id) }}">
                                        <button type="button" class="btn rounded-pill btn-success" fdprocessedid="c80zr4">SPJ Keluar</button>
                                    </a>
                                @else
                                    done
                                @endif
                            </td>
                            @if ($data->spjs && $data->is_in == null)
                            <td>
                                <a href="{{ route('spj/data', $data->spjs->id) }}">
                                    <button type="button" class="btn rounded-pill btn-warning" fdprocessedid="c80zr4">Biaya
                                        Lain</button>
                                </a>
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
