@extends('main')
@section('content')

<div class="card-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h5 class="card-header">Pengemudi</h5>
    <hr class="my-0" />
    <div class="card-body">
        <form action="{{ route('pengemudi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="user_id" class="form-label">User</label>
                    <select id="user_id" name="user_id" class="select2 form-select" data-allow-clear="false">
                        <option value="">Pilih User</option>
                        @foreach ($users->filter(function($user) {
                            return $user->hasRole('pengemudi') && !empty($user->biodata->nik);
                        }) as $data)
                            <option value="{{ $data->id }}" {{ old('user_id') == $data->id ? 'selected' : '' }}>
                                {{ $data->id }} - {{ $data->name }} - {{ $data->biodata->nik }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('user_id'))
                        <div class="text-danger">{{ $errors->first('user_id') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="nopengemudi" class="form-label">Nomor Pengemudi</label>
                    <input id="nopengemudi" name="nopengemudi" type="text" value="{{ \App\Models\Hrd\Pengemudi::generateNopengemudi() }}" class="form-control">
                    @error('nopengemudi')
                        <div class="toast-header">
                            <div class="text-danger">{{ $message }}</div>
                        </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="rute_id">Rute</label>
                    <select id="rute_id" name="rute_id" class="select2 form-select">
                        <option value="">{{ __('Select Rute') }}</option>
                        @foreach($rutes as $rute)
                            <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                                {{ $rute->nama_rute }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('rute_id'))
                        <div class="text-danger">{{ $errors->first('rute_id') }}</div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="pool_id">Pool</label>
                    <select id="pool_id" name="pool_id" class="form-select select2" data-allow-clear="true">
                        <option value="">{{ __('Select Pool') }}</option>
                        @foreach($pools as $pool)
                            <option value="{{ $pool->id }}" {{ old('pool_id') == $pool->id ? 'selected' : '' }}>
                                {{ $pool->nama_pool }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('pool_id'))
                        <div class="text-danger">{{ $errors->first('pool_id') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" value="{{ old('tgl_masuk') }}" />
                    @if ($errors->has('tgl_masuk'))
                        <div class="text-danger">{{ $errors->first('tgl_masuk') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="tanggal_kp" class="form-label">Tanggal KP</label>
                    <input type="date" class="form-control" id="tanggal_kp" name="tanggal_kp" value="{{ old('tanggal_kp') }}" />
                    @if ($errors->has('tanggal_kp'))
                        <div class="text-danger">{{ $errors->first('tanggal_kp') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="nosim" class="form-label">Nomor SIM</label>
                    <input type="text" class="form-control" id="nosim" name="nosim" value="{{ old('nosim') }}"/>
                    @if ($errors->has('nosim'))
                        <div class="text-danger">{{ $errors->first('nosim') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label" for="jenis_sim">Jenis SIM</label>
                    <select id="jenis_sim" name="jenis_sim" class="select2 form-select" required>
                        <option value="">Select Jenis SIM</option>
                        <option value="BI" {{ old('jenis_sim') == 'BI' ? 'selected' : '' }}>BI</option>
                        <option value="BI-Umum" {{ old('jenis_sim') == 'BI-Umum' ? 'selected' : '' }}>BI-Umum</option>
                        <option value="BII" {{ old('jenis_sim') == 'BII' ? 'selected' : '' }}>BII</option>
                        <option value="BII-Umum" {{ old('jenis_sim') == 'BII-Umum' ? 'selected' : '' }}>BII-Umum</option>
                    </select>
                    @if ($errors->has('jenis_sim'))
                        <div class="text-danger">{{ $errors->first('jenis_sim') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="tgl_sim" class="form-label">Tanggal SIM</label>
                    <input type="date" class="form-control" id="tgl_sim" name="tgl_sim" value="{{ old('tgl_sim') }}"/>
                    @if ($errors->has('tgl_sim'))
                        <div class="text-danger">{{ $errors->first('tgl_sim') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="nojamsostek" class="form-label">Nomor Jamsostek</label>
                    <input type="text" class="form-control" id="nojamsostek" name="nojamsostek" value="{{ old('nojamsostek') }}"/>
                    @if ($errors->has('nojamsostek'))
                        <div class="text-danger">{{ $errors->first('nojamsostek') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="select2 form-select" required>
                        <option value="[]">Select Status</option>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Disable" {{ old('status') == 'Disable' ? 'selected' : '' }}>Disable</option>
                    </select>
                    @if ($errors->has('status'))
                        <div class="text-danger">{{ $errors->first('status') }}</div>
                    @endif
                </div>

                <div class="mb-3 col-md-6">
                    <label for="ket_pengemudi" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" id="ket_pengemudi" name="ket_pengemudi" value="{{ old('ket_pengemudi') }}"/>
                    @if ($errors->has('ket_pengemudi'))
                        <div class="text-danger">{{ $errors->first('ket_pengemudi') }}</div>
                    @endif
                </div>

                <div class="mt-2">
                    <button type="submit" class="btn btn-primary" name="action">Submit</button>
                    <a href="{{ route('pengemudi.index') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </form>
    </div>
        <!-- /Account -->
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        // Jika terdapat pesan error dari server, tampilkan pesan toastr
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        $(document).ready(function () {
            $('#rute_id').select2(); // Initialize Select2 on the dropdown

            $('#rute_id').change(function () {
                var selectedOption = $(this).children("option:selected");
                var id = selectedOption.data('pool'); // Access pool ID

                if (id) {
                    // Use AJAX to retrieve pool name based on pool ID
                    $.ajax({
                        url: '/get-pool-name/' + id, // Adjust URL according to your route
                        type: 'GET',
                        success: function (response) {
                            $('#nama_pool').val(response.nama_pool); // Fill the pool name into the input field
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    // If no pool ID is available, clear the input field
                    $('#nama_pool').val('');
                }
            });

            // Trigger event 'change' for autofill on page load
            $('#rute_id').trigger('input');
        });
    </script>

@endsection
