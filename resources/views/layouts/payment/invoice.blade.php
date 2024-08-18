<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="5qdZDOOsTk1xdv5nhwNrlwNB1H36bfOGJO0nkmo8">
<title>Invoice Transaction Sell</title>
<link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
<style>
    @page  {
        size: 58mm auto;
        margin:0;
    }

    body {
        font-size:12px;
        font-family:'Play', sans-serif;
        background: #eee;
    }

    h1 { font-size:1em; font-weight:700; }

    .devider {
        border: 1px dashed #444;
    }

    #section {
        margin-bottom:10px;
        text-align: left !important;
    }

    #end { page-break-after: always; }

    #invoice {
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
        margin-bottom:5px;
        height: 80px;
        border: 0px solid #f00;
        text-align: left;
    }

    #listItem table {
        border: solid #999 !important;
        border-width: 1px 0 0 1px !important;
    }
    #listItem th, #listItem td {
        border: solid #999 !important;
        border-width: 1px !important;
        padding: 5px;
    }
</style>
</head>
<body>
    @php
        $trx_date = \Carbon\Carbon::parse($payments->created_at)->format('d F Y');
    @endphp
    <div id="invoice">
        <div id="section" style="zoom: 0.9;">
            <table width="100%">
                <tr>
                    <td width='20px'></td>
                    <td width="10px"><img src="{{ asset('img/pp150x150.png') }}" height="80"></td>
                    <td valign="top" align="center" width="450px">
                        <div style="font-size: 25px; padding-bottom: 5px;">PT. Primajasa</div>
                        <div style="font-size: 12px;">Jl.Mayjen Sutotyo No.32Cililitan - Jakarta Timur</div>
                        <div style="font-size: 12px;">Telp: +62 812-1219-545| email: pengaduan@primajasagroup.com</div>
                        <div style="font-size: 12px;">Website : www.primajasagroup.com</div>
                    </td>
                    <td width="120px"></td>
                </tr>
            </table>
            <hr >
            <table width="100%">
                <tr>
                    <td colspan="3" style="text-align: center !important;"><span style="font-size: 28px;"><u>INVOICE</u></span></td>
                </tr>
                <tr>
                    <td width="35%" style="padding-bottom:60px;">
                        <div style="border: 1px solid #ccc; padding: 10px; text-align: left !important;"><b>Customer : {{ $payments->bookings->customer }}</b></div>
                        <div style="border: 1px solid #ccc; padding: 5px; text-align: left !important;">
                            <div style="padding:5px;"><b>Phone : {{ $payments->bookings->telephone }}</b></div>
                        </div>
                        <div style="border: 1px solid #ccc; padding: 5px; text-align: left !important;">
                            <div style="padding:5px;"><b>Jumlah Belum Dibayarkan : Rp.{{ number_format($payments->bookings->grand_total -  $payments->bookings->total_payment ?? 0)}}</b></div>
                        </div>
                    </td>
                    <td width="30%"></td>
                    <td width="35%">
                        <div style="border: 1px solid #ccc; padding: 10px; text-align: left !important; background-color:#C1F0FF"><b>No.  :  {{ $payments->no_payment }}</b></div>
                        <div style="border: 1px solid #ccc; padding: 10px; text-align: left !important; margin-bottom:80px;">
                            <table>
                                <tr>
                                    <td width="45%" valign="top">Pembayaran </td>
                                    <td width="45%" valign="top">: 
                                        @if ($payments->jmlh_bayar == 1)
                                            pertama
                                        @elseif($payments->jmlh_bayar == 2)
                                            kedua
                                        @elseif($payments->jmlh_bayar == 3)
                                            Ketiga
                                        @elseif($payments->jmlh_bayar == 4)
                                            Pelunasan
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="45%" valign="top">Currency </td>
                                    <td width="45%" valign="top">: IDR</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 10px 0px;">
                        <table id="listItem" width="100%" style="margin-top: 20px;" cellspacing="0" cellpadding="0">
                            <tr style="text-align: center !important;">
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th>Biaya Yang Dibayar (Rp)</th>
                            </tr>
                            @php
                                $no = 1;
                            @endphp
                                <tr>
                                    <td style="text-align: center;" width='5%'>{{ $no }}</td>
                                    <td width='50%'>Customer dengan nama <b>{{ $payments->bookings->customer }}</b> telah melakukan pembayaran  
                                    @if ($payments->jmlh_bayar == 1)
                                        <b>pertama</b>
                                    @elseif($payments->jmlh_bayar == 2)
                                        <b>kedua</b>
                                    @elseif($payments->jmlh_bayar == 3)
                                        <b>Ketiga</b>
                                    @elseif($payments->jmlh_bayar == 4)
                                        <b>Pelunasan</b>
                                    @endif
                                    Dengan Tujuan Wisata {{ $payments->bookings->tujuan->nama_tujuan }}
                                </td>
                                    <td style="text-align: right;" width='20%'><b>{{ number_format($payments->price) }}</b></td>
                                </tr>
                                @php
                                    $no++;
                                @endphp
                        </table>
                    </td>
                </tr>
            </table>
			{{-- @if(auth()->user()->store_id == '141')
				<div style="padding: 0px 0 10px;">
					<b>Notes : </b>{{ $data->notes }}
				</div>
			@endif --}}
            <table width="100%" style="margin-top: 20px;">
                <tr>
                    <td width="45%" valign="top">
                        {{-- @if($data->payment_method == 'transfer')
                            <h3 style="margin-top: 0px; margin-bottom: 5px;">Payment Instruction</h3>
                            <div>Please transfer the payment in full amount.</div>
                            <div>Payment should be made via transfer to our Bank Account:</div>
                            <div>PT Laku Emas Indonesia</div>
                            <div>Bank BCA - Branch Grogol Muwardi</div>
                            @if(auth()->user()->store_id == 140)
                                <div><b>268-3059951</b></div>
                            @else
                                <div><b>268-2686333</b></div>
                            @endif
                        @endif --}}
                        
                    </td>
                    <td width="20%">
                        <div style="text-align: center;">
                            <div style="height: 112px;"></div>
                            <div id="ttd" style=""></div>
                            <div><b>{{ ucwords($payments->bookings->customer) }}</b></div>
                            <div><i>Customer</i></div>
                        </div>
                    </td>
                    <td width="35%" valign="top" style="padding-top: 5px;">
                        <div style="text-align: center;">
                            <div>Jakarta, {{ $trx_date }}</div>
                            <div id="ttd" style=""></div>
                            <div style="height: 93px;"></div>
                            <div id="ttd" style=""></div>
							{{-- <img height="80px" src="{{ asset('/images/Stamp_LEI_Blue.png') }}"> --}}
                            <div ><b>{{ ucwords($payments->users->name) }}</b></div>
                            <div><i>Staff Keuangan</i></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>