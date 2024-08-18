@extends('main')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>

    <div class="card text-center">
        <h5 class="card-header">Payments Bookings</h5>
    </div>

    <div class="row mt-4">

        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-dollar-circle'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Yang Harus Dibayar</b></p>
                    <p class="mb-0">
                        <span class="fw-medium me-1">{{ number_format($booking->grand_total) ?? 0 }}</span>
                    </p>
                </div>
            </div>
        </div>
        @if ($booking->grand_total == $booking->total_payment)
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success"><i class='bx bx-dollar'></i></span>
                            </div>
                            <h4 class="ms-1 mb-0"></h4>
                        </div>
                        <p class="mb-1"><b>Yang sudah Dibayar</b></p>
                        <p class="mb-0">
                            <span class="fw-medium me-1">{{ number_format($booking->total_payment) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-sm-6 col-lg-4 mb-4">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-dollar'></i></span>
                            </div>
                            <h4 class="ms-1 mb-0"></h4>
                        </div>
                        <p class="mb-1"><b>Yang sudah Dibayar</b></p>
                        <p class="mb-0">
                            <span class="fw-medium me-1">{{ number_format($booking->total_payment) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class='bx bxs-dollar-circle'></i></span>
                        </div>
                        <h4 class="ms-1 mb-0"></h4>
                    </div>
                    <p class="mb-1"><b>Selisih Pembayaran</b></p>
                    <p class="mb-0">
                        <span
                            class="fw-medium me-1">{{ number_format($booking->grand_total - $booking->total_payment ?? 0) }}</span>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="card-header" style="zoom: 0.8">
                <h4>
                    Booking Detail {{ $booking->no_booking }}
                </h4>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">

                        <div class="form-group">
                            <label class="control-label col-sm-3">Name :</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ $booking->customer }}" disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">Start Date :</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->start_date)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Tujuan :</label>
                            <div class="col-sm-9">
                                <input type="text"
                                    value="@foreach ($booking->tujuans() as $key => $item){{ $item->nama_tujuan }}@if (!$loop->last), @endif @endforeach"
                                    disabled class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3">End Date</label>
                            <div class="col-sm-9">
                                <input type="date"
                                    value="{{ Carbon\Carbon::parse($booking->end_date)->format('Y-m-d') }}" disabled
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 mt-4">
                        <h4>
                            Pembayaran Ke {{$payment_count}}
                        </h4>
                        <hr>
                        <form action="{{ route('payment/store') }}" method="POST" enctype="multipart/form-data"
                            id="paymentForm">
                            @csrf
                            <div class="form-group">
                                <label class="control-label col-sm-3">Tipe Pemabayaran :</label>
                                <div class="col-sm-12 mt-3">
                                    <select class="form-control input-goldbrand" name="type_payment_id" required>
                                        <option value="">- Choose Payment type -</option>
                                        @foreach ($type as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Payment Ke :</label>
                                <div class="col-sm-12 mt-2">
                                    <select class="form-control input-goldbrand" name="jmlh_bayar" required>
                                        <option value="">- Pilih Pemabyaran Ke -</option>
                                        @if($payment_count < 1)
                                            <option value="1"> Pembayara ke 1 </option>
                                        @endif
                                        @if($payment_count < 2)
                                            <option value="2"> Pembayara ke 2 </option>
                                        @endif
                                        @if($payment_count < 3)
                                            <option value="3"> Pembayara ke 3 </option>
                                        @endif
                                        @if($payment_count < 4)
                                            <option value="4"> Pelunasan </option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <input type="number" value="{{ $booking->id }}" name="booking_id"
                                class="form-control input-quantity" hidden required>

                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Price :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="text" class="form-control input-quantity" name="price"
                                        id="price" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="control-label col-sm-3">Image :</label>
                                <div class="col-sm-12 mt-2">
                                    <input type="file" class="form-control input-quantity" name="image">
                                </div>
                            </div>
                            <div class="pt-5 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
        
        $(document).ready(function() {
            function formatNumber(value) {
                return value.replace(/\D/g, "") // Remove non-digit characters
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Add commas
            }

            $('#price').on('input', function() {
                var input = $(this);
                var value = input.val();
                input.val(formatNumber(value));
            });

            $('#paymentForm').on('submit', function() {
                var priceInput = $('#price');
                var priceValue = priceInput.val().replace(/,/g, ''); // Remove commas
                priceInput.val(priceValue); // Set the input value without commas
            });
        });
    </script>
@endsection
