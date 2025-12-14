<table class="table table-hover table-bordered align-middle" style="zoom: 0.9">
    <thead class="table-light">
        <tr>
            <th>No Booking</th>
            <th>Tanggal</th>
            <th>jmlh SPJ</th>
            <th>Jmlh Hari</th>
            <th>Type Armada</th>
            <th>harga Standar</th>
            <th>Biaya Jemput</th>
            <th>Discount</th>
            <th>Biaya BBM</th>
            <th>Uang Makan</th>
            <th>Parkir</th>
            <th>Biaya Tol</th>
            <th>Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $item)
            <tr>
                <td>{{ $item['no_booking'] }}</td>
                <td>{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d F Y') }}</td>
                <td>{{ $item['jmlh_spj'] }}</td>
                <td>{{ $item['jmlhHari'] }}</td>
                <td>{{ $item['type_armada'] ?? '-' }}</td>
                <td>{{ $item['harga_std'] }}</td>
                <td>{{ $item['biaya_jemput'] }}</td>
                <td>{{ $item['diskon'] }}</td>
                <td>{{ $item['total_bbm'] }}</td>
                <td>{{ $item['total_uang_makan'] }}</td>
                <td>{{ $item['parkir'] }}</td>
                <td>{{ $item['tol'] }}</td>
                <td>{{ $item['pendapatan'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
