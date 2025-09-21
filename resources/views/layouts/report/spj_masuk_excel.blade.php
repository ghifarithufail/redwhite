
    {{-- Report Table --}}
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered align-middle" style="zoom: 0.9">
                    <thead class="table-light">
                        <tr>
                            <th>No SPJ</th>
                            <th>No Booking</th>
                            <th>Bus</th>
                            <th>Pengemudi</th>
                            <th>Kondektur</th>
                            <th>Uang Berangkat</th>
                            <th>BOP</th>
                            <th>Sisa Uang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spj as $item)
                            <tr>
                                <td>{{ $item->no_spj }}</td>
                                <td>{{ $item->booking_details->bookings->no_booking }}</td>
                                <td>{{ $item->booking_details->armadas->nobody }}</td>
                                <td>{{ $item->booking_details->pengemudis->users->name }}</td>
                                <td>{{ $item->booking_details->kondekturs ? $item->booking_details->kondekturs->users->name : '-' }}
                                </td>
                                <td>{{ $item->uang_jalan }}</td>
                                <td>{{ $item->pengeluaran }}</td>
                                <td>{{ $item->sisa_uang_jalan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
