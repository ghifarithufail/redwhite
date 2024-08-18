@extends('main')
@section('content')

    <div class="card">
        <div class="card-body">
            <h5 class="card-header">Edit Pengemudi</h5>
            <hr class="my-0" />
            <div class="card-body">
                <form action="{{ route('pengemudi.update', $pengemudi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <input type="hidden" name="user_id" value="{{ $pengemudi->user_id }}">
                        <div class="mb-3 col-md-6">
                            <label for="nopengemudi" class="form-label">Nomor Pengemudi</label>
                            <input id="nopengemudi" name="nopengemudi" type="text" value="{{ $pengemudi->nopengemudi }}" class="form-control">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="rute_id" class="form-label">Rute</label>
                            <select class="select2 form-select" id="rute_id" name="rute_id" required>
                                <option value="">Pilih Rute</option>
                                @if(isset($rutes) && count($rutes) > 0)
                                    @foreach($rutes as $data)
                                    <option value="{{ $data->id }}"
                                        data-nama="{{ $data->kode_rute }}"
                                        data-pool="{{ $data->pools->nama_pool }}"
                                        {{ $pengemudi->rute_id == $data->id ? 'selected' : '' }}>
                                        {{ $data->kode_rute }} - {{ $data->nama_rute }} -{{ $data->pools->nama_pool }}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="pool_id">Pool</label>
                            <select id="pool_id" name="pool_id" class="select2 form-select" data-allow-clear="true">
                              <option value="">{{ __('Select Pool') }}</option>
                                @foreach($pools as $pool)
                                    <option value="{{ $pool->id }}" {{ $pengemudi->pool_id == $pool->id ? 'selected' : '' }}>
                                        {{ $pool->nama_pool }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ $pengemudi->tgl_masuk }}" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="tanggal_kp" class="form-label">Tanggal KP</label>
                            <input type="date" class="form-control" id="tanggal_kp" name="tanggal_kp" value="{{ $pengemudi->tanggal_kp }}" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nosim" class="form-label">Nomor SIM</label>
                            <input type="text" class="form-control" id="nosim" name="nosim" value="{{ $pengemudi->nosim }}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="jenis_sim">Jenis SIM</label>
                            <select id="jenis_sim" name="jenis_sim" class="select2 form-select" required>
                                <option value="">Select Status</option>
                                <option value="BI" {{ $pengemudi->jenis_sim == 'BI' ? 'selected' : '' }}>BI</option>
                                <option value="BI-Umum" {{ $pengemudi->jenis_sim == 'BI-Umum' ? 'selected' : '' }}>BI-Umum</option>
                                <option value="BII" {{ $pengemudi->jenis_sim == 'BII' ? 'selected' : '' }}>BII</option>
                                <option value="BII-Umum" {{ $pengemudi->jenis_sim == 'BII-Umum' ? 'selected' : '' }}>BII-Umum</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="tgl_sim" class="form-label">Tanggal SIM</label>
                            <input type="date" class="form-control" id="tgl_sim" name="tgl_sim" value="{{ $pengemudi->tgl_sim }}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nojamsostek" class="form-label">Nomor Jamsostek</label>
                            <input type="text" class="form-control" id="nojamsostek" name="nojamsostek" value="{{ $pengemudi->nojamsostek }}"/>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="select2 form-select" required>
                                <option value="[]">Select Status</option>
                                <option value="Active" {{ $pengemudi->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $pengemudi->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Disable" {{ $pengemudi->status == 'Disable' ? 'selected' : '' }}>Disable</option>
                            </select>
                            @if ($errors->has('status'))
                                <div class="text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="ket_pengemudi" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="ket_pengemudi" name="ket_pengemudi" value="{{ $pengemudi->ket_pengemudi }}"/>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary" name="action">Update</button>
                        <a href="{{ route('pengemudi.index') }}" class="btn btn-warning">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#rute_id').select2();
            $('#pool_id').select2();

            // $('#rute_id').change(function () {
            //     var selectedOption = $(this).children("option:selected");
            //     var ruteId = selectedOption.val(); // Get rute ID
            //     var poolId = selectedOption.data('pool'); // Access pool ID from data attribute

            //     if (ruteId) {
            //     // AJAX to retrieve pool name based on rute ID
            //     $.ajax({
            //         url: '/pools/' + ruteId, // Adjust URL based on your route definition
            //         type: 'GET',
            //         success: function (response) {
            //         if (response.nama_pool) { // Check if pool name exists in response
            //             $('#pool_id').val(response.pool_id); // Set pool dropdown value
            //             $('#nama_pool').val(response.nama_pool); // Update nama_pool input
            //         } else {
            //             // Handle case where no pool found for rute
            //             $('#pool_id').val(''); // Clear pool dropdown
            //             $('#nama_pool').val(''); // Clear nama_pool input
            //         }
            //         },
            //         error: function (xhr, status, error) {
            //         console.error('Error retrieving pool:', error); // Handle error
            //         }
            //     });

            //     } else {
            //     // Clear fields if no rute selected
            //     $('#pool_id').val('');
            //     $('#nama_pool').val('');
            //     }
            // });
        });
    </script>
@endsection
