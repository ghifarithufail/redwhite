<!DOCTYPE html>
<html>
<head>
    <style>
        /* Custom CSS for PDF styling */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px; /* Ubah ukuran font sesuai kebutuhan Anda */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px; /* Ubah ukuran font untuk tabel */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tfoot tr th, tfoot tr td {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Laporan Booking</h1>
    <p><strong>Periode:</strong>
        {{ $start_date }} s/d {{ $end_date }}
    </p>

    <!-- Display Bookings -->
    <table>
        <thead>
            <tr>
                <th>No Booking</th>
                <th>Nama Customer</th>
                <th>Telephone</th>
                <th>Tanggal Pemakaian</th>
                <th>Durasi</th>
                <th>Tujuan</th>
                <th>Harga Dasar</th>
                <th>Total Bis</th>
                <th>Discount</th>
                <th>Biaya Jemput</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $data)
                <tr>
                    <td>{{ $data->no_booking }}</td>
                    <td>{{ $data->customer }}</td>
                    <td>{{ $data->telephone }}</td>
                    <td>{{ $data->formatted_date_range }}</td>
                    <td>{{ $data->duration_days }} Hari</td>
                    <td>
                        @if ($data->tujuan)
                            {{ $data->tujuan->nama_tujuan }}
                        @else
                            Tidak tersedia
                        @endif
                    </td>
                    <td style="text-align: right">{{ number_format($data->harga_std, 0, ',', '.') }}</td>
                    <td style="text-align: center">{{ $data->bookingDetails->count() }}</td>
                    <td style="text-align: right">{{ number_format($data->diskon, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($data->biaya_jemput, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($data->grand_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;">Total</td>
                <td style="text-align: right">{{ number_format($total_harga_dasar, 0, ',', '.') }}</td>
                <td style="text-align: center">{{ $total_buses }} </td>
                <td style="text-align: right">{{ number_format($total_discount, 0, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($total_biaya_jemput, 0, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($total_bayar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
