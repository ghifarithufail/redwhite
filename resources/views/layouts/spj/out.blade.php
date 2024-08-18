<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="5qdZDOOsTk1xdv5nhwNrlwNB1H36bfOGJO0nkmo8">
    <title>spj</title>
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            font-size: 12px;
            font-family: calibri;
            background: #eee;
        }

        h1 {
            font-size: 1em;
            font-weight: 700;
        }

        .devider {
            border: 1px dashed #444;
        }

        #section {
            margin-bottom: 10px;
            text-align: left !important;
            padding: 10px 30px;
            font-size: 15px;
        }

        #end {
            page-break-after: always;
        }

        #receipt {
            background-color: white;
            width: 20cm;
            min-height: 14cm;
            font-size: 12px;
            margin: auto;
            padding: 5px;
            border: 1px solid #ddd;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
            /* line-height: 24px; */
            color: #555;
        }

        .box-logo {
            padding: 5px 20px;
            margin-bottom: 5px;
            height: 80px;
            border: 0px solid #f00;
            text-align: left;
        }

        .logo {
            width: 250px;
        }

        .floatLeft {
            float: left;
        }

        .floatRight {
            float: right;
        }

        .textRight {
            text-align: right;
        }

        .textCenter {
            text-align: center;
        }

        .titleLeft {
            padding-top: 3px;
        }

        .titleRight {
            margin-left: 20px;
            border: 0px solid #f0f;
            padding-top: 7px;
        }

        .titleMain {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .titleStore {
            font-size: 13px;
            text-align: left;
        }

        .title2 {
            margin-top: 10px;
            font-size: 13px;
        }

        .title3 {
            font-size: 14px;
        }

        .pr-200 {
            padding-right: 200px;
        }

        .bold-italic {
            font-weight: bold;
            /* Menetapkan teks menjadi tebal */
            font-style: italic;
            /* Menetapkan teks menjadi miring */
        }
    </style>
</head>

<body>
    <div id="receipt">
        <div id="section">
            <div class="row">
                <img src="{{ asset('img/redwhite 71x11.png') }}" alt="Logo" class="logo">
                <h4 class="bold-italic"> PT. PRIMAJASA PERDANARAYA<br>Jl. Moh. Toha No.1 (Ciputat-Parung)
                    Wates,<br>Pondok Cabe Telp. (021) 74703339</h4>
            </div>
            <center>
                <h2>SURAT PERINTAH JALAN</h2>

            </center>
            <hr>
            <center>
                <h3 style="text-decoration: underline;">NOMOR SPJ : {{ $spj->no_spj }}</h3>
            </center>
            <hr>
            {{-- <div>Surat ini adalah surat perintah jalan dengan detail sebagai berikut :</div> --}}
            <div style="padding: 10px; font-size: 16px;">
                <table>
                    <tr>
                        <td width="170">Pengemudi/Nomor Induk</td>
                        <td width="1%">:</td>
                        <td>
                            {{ $spj->booking_details ? $spj->booking_details->pengemudis->users->name :'-'}} /
                            {{ $spj->booking_details ? $spj->booking_details->pengemudis->nopengemudi :'-'}}
                            {{-- @if ($spj->bookingDetails && $spj->bookingDetails->isNotEmpty())
                                @foreach ($spj->bookingDetails as $detail)
                                    @if ($detail->pengemudi)
                                        {{ $detail->pengemudi->user ? $detail->pengemudi->user->name : 'User Not Found' }} / {{ $detail->pengemudi->nopengemudi }}
                                    @else
                                        Pengemudi Not Found
                                    @endif
                                @endforeach
                            @else
                                Booking Details Not Found
                            @endif --}}
                        </td>
                        {{-- <td>
                            @if ($spj->booking_details && $spj->booking_details->pengemudis && $spj->booking_details->pengemudis->users)
                                {{ $spj->booking_details->pengemudis->users->name }} / {{ $spj->booking_details->pengemudis->nopengemudi }}
                            @else
                                Data tidak tersedia
                            @endif
                        </td> --}}
                    </tr>
                    <tr>
                        <td width="170">No.Body / No.Polisi</td>
                        <td width="1%">:</td>
                        <td>{{ $spj->booking_details->armadas->nobody }} /
                            {{ $spj->booking_details->armadas->nopolisi }}
                        </td>
                    </tr>
                    <tr>
                        <td width="170">KM Keluar</td>
                        <td width="1%">:</td>
                        <td>
                            @if ($spj->km_masuk == null)
                                {{ $spj->km_keluar }} Km - KM Masuk :
                            @else
                                {{ $spj->km_keluar }} Km / {{ $spj->km_masuk }} Km
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="170">Biaya Jemput</td>
                        <td width="1%">:</td>
                        <td>{{ $spj->biaya_jemput }}</td>
                    </tr>
                    <tr>
                        <td width="170">Uang Jalan</td>
                        <td width="1%">:</td>
                        <td>Rp. {{ number_format($spj->uang_jalan) }}</td>
                    </tr>
                </table>
            </div>

            <div style="padding: 10px; font-size: 16px; margin-bottom:">
                <table width="100%">
                    <tr>
                        <td width="170">Nama Pemesan/Perusahaan</td>
                        <td width="1%">:</td>
                        <td>{{ $spj->booking_details->bookings->customer }}</td>
                    </tr>
                    <tr>
                        <td width="170">Alamat Pemesan</td>
                        <td width="1%">:</td>
                        {{-- <td>0{{ $spj->booking_details->bookings->telephone }}</td> --}}
                    </tr>
                    <tr>
                        <td width="170">Telephone</td>
                        <td width="1%">:</td>
                        <td>0{{ $spj->booking_details->bookings->telephone }} </td>
                    </tr>
                    <tr>
                        <td width="170">Hari/Tanggal Pemakaian</td>
                        <td width="1%">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($spj->booking_details->bookings->date_start)->locale('id')->translatedFormat('l, d F Y') }}
                            s/d
                            {{ \Carbon\Carbon::parse($spj->booking_details->bookings->date_end)->locale('id')->translatedFormat('l, d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td width="170">Tujuan</td>
                        <td width="1%">:</td>
                        <td>
                            @foreach ($spj->booking_details->bookings->tujuans() as $key => $item)
                                {{ $item->nama_tujuan }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td width="170">Lama Pemakaian</td>
                        <td width="1%">:</td>
                        <td>
                            @php
                                $dateStart = \Carbon\Carbon::parse($spj->booking_details->bookings->date_start);
                                $dateEnd = \Carbon\Carbon::parse($spj->booking_details->bookings->date_end);
                                $duration = $dateStart->diffInDays($dateEnd) + 1; // +1 jika ingin menghitung hari mulai
                            @endphp
                            {{ $duration }} Hari
                        </td>
                    </tr>

                    <tr>
                        <td width="170">Jumlah Bus</td>
                        <td width="1%">:</td>
                        <td>{{ $spj->booking_details->bookings->total_bus }} Unit</td>
                    </tr>
                    <tr>
                        <td width="170">Lokasi Jemput</td>
                        <td width="1%">:</td>
                        <td>{{ $spj->booking_details->bookings->lokasi_jemput }}</td>
                    </tr>
                    <tr>
                        <td width="170">Bis Tiba di Tempat Jam </td>
                        <td width="1%">:</td>
                        <td>
                            @php
                                $jamJemput = \Carbon\Carbon::parse($spj->jam_jemput);
                            @endphp
                            {{ $jamJemput->format('H:i d-m-Y ') }}
                        </td>
                    </tr>
                    <tr>
                        <td width="170">Berangkat Dari Pool</td>
                        <td width="1%">:</td>
                        <td>{{ \Carbon\Carbon::parse($spj->date_keluar)->format('d F Y H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td width="170">Selesai Pemakaian & Bis Tiba di Pool</td>
                        <td width="1%">:</td>
                        <td>
                            @if ($spj->date_masuk)
                                {{ \Carbon\Carbon::parse($spj->date_masuk)->format('d F Y H:i') }} WIB
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div style="padding: 10px; font-size: 16px; margin-bottom:">
                <tr>
                    <td width="170">Catatan</td>
                    <td width="1%">:</td>
                    <td>Lampirkan denah lokasi penjemputan</td>
                </tr>
            </div>
            <div style="padding: 10px; font-size: 16px; margin-bottom:">
                <tr>
                    <td width="170">Keterangan</td>
                    <td width="1%">:</td>
                </tr>
            </div>

            @if ($spj->km_masuk != null)
                <div>Data rincian biaya perjalanan sebagai berikut : </div>
                <div style="padding: 10px; font-size: 13px;">
                    <table width="100%">
                        <tr>
                            <td colspan="3">
                                <table width="100%" border="1" cellpadding="5" cellspacing="0"
                                    style="border-collapse: collapse;">
                                    <tr>
                                        <th>Nama</th>
                                        <th style="text-align: right">Harga</th>
                                    </tr>
                                    <tr>
                                        <td>Uang Jalan</td>
                                        <td style="text-align: right">{{ number_format($spj->uang_jalan) }}</td>
                                    </tr>
                                    <tr>
                                        <td>BBM</td>
                                        <td style="text-align: right">{{ number_format($spj->bbm) }}</td>

                                    </tr>
                                    <tr>
                                        <td>Uang Makan</td>
                                        <td style="text-align: right">{{ number_format($spj->uang_makan) }}</td>

                                    </tr>
                                    <tr>
                                        <td>Parkir</td>
                                        <td style="text-align: right">{{ number_format($spj->parkir) }}</td>

                                    </tr>
                                    <tr>
                                        <td>Tol</td>
                                        <td style="text-align: right">{{ number_format($spj->tol) }}</td>

                                    </tr>
                                    <tr>
                                        <td>Biaya Lain</td>
                                        <td style="text-align: right">{{ number_format($spj->biaya_lain) }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: right">Total Biaya yang harus dikembalikan: </th>
                                        <th style="text-align: right">
                                            {{ number_format($spj->uang_jalan - $spj->bbm - $spj->uang_makan - $spj->parkir - $spj->tol - $spj->biaya_lain) }}
                                        </th>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
            @endif


            {{-- <div style="margin-bottom: 20px;">Barang di atas akan disimpan dalam program Gold Priority selama 12 bulan.
                Dari transaksi ini customer mendapatkan benefit Jewellery Certificate yang akan dikirimkan melalui
                aplikasi CMK Club.</div> --}}
            {{-- <div style="margin-bottom: 20px;">Demikian perintah jalan ini dibuat secara sadar sebagai bukti
                yang sah dan dapat digunakan sesuai isi dalam surat ini.</div> --}}

            <table width="100%">
                <tr>
                    <td width="30%">
                        <div style="height: 80px; padding-top: 10px;">Manager Operasi</div>
                        ( ........................ )
                    </td>
                    <td width="30%">
                        <div style="height: 80px; padding-top: 10px;">Customer Service</div>
                        ( ........................ )
                    </td>
                    @if ($spj->km_masuk == null)
                        <td>
                            @php
                                \Carbon\Carbon::setLocale('id');
                                $currentDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
                            @endphp
                            {{ $currentDate }}
                            <div style="height: 80px;">Bagian Operasional</div>
                            ( {{ $spj->user_out->name }} )
                        </td>

                        @else
                        <td>
                            @php
                                \Carbon\Carbon::setLocale('id');
                                $currentDate = \Carbon\Carbon::now()->translatedFormat('l, d F Y');
                            @endphp
                            {{ $currentDate }}
                            <div style="height: 80px;">Bagian Operasional</div>
                            ( {{ $spj->user_in->name }} )
                        </td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
</body>
{{-- <script>window.print();</script> --}}

</html>
