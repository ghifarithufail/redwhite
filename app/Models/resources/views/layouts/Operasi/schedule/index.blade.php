@extends('main')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-xxl">
    <div class="row">
    <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <form method="GET" action="{{ route('schedule') }}" class="row mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        {{-- <h5 class="m-0 me-2">Total Armada : {{ $totalArmadas }}</h5> --}}
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0"
                            type="button" id="orederStatistics" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                            <label for="type_id" class="form-label">Type</label>
                            <select id="type_id" name="type_id" class="select2 form-select" data-allow-clear="false">
                                <option value="All">All Types</option>
                                <option value="Single Glass">Single Glass</option>
                                <option value="Double Glass">Double Glass</option>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                        {{-- <h2 class="mb-2">{{ $totalBookingsThisMonth }}</h2> --}}
                        <span>Total Booking Bulan ini</span>
                        </div>
                        <div id="bookingStatisticsChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"
                            ><i class="bx bx-mobile-alt"></i
                            ></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                            <h6 class="mb-0">Electronic</h6>
                            <small class="text-muted">Mobile, Earbuds, TV</small>
                            </div>
                            <div class="user-progress">
                            <small class="fw-semibold">{{ $typearmadas}}</small>
                            </div>
                        </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-closet"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                            <h6 class="mb-0">Fashion</h6>
                            <small class="text-muted">T-shirt, Jeans, Shoes</small>
                            </div>
                            <div class="user-progress">
                            <small class="fw-semibold">23.8k</small>
                            </div>
                        </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                            <h6 class="mb-0">Decor</h6>
                            <small class="text-muted">Fine Art, Dining</small>
                            </div>
                            <div class="user-progress">
                            <small class="fw-semibold">849k</small>
                            </div>
                        </div>
                        </li>
                        <li class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"
                            ><i class="bx bx-football"></i
                            ></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                            <h6 class="mb-0">Sports</h6>
                            <small class="text-muted">Football, Cricket Kit</small>
                            </div>
                            <div class="user-progress">
                            <small class="fw-semibold">99</small>
                            </div>
                        </div>
                        </li>
                    </ul>
                    </div>
                </div>
            </form>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Grafik Jumlah Armada yang Dibooking Harian</h5>
                    <canvas id="dailyBookingsChart"></canvas>
                </div>
            </div>
        </div>


    <!-- Expense Overview -->
    <div class="col-md-6 col-lg-4 order-1 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
              <button
                type="button"
                class="nav-link active"
                role="tab"
                data-bs-toggle="tab"
                data-bs-target="#navs-tabs-line-card-income"
                aria-controls="navs-tabs-line-card-income"
                aria-selected="true"
              >
                Income
              </button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab">Expenses</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab">Profit</button>
            </li>
          </ul>
        </div>
        <div class="card-body px-0">
          <div class="tab-content p-0">
            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
              <div class="d-flex p-4 pt-3">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                </div>
                <div>
                  <small class="text-muted d-block">Total Balance</small>
                  <div class="d-flex align-items-center">
                    <h6 class="mb-0 me-1">$459.10</h6>
                    <small class="text-success fw-semibold">
                      <i class="bx bx-chevron-up"></i>
                      42.9%
                    </small>
                  </div>
                </div>
              </div>
              <div id="incomeChart"></div>
              <div class="d-flex justify-content-center pt-4 gap-2">
                <div class="flex-shrink-0">
                  <div id="expensesOfWeek"></div>
                </div>
                <div>
                  <p class="mb-n1 mt-1">Expenses This Week</p>
                  <small class="text-muted">$39 less than last week</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Expense Overview -->

    <!-- Transactions -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Transactions</h5>
          <div class="dropdown">
            <button
              class="btn p-0"
              type="button"
              id="transactionID"
              data-bs-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
              <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
              <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
              <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="p-0 m-0">
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img src="../assets/img/icons/unicons/paypal.png" alt="User" class="rounded" />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <small class="text-muted d-block mb-1">Paypal</small>
                  <h6 class="mb-0">Send money</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">+82.6</h6>
                  <span class="text-muted">USD</span>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img src="../assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <small class="text-muted d-block mb-1">Wallet</small>
                  <h6 class="mb-0">Mac'D</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">+270.69</h6>
                  <span class="text-muted">USD</span>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img src="../assets/img/icons/unicons/chart.png" alt="User" class="rounded" />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <small class="text-muted d-block mb-1">Transfer</small>
                  <h6 class="mb-0">Refund</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">+637.91</h6>
                  <span class="text-muted">USD</span>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img src="../assets/img/icons/unicons/cc-success.png" alt="User" class="rounded" />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <small class="text-muted d-block mb-1">Credit Card</small>
                  <h6 class="mb-0">Ordered Food</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">-838.71</h6>
                  <span class="text-muted">USD</span>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <img src="../assets/img/icons/unicons/wallet.png" alt="User" class="rounded" />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <small class="text-muted d-block mb-1">Wallet</small>
                  <h6 class="mb-0">Starbucks</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">+203.33</h6>
                  <span class="text-muted">USD</span>
                </div>
              </div>
            </li>
            <li class="d-flex">
              <div class="avatar flex-shrink-0 me-3">
                <img src="../assets/img/icons/unicons/cc-warning.png" alt="User" class="rounded" />
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <small class="text-muted d-block mb-1">Mastercard</small>
                  <h6 class="mb-0">Ordered Food</h6>
                </div>
                <div class="user-progress d-flex align-items-center gap-1">
                  <h6 class="mb-0">-92.45</h6>
                  <span class="text-muted">USD</span>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!--/ Transactions -->
  </div>
</div>
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Dashboard Schedule</h3>
            <form method="GET" action="{{ route('schedule') }}" class="row mb-4">
                <div class="col-md-4">
                    <label for="date_start" class="form-label">Tanggal Mulai:</label>
                    <input type="date" id="date_start" name="date_start" class="form-control" value="{{ request('date_start', '2024-05-01') }}">
                </div>
                <div class="col-md-4">
                    <label for="date_end" class="form-label">Tanggal Selesai:</label>
                    <input type="date" id="date_end" name="date_end" class="form-control" value="{{ request('date_end', '2024-05-31') }}">
                </div>
                <div class="col-md-4">
                    <label for="type_id" class="form-label">Tipe Armada:</label>
                    <select id="type_id" name="type_id" class="form-control">
                        <option value="">Semua</option>
                        @foreach($typearmadas as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-primary p-2">
                                    <i class="ti ti-currency-dollar ti-sm"></i>
                                </div>
                                <h6 class="mb-0">Pendapatan</h6>
                            </div>
                            <h4 class="my-2 pt-1">$545.69</h4>
                            <div class="progress w-75" style="height: 4px">
                                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-info p-2">
                                    <i class="ti ti-chart-pie-2 ti-sm"></i>
                                </div>
                                <h6 class="mb-0">Keuntungan</h6>
                            </div>
                            <h4 class="my-2 pt-1">$256.34</h4>
                            <div class="progress w-75" style="height: 4px">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-danger p-2">
                                    <i class="ti ti-brand-paypal ti-sm"></i>
                                </div>
                                <h6 class="mb-0">Pengeluaran</h6>
                            </div>
                            <h4 class="my-2 pt-1">$74.19</h4>
                            <div class="progress w-75" style="height: 4px">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earning Reports Table -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rincian Pendapatan</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No Body</th>
                                <th>Tipe</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($earnings as $earning)
                                <tr>
                                    <td>{{ $earning->nobody }}</td>
                                    <td>{{ $earning->name }}</td>
                                    <td>{{ number_format($earning->total_earnings, 2) }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                    <div>
                        {{-- {{ $earnings->appends(request()->input())->links() }} --}}
                    </div>
                </div>
            </div>

            <!-- Earning Reports Chart -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Grafik Pendapatan Mingguan</h5>
                    <canvas id="weeklyEarningReports"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Include Chart.js -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data for Order Statistics Chart
        var orderStatisticsOptions = {
            chart: { height: 165, width: 150, type: 'donut' },
            labels: ['Single Glass', 'Double Glass'],
            series: [85, 15, 50, 50],
            colors: ['#5A8DEE', '#39DA8A', '#FF5B5C', '#FFB547'],
            stroke: { width: 5, colors: ['#FFFFFF'] },
            dataLabels: {
                enabled: false,
                formatter: function (val) {
                    return parseInt(val) + '%';
                }
            },
            legend: { show: false },
            grid: { padding: { top: 0, bottom: 0, right: 15 } },
            states: {
                hover: { filter: { type: 'none' } },
                active: { filter: { type: 'none' } }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '1.5rem',
                                fontFamily: 'Public Sans',
                                color: '#000',
                                offsetY: -15,
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            name: { offsetY: 20, fontFamily: 'Public Sans' },
                            total: {
                                show: true,
                                fontSize: '0.8125rem',
                                color: '#000',
                                label: 'Weekly',
                                formatter: function (w) {
                                    return '38%';
                                }
                            }
                        }
                    }
                }
            }
        };

        // Render the Order Statistics Chart
        var orderStatisticsChart = new ApexCharts(document.querySelector("#orderStatisticsChart"), orderStatisticsOptions);
        orderStatisticsChart.render();

        // Data for Booking Statistics Chart
        var bookingStatisticsOptions = {
            chart: { type: 'donut', height: 300 },
            labels: ['Booked', 'Unbooked'],
            series: [{{ $bookedArmadasThisMonth }}, {{ $unbookedArmadasThisMonth }}],
            colors: ['#5A8DEE', '#FF5B5C'],
            stroke: { width: 5, colors: ['#D9E4EC'] },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + '%';
                }
            },
            legend: { show: true },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '0.75rem',
                                fontFamily: 'Public Sans',
                                color: '#000',
                                offsetY: -15,
                                formatter: function (val) {
                                    return val.toFixed(1) + '%';
                                }
                            },
                            // name: { offsetY: 20, fontFamily: 'Public Sans' },
                            // total: {
                            //     show: true,
                            //     fontSize: '0.8125rem',
                            //     color: '#800',
                            //     label: 'Total',
                            //     formatter: function (w) {
                            //         return '100%';
                            //     }
                            // }
                        }
                    }
                }
            }
        };

        // Render the Booking Statistics Chart
        var bookingStatisticsChart = new ApexCharts(document.querySelector("#bookingStatisticsChart"), bookingStatisticsOptions);
        bookingStatisticsChart.render();
    });

    // Mendengarkan perubahan pada dropdown
        $('#type_id').change(function(){
            var selectedType = $(this).val();

            // Menghapus konten formulir yang ada
            $('#dynamicForm').empty();

            // Menyesuaikan formulir berdasarkan pilihan yang dibuat
            if(selectedType === 'Single Glass') {
                // Jika dipilih Single Glass, tambahkan elemen formulir untuk Single Glass
                $('#dynamicForm').html('<input type="text" name="single_glass_field" class="form-control" placeholder="Single Glass Field">');
            } else if(selectedType === 'Double Glass') {
                // Jika dipilih Double Glass, tambahkan elemen formulir untuk Double Glass
                $('#dynamicForm').html('<input type="text" name="double_glass_field" class="form-control" placeholder="Double Glass Field">');
            } else {
                // Jika dipilih All atau tidak ada yang dipilih, tidak perlu ada perubahan pada formulir
            }
        });
</script>

@endsection
