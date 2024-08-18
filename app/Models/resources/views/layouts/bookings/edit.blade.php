@extends('main')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- <style>
    .bus-selection-container {
        max-height: 200px;   /* Atur tinggi maksimal sesuai kebutuhan */
        max-width: 100%;     /* Atur lebar maksimal sesuai kebutuhan */
        overflow-x: auto;    /* Membuat scroll horizontal */
        overflow-y: auto;    /* Membuat scroll vertikal */
        padding: 10px;
        display: flex;
        flex-wrap: wrap;     /* Membuat elemen berjalan di beberapa baris jika perlu */
        gap: 10px;
    }

    .bus-item {
        flex: 0 0 150px;     /* Atur ukuran item bus */
        padding: 15px;
        background: transparent;
        border-radius: 5px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s; /* Transisi untuk perubahan warna */
    }

    .bus-item[data-booked="true"] label {
        background-color: red; /* Warna merah ketika sudah terboking */
    }

    .bus-item input[type="checkbox"] {
        display: none; /* Menyembunyikan checkbox */
    }

    .bus-item input[type="checkbox"]:checked + label {
        background-color: red; /* Warna merah ketika dipilih */
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
        margin-top: 50px;    /* Jarak atas dari container form pemesanan */
    }
</style> --}}

<!-- Container untuk Pemilihan Bus -->
<style>
    .bus-selection-container {
        max-height: 200px;   /* Atur tinggi maksimal sesuai kebutuhan */
        max-width: 100%;     /* Atur lebar maksimal sesuai kebutuhan */
        overflow-x: auto;    /* Membuat scroll horizontal */
        overflow-y: auto;    /* Membuat scroll vertikal */
        padding: 10px;
        display: flex;
        flex-wrap: wrap;     /* Membuat elemen berjalan di beberapa baris jika perlu */
        gap: 10px;
    }

    .bus-item {
        flex: 0 0 150px;     /* Atur ukuran item bus */
        padding: 15px;
        background: transparent;
        border-radius: 5px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s; /* Transisi untuk perubahan warna */
    }

    .bus-item[data-booked="true"] label {
        background-color: red; /* Warna merah ketika sudah terboking */
    }

    .bus-item input[type="checkbox"] {
        display: none; /* Menyembunyikan checkbox */
    }

    .bus-item input[type="checkbox"]:checked + label {
        background-color: red; /* Warna merah ketika dipilih */
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
        margin-top: 50px;    /* Jarak atas dari container form pemesanan */
    }
</style>

    <div class="container">
        <h2>Edit Booking</h2>
        <!-- Container untuk Pemilihan Bus -->
        <div class="row">

        <!-- Daftar Bus -->
        <div class="bus-selection-container">
            @foreach($availableBuses as $bus)
            <div class="bus-item" id="bus{{ $bus->id }}">
                <input type="checkbox" id="busCheckbox{{ $bus->id }}" class="bus-checkbox" value="{{ $bus->id }}" {{ in_array($bus->id, $selectedBusIds) ? 'checked' : '' }}>
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
    </div>

    <!-- Container untuk Form Pemesanan -->
    <div class="row mt-8">
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">Edit Booking</h5>
            <hr class="my-0" />
            <div class="card-body">
                <form id="bookingForm" action="{{ route('bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                    <!-- Form lainnya -->
                    <div class="mb-3 col-md-6">
                        <label for="no_booking">Nomor Booking</label>
                        <input type="text" name="no_booking" id="no_booking" class="form-control" value="{{ old('no_booking', $booking->no_booking) }}" readonly>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="start">Tanggal Berangkat</label>
                        <input type="date" name="start" id="startDate" class="form-control" value="{{ old('start', $startDateFormatted) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="end">Tanggal Pulang</label>
                        <input type="date" name="end" id="endDate" class="form-control" value="{{ old('end', $endDateFormatted) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="customer">Nama Pelanggan</label>
                        <input type="text" name="customer" id="customer" class="form-control" value="{{ old('customer', $booking->customer) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="telephone">Telepon</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone', $booking->telephone) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="tujuan_id" class="form-label">Tujuan</label>
                        <select class="select2 form-select" id="tujuan_id" name="tujuan_id" required>
                            <option value="">Pilih Tujuan</option>
                            @if(isset($tujuans) && count($tujuans) > 0)
                                @foreach($tujuans as $tujuan)
                                    <option value="{{ $tujuan->id }}"
                                            data-nama="{{ $tujuan->nama_tujuan }}"
                                            data-pemakaian="{{ $tujuan->pemakaian }}"
                                            data-type="{{ $tujuan->type_bus }}"
                                            data-harga="{{ $tujuan->harga_std }}"
                                            {{ old('tujuan_id', $booking->tujuan_id) == $tujuan->id ? 'selected' : '' }}>
                                        {{ $tujuan->nama_tujuan }} - {{ $tujuan->type_bus }} - {{ $tujuan->pemakaian }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="harga_std">Harga Standar</label>
                        <input type="text" name="harga_std" id="harga_std" class="form-control"
                        value="{{ old('harga_std', number_format($booking->harga_std, 0, ',', '.')) }}" required>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="armada_id" class="form-label">Armada</label>
                        <select class="form-control select2" id="armada_id" name="armada_id[]" multiple="multiple" required>
                            @foreach($availableBuses as $armada)
                            <option value="{{ $armada->id }}" {{ in_array($armada->id, $selectedBusIds) ? 'selected' : '' }}>
                                {{ $armada->nobody }} - {{ $armada->nopolisi }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="total_bus">Total Bus</label>
                        <input type="number" name="total_bus" id="total_bus" class="form-control" value="{{ old('total_bus', $booking->total_bus) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="diskon">Diskon</label>
                        <input type="number" name="diskon" id="diskon" class="form-control"
                        value="{{ old('diskon', number_format($booking->diskon, 0, ',', '.')) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="biaya_jemput">Biaya Jemput</label>
                        <input type="number" name="biaya_jemput" id="biaya_jemput" class="form-control"
                        value="{{ old('biaya_jemput', number_format($booking->biaya_jemput, 0, ',', '.')) }}">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="grand_total">Grand Total</label>
                        <input type="number" name="grand_total" id="grand_total" class="form-control"
                        value="{{ old('grand_total', number_format($booking->grand_total, 0, ',', '.')) }}" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $booking->keterangan) }}</textarea>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary" name="action">Update</button>
                        <a href="{{ route('bookings.index') }}" class="btn btn-warning">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
 $(document).ready(function () {
            // Menginisialisasi Select2 pada elemen select
            $('#armada_id').select2();
            $('#tujuan_id').select2();

            // Fungsi untuk menambahkan atau menghapus opsi di select2 berdasarkan checkbox
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

            // Sinkronisasi awal saat halaman dimuat
            $('.bus-checkbox').each(function () {
                syncCheckboxToSelect($(this));
            });

            // Event listener untuk checkbox
            $('.bus-checkbox').on('change', function () {
                syncCheckboxToSelect($(this));
                updateTotalBus(); // Perbarui total bus saat checkbox berubah
            });

            // Event listener untuk select2 (saat armada diubah dari select2)
            $('#armada_id').on('change', function () {
                var selectedValues = $(this).val() || [];
                $('.bus-checkbox').each(function () {
                    $(this).prop('checked', selectedValues.includes($(this).val()));
                });
                updateTotalBus(); // Perbarui total bus saat armada_id berubah
            });

            // Event listener untuk perubahan tujuan
            $('#tujuan_id').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var hargaStd = parseFloat(selectedOption.data('harga')) || 0;

                $('#harga_std').val(hargaStd); // Menampilkan harga standar
                updateGrandTotal(); // Memperbarui total besar berdasarkan nilai yang baru
            });

            // Fungsi untuk memperbarui total bus berdasarkan checkbox yang dipilih
            function updateTotalBus() {
                var selectedArmadas = $('#armada_id').val() || [];
                $('#total_bus').val(selectedArmadas.length);
                updateGrandTotal(); // Perbarui grand total saat total bus diperbarui
            }

            // Fungsi untuk menghitung grand total
            function updateGrandTotal() {
                var hargaStd = parseFloat($('#harga_std').val().replace(/\./g, '').replace(',', '.')) || 0;
                var totalBus = parseInt($('#total_bus').val()) || 0;
                var diskon = parseFloat($('#diskon').val().replace(/\./g, '').replace(',', '.')) || 0;
                var biayaJemput = parseFloat($('#biaya_jemput').val().replace(/\./g, '').replace(',', '.')) || 0;

                var grandTotal = (hargaStd * totalBus) - diskon + biayaJemput;
                $('#grand_total').val(grandTotal).replace(',', '.'); // Set jumlah desimal yang diinginkan
            }

            // Event listener untuk input lain yang mempengaruhi grand total
            $('#harga_std, #total_bus, #diskon, #biaya_jemput').on('input', function () {
                updateGrandTotal();
            });

            // Validasi total bus dan armada yang dipilih
            $('#bookingForm').on('submit', function (event) {
                var totalBusInput = $('#total_bus');
                var totalBus = parseInt(totalBusInput.val(), 10);
                var selectedArmadas = $('#armada_id').val() || [];

                if (selectedArmadas.length !== totalBus) {
                    alert('Total bus harus sama dengan jumlah armada yang dipilih.');
                    event.preventDefault(); // Mencegah pengiriman form
                }
            });

            // Memanggil updateGrandTotal saat halaman dimuat
            updateGrandTotal();
        });

</script>


@endsection
