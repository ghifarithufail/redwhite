<div class="card mt-4">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover" style="zoom: 0.75">
            <thead>
                <tr>
                    <th>No SPJ</th>
                    <th>No Body</th>
                    <th>No Polisi</th>
                    <th>No Booking</th>
                    <th>Nama Customer</th>
                    <th>Tujuan</th>
                    <th>Tanggal SPJ Keluar</th>
                    <th>Tanggal Berangkat</th>
                    <th>Tanggal Pulang</th>
                    <th>No Induk</th>
                    <th>Pengemudi</th>
                    <th>No Induk</th>
                    <th>Kondektur</th>
                    <th>Uang Kas / Uang Jalan</th>
                    <th>Bop</th>
                    <th>Sisa Uang Jalan</th>
                    <th>Ops</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($spj as $data)
                @php
                    $bop = $data->bbm + $data->uang_makan + $data->parkir + $data->tol + $data->biaya_lain;
                    $sisa_uang_jalan = $data->uang_jalan - $bop
                @endphp
                    <tr>
                        <td>{{ $data->no_spj }}</td>
                        <td>{{ $data->booking_details->armadas->nobody }}</td>
                        <td>{{ $data->booking_details->armadas->nopolisi }}</td>
                        <td>{{ $data->booking_details->bookings->no_booking }}</td>
                        <td>{{ $data->booking_details->bookings->customer }}</td>
                        <td>{{ $data->booking_details->bookings->tujuan ? $data->booking_details->bookings->tujuan->nama_tujuan : '-' }}</td>
                        <td>{{ $data->date_keluar }}</td>
                        <td>{{ $data->booking_details->bookings->date_start }}</td>
                        <td>{{ $data->booking_details->bookings->date_end }}</td>
                        <td>{{ $data->booking_details->pengemudis ? $data->booking_details->pengemudis->nopengemudi : '-' }}</td>
                        <td>{{ $data->booking_details->pengemudis ? $data->booking_details->pengemudis->users->name : '-' }}</td>
                        <td>{{ $data->booking_details->kondekturs ? $data->booking_details->kondekturs->nokondektur : '-' }}</td>
                        <td>{{ $data->booking_details->kondekturs ? $data->booking_details->kondekturs->users->name : '-' }}</td>
                        <td>{{ $data->uang_jalan }}</td>
                        <td>{{ $bop }}</td>
                        <td>{{ $sisa_uang_jalan }}</td>
                        <td>{{ $data->booking_details->bookings->users ? $data->booking_details->bookings->users->name : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
