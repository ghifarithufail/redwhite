@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Select2 CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
            integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Select2 JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <style>
        .bus-items {
            height: 250px;
            overflow-y: auto;
            /* Menambahkan scrollbar jika konten melebihi ketinggian */
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Wizard examples /</span> Property Listing
</h4> --}}

        <!-- Property Listing Wizard -->
        <div class="card text-center">
            <h5 class="card-header">Bookings</h5>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <div class="card-header" style="zoom: 0.8">
                    <form>
                        <div class="form-group row">
                            <div class="col-sm-3 mt-2">
                                <input type="date" class="form-control" placeholder="NIK" name="start"
                                    value="{{ $request['start'] }}" id="start">
                            </div>
                            <div class="col-sm-3 mt-2">
                                <input type="date" class="form-control" placeholder="NIK" name="end"
                                    value="{{ $request['end'] }}" id="end">
                            </div>
                            <div class="col-sm-3 mt-2">
                                <select class="form-control input-goldbrand" name="type">
                                    <option value="">- Choose Single / Double -</option>
                                    <option value="SINGEL GLASS">SINGEL GLASS</option>
                                    <option value="DOUBLE GLASS">DOUBLE GLASS</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary rounded text-white mt-2 mr-2"
                                    style="height: 40px" id="search_btn">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                @if (!request()->filled('start') && !request()->filled('end'))
                    <div class="text-center">
                        <p>Silahkan Pilih Tanggal Terlebih Dahulu</p>
                    </div>
                @elseif ($bus->isNotEmpty())
                    <div id="wizard-property-listing" class="bs-stepper vertical mt-2 linear">
                        <div class="bs-stepper-content">
                            <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data"
                                id="booking_form">
                                @csrf
                                <div id="personal-details"
                                    class="content active dstepper-block fv-plugins-bootstrap5 fv-plugins-framework">
                                    <div class="row g-3 bus-items" style="zoom: 0.8">
                                        <div class="col-12">
                                            <div class="row g-3">
                                                @foreach ($bus as $data)
                                                    <div class="col-sm-2 mb-md-0 mb-2">
                                                        <div class="form-check custom-option custom-option-icon">
                                                            <label class="form-check-label custom-option-content"
                                                                for="{{ $data->id }}">
                                                                <span class="custom-option-body">
                                                                    <i class="bx bx-bus"></i>
                                                                    <span
                                                                        class="custom-option-title">{{ $data->nobody }}</span>
                                                                    <small>{{ $data->nopolisi }}</small>
                                                                </span>
                                                                <input name="bus_id[]" class="form-check-input"
                                                                    type="checkbox" value="{{ $data->id }}"
                                                                    id="{{ $data->id }}">
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-3">
                                        <div class="col-sm-6">
                                            <label class="form-label" for="date_start">Tanggal Awal</label>
                                            <input type="text" id="date_start"
                                                value="{{ \Carbon\Carbon::parse($request['start'])->format('j F Y') }}"
                                                name="date_start" class="form-control" placeholder="Month Day Year"
                                                disabled>

                                            <input type="date" id="date_start" value="{{ $request['start'] }}"
                                                name="date_start" class="form-control" placeholder="john.doe" hidden>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="date_end">Tanggal Akhir</label>
                                            <input type="text" id="date_end"
                                                value="{{ \Carbon\Carbon::parse($request['end'])->format('j F Y') }}"
                                                name="date_end" class="form-control" placeholder="john.doe" disabled>

                                            <input type="date" id="date_end" value="{{ $request['end'] }}"
                                                name="date_end" class="form-control" placeholder="john.doe" hidden>
                                        </div>
                                        <div class="col-sm-6 fv-plugins-icon-container">
                                            <label class="form-label" for="customer">Customer</label>
                                            <input type="text" id="customer" name="customer" class="form-control"
                                                value="{{ old('customer') }}">
                                        </div>
                                        <div class="col-sm-6 fv-plugins-icon-container">
                                            <label class="form-label" for="telephone">Telephone</label>
                                            <input type="text" id="telephone" name="telephone" class="form-control">
                                        </div>
                                        <div class="col-sm-6 ">
                                            <label class="form-label" for="tujuan_id">tujuan</label>
                                            <select class="form-select" id="tujuan_id"
                                                aria-label="Default select example" name="tujuan_id">
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="diskon">Diskon (%)</label>
                                            <input type="number" id="diskon" name="diskon" class="form-control">
                                        </div>
                                        <div class="col-sm-6 fv-plugins-icon-container">
                                            <label class="form-label" for="harga_std">Harga Booking</label>
                                            <input type="text" id="total_harga_std" name="harga_std"
                                                class="form-control" readonly>
                                        </div>
                                        <div class="col-sm-6 fv-plugins-icon-container">
                                            <label class="form-label" for="harga_std">Biaya Jemput</label>
                                            <input type="text" id="biaya_jemput" name="biaya_jemput"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="total_bus">Total Bus</label>
                                            <input type="number" id="total_bus" name="total_bus" class="form-control"
                                                readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label" for="grand_total">Total Biaya</label>
                                            <input type="text" id="grand_total" name="grand_total"
                                                class="form-control" readonly>
                                        </div>
                                        <div class="col-sm-6 fv-plugins-icon-container">
                                            <label class="form-label" for="keterangan">Keterangan</label>
                                            <input type="text" id="keterangan" name="keterangan"
                                                class="form-control">
                                        </div>
                                        <div class="pt-5 d-flex justify-content-end" id="button_container">
                                            <button type="button" class="btn btn-warning me-sm-3 me-1"
                                                id="calculate_btn">Hitung</button>
                                            <button type="submit" class="btn btn-primary me-sm-3 me-1"
                                                id="submit_btn">Submit</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                @elseif($allBusesFull)
                    <div class="text-center">
                        <p>Semua bus telah terisi penuh untuk tanggal yang dipilih.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        // Jika terdapat pesan error dari server, tampilkan pesan toastr
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            // Hide the Submit button when the page loads
            document.getElementById('submit_btn').style.display = 'none';

            // Event listener for the "Hitung" button
            document.getElementById('calculate_btn').addEventListener('click', function() {
                // Show the Submit button when the "Hitung" button is clicked
                document.getElementById('submit_btn').style.display = 'inline-block';

                // Call your calculation function here
                updateGrandTotal();
                formatHargaStd();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('input[name="bus_id[]"]');
            var totalBusInput = document.getElementById('total_bus');
            var hargaStdInput = document.getElementById('total_harga_std');
            var diskon = document.getElementById('diskon');
            var grandTotalInput = document.getElementById('grand_total');

            // Event listener untuk perubahan pada checkbox bus
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total
                });
            });

            diskon.addEventListener('change', function() {
                if (diskon.value === '') {
                    diskon.value = 0; // Set nilai diskon menjadi 0 jika kosong
                }
                updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total
            });

            // Event listener untuk perubahan pada kolom diskon
            $('#calculate_btn').click(function() {
                updateGrandTotal(); // Panggil fungsi untuk memperbarui grand total
            });

            function updateGrandTotal() {
                var checkedCheckboxes = document.querySelectorAll('input[name="bus_id[]"]:checked');
                totalBusInput.value = checkedCheckboxes.length;

                var hargaStd = parseFloat(hargaStdInput.value.replace(/,/g, ''));
                var totalBus = parseInt(totalBusInput.value);
                var totalDiskon = parseFloat(diskon.value.replace(/,/g, ''));

                // Ensure values are valid numbers
                if (isNaN(hargaStd)) {
                    hargaStd = 0;
                }
                if (isNaN(totalBus)) {
                    totalBus = 0;
                }
                if (isNaN(totalDiskon)) {
                    totalDiskon = 0;
                }

                // Ensure the discount is not more than the total price
                var grandTotal = (hargaStd * totalBus) - totalDiskon;

                // Update the grand total input value
                grandTotalInput.value = numberWithCommas(grandTotal.toFixed(0));
            }

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        });

        $(document).ready(function() {
            $('#tujuan_id').select2({
                placeholder: 'Pilih Tujuan',
                allowClear: true,
                ajax: {
                    url: "{{ route('getTujuan') }}",
                    type: "post",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama_tujuan + ' - ' + item.pemakaian + ' - ' +
                                        item.type_bus
                                }
                            })
                        };
                    },
                },
            }).on('change', function(e) {
                var tujuanId = $(this).val(); // Ambil tujuan_id yang dipilih
                // Kirim permintaan AJAX untuk mendapatkan jumlah harga_std berdasarkan tujuan yang dipilih
                $.ajax({
                    url: "{{ route('getTotalHargaStd') }}",
                    type: "post",
                    data: {
                        tujuan_id: tujuanId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        // Perbarui nilai tampilan dengan total harga_std yang diterima dari respons AJAX
                        $('#total_harga_std').val(numberWithCommas(response.total_harga_std));

                        // Calculate grand total
                        var hargaStd = parseFloat(response.total_harga_std.replace(/,/g, ''));
                        var totalBus = parseInt($('#total_bus').val());
                        var totalDiskon = parseFloat($('#diskon').val().replace(/,/g, ''));
                        if (isNaN(totalDiskon)) {
                            totalDiskon = 0; // Atur nilai diskon menjadi 0 jika tidak valid
                        }
                        // var diskonAmount = hargaStd - totalDiskon; // Calculate diskon amount
                        $('#grand_total').val(numberWithCommas((hargaStd * totalBus) -
                            totalDiskon));
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Format input fields on keyup event
            $('#diskon, #total_harga_std, #grand_total').on('keyup', function() {
                $(this).val($(this).val().replace(/,/g, ''));
            });

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Remove commas from input fields before form submission
            $('#booking_form').on('submit', function() {
                $('#diskon').val($('#diskon').val().replace(/,/g, ''));
                $('#total_harga_std').val($('#total_harga_std').val().replace(/,/g, ''));
                $('#grand_total').val($('#grand_total').val().replace(/,/g, ''));
            });
        });
    </script>

@endsection
