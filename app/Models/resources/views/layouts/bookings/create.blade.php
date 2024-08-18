@extends('main')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .bus-selection-container {
        max-height: 200px;
        max-width: 100%;
        overflow-x: auto;
        overflow-y: auto;
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .bus-item {
        flex: 0 0 150px;
        padding: 15px;
        background: transparent;
        border-radius: 5px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s;
    }

    .bus-item[data-booked="true"] label {
        background-color: red;
    }

    .bus-item input[type="checkbox"] {
        display: none;
    }

    .bus-item input[type="checkbox"]:checked + label {
        background-color: red;
    }

    .bus-item label {
        cursor: pointer;
        color: rgb(0, 0, 0);
        display: block;
        padding: 5px;
    }

    .bus-item label:hover {
        background-color: #6e6e6e;
    }

    .row {
        margin-top: 50px;
    }
</style>

<div class="container">
    <h2>Create Booking</h2>
    <div class="col-12">
        {{-- <div class="card mb-4"> --}}
            <div class="card-body">
                <form method="GET" action="{{ route('bookings.create') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-12 mb-4">
                            <label for="date_start" class="form-label">Tanggal Berangkat</label>
                            <input type="date" class="form-control" name="start" value="{{ $request['start'] }}" id="start">
                        </div>
                        <div class="col-md-3 col-12 mb-4">
                            <label for="date_end" class="form-label">Tanggal Pulang</label>
                            <input type="date" class="form-control" name="end" value="{{ $request['end'] }}" id="end">
                        </div>
                        <div class="col-md-3 col-12 mb-4">
                            <label for="type_id" class="form-label">Type Armada</label>
                            <select id="type_id" name="type_id" class="form-select">
                                <option value="">Pilih Type Armada</option>
                                @foreach($typearmadas as $type)
                                <option value="{{ $type->id }}" {{ $request['type_id'] == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12 mb-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="bus-selection-container">
        @foreach($availableBuses as $bus)
            <div class="bus-item" id="bus{{ $bus->id }}">
                <input type="checkbox" id="busCheckbox{{ $bus->id }}" class="bus-checkbox"
                       name="armada_id[]" value="{{ $bus->id }}" {{ in_array($bus->id, old('armada_id', $selectedBusIds)) ? 'checked' : '' }}>
                <label for="busCheckbox{{ $bus->id }}">
                    <span>
                        <i class="fas fa-bus"></i>
                        <span>{{ $bus->nobody }}</span>
                        <span>{{ $bus->nopolisi }}</span>
                    </span>
                </label>
            </div>
        @endforeach
    </div>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="start">Tanggal Berangkat</label>
                            <input type="text" id="date_start_disabled"
                                value="{{ \Carbon\Carbon::parse($request['start'])->format('d-m-Y') }}"
                                name="start" class="form-control" disabled>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="end">Tanggal Pulang</label>
                            <input type="text" id="date_end_disabled"
                                value="{{ \Carbon\Carbon::parse($request['end'])->format('d-m-Y') }}"
                                name="end" class="form-control" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="no_booking">Nomor Booking</label>
                            <input type="text" name="bookingNumber" class="form-control" value="{{ $request['newBookingNumber'] }}" disabled>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="tujuan_id" class="form-label">Tujuan</label>
                            <select class="form-select" id="tujuan_id" name="tujuan_id" required>
                                <option value="">Pilih Tujuan</option>
                                @foreach($tujuans as $tujuan)
                                    <option value="{{ $tujuan->id }}"
                                            data-harga="{{ $tujuan->harga_std }}"
                                            data-pemakaian="{{ $tujuan->total_bus }}"
                                            {{ old('tujuan_id') == $tujuan->id ? 'selected' : '' }}>
                                        {{ $tujuan->nama_tujuan }}- {{ $tujuan->pemakaian }} - {{ $tujuan->typearmadas->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="customer">Nama Pelanggan</label>
                            <input type="text" name="customer" id="customer" class="form-control" value="{{ old('customer') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="telephone">Telepon</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="harga_std">Harga Standar</label>
                            <input type="text" name="harga_std" id="harga_std" class="form-control harga-input"
                            value="{{ old('harga_std') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="armada_id" class="form-label">Armada</label>
                            <select class="form-control select2" id="armada_id" name="armada_id[]" multiple="multiple" required>
                                @foreach($availableBuses as $armada)
                                    <option value="{{ $armada->id }}" {{ in_array($armada->id, old('armada_id', $selectedBusIds)) ? 'selected' : '' }}>
                                        {{ $armada->nobody }} - {{ $armada->nopolisi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('armada_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="total_bus">Total Bus</label>
                            <input type="text" name="total_bus" id="total_bus" class="form-control" value="{{ old('total_bus') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="diskon">Diskon</label>
                            <input type="number" name="diskon" id="diskon" class="form-control" value="{{ old('diskon') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="biaya_jemput">Biaya Jemput</label>
                            <input type="number" name="biaya_jemput" id="biaya_jemput" class="form-control" value="{{ old('biaya_jemput') }}">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="grand_total">Grand Total</label>
                            <input type="text" name="grand_total" id="grand_total" class="form-control"
                            value="{{ old('grand_total') }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-warning">Kembali</a>
                        </div>
                    </div>
                </form>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
 $(document).ready(function () {
            $('#armada_id').select2();
            $('#tujuan_id').select2();

            function syncCheckboxToSelect(checkbox) {
                var armadaSelect = $('#armada_id');
                var value = checkbox.val();
                var optionExists = armadaSelect.find('option[value="' + value + '"]').length > 0;

                if (checkbox.is(':checked') && !optionExists) {
                    var optionText = checkbox.next('label').text().trim();
                    var newOption = new Option(optionText, value, true, true);
                    armadaSelect.append(newOption).trigger('change');
                } else if (!checkbox.is(':checked') && optionExists) {
                    armadaSelect.find('option[value="' + value + '"]').remove();
                    armadaSelect.trigger('change');
                }
            }

            $('.bus-checkbox').each(function () {
                syncCheckboxToSelect($(this));
            });

            $('.bus-checkbox').on('change', function () {
                syncCheckboxToSelect($(this));
                updateTotalBus();
            });

            $('#armada_id').on('change', function () {
                var selectedValues = $(this).val() || [];
                $('.bus-checkbox').each(function () {
                    $(this).prop('checked', selectedValues.includes($(this).val()));
                });
                updateTotalBus();
            });


            $('.harga-input, #diskon, #biaya_jemput, #grand_total').on('input', function() {
                var input = $(this).val().replace(/,/g, '');
                var formatted = formatNumber(input);
                $(this).val(formatted);
            });

            $('#tujuan_id').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var hargaStd = parseFloat(selectedOption.data('harga')) || 0;
                var formattedHarga = hargaStd.toLocaleString('id-ID');

                $('#harga_std').val(formattedHarga);
                updateGrandTotal();
            });

                function updateGrandTotal() {
                    var hargaStd = parseFloat($('#harga_std').val().replace(/\./g, '').replace(/,/g, '.')) || 0;
                    var totalBus = parseFloat($('#total_bus').val()) || 0;
                    var diskon = parseFloat($('#diskon').val()) || 0;
                    var biayaJemput = parseFloat($('#biaya_jemput').val()) || 0;

                    var grandTotal = (hargaStd * totalBus) - diskon + biayaJemput;
                    $('#grand_total').val(grandTotal.toLocaleString('id-ID'));
                }

           // Fungsi untuk memperbarui total bus berdasarkan checkbox yang dipilih
           function updateTotalBus() {
                var selectedArmadas = $('#armada_id').val() || [];
                $('#total_bus').val(selectedArmadas.length);
                updateGrandTotal(); // Perbarui grand total saat total bus diperbarui
            }

            // Fungsi untuk menghitung grand total
            function updateGrandTotal() {
                var hargaStd = parseFloat($('#harga_std').val().replace(/\./g, '').replace(',', '.')) || 0;
                var totalBus = parseFloat($('#total_bus').val()) || 0;
                var diskon = parseFloat($('#diskon').val()) || 0;
                var biayaJemput = parseFloat($('#biaya_jemput').val()) || 0;

                var grandTotal = (hargaStd * totalBus) - diskon + biayaJemput;
                $('#grand_total').val(grandTotal.toFixed(0)); // Set jumlah desimal yang diinginkan
            }

            // Event listener untuk input lain yang mempengaruhi grand total
            $('#harga_std, #total_bus, #diskon, #biaya_jemput').on('input', function () {
                updateGrandTotal();
            });

            $('#grand_total').on('input', function (event) {
                var inputValue = event.target.value;
                var numericValue = inputValue.replace(/,/g, '');
                var formattedValue = new Intl.NumberFormat('id-ID').format(numericValue);
                event.target.value = formattedValue;
            });

            // Validasi total bus dan armada yang dipilih
            $('#bookingForm').on('submit', function (event) {
                var totalBusInput = $('#total_bus');
                var totalBus = parseInt(totalBusInput.val().replace(/,/g, ''), 10);
                var selectedArmadas = $('#armada_id').val() || [];

                if (selectedArmadas.length !== totalBus) {
                    alert('Total bus harus sama dengan jumlah armada yang dipilih.');
                    event.preventDefault(); // Mencegah pengiriman form
                }
            });

    updateGrandTotal(); // Memanggil updateGrandTotal saat halaman dimuat

        });
</script>
@endsection
