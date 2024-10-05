<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css" media="print">
	    @page 
	    {
	        size: auto;   /* auto is the initial value */
	        margin: 5mm;  /* this affects the margin in the printer settings */
	    }
	</style>
	<style type="text/css">
		body {
			font-family: system-ui;
			font-size: 13px;
			zoom: 0.7;
		}

		.justify {
			text-align: justify;
		}

		.font-14 {
			font-size: 14px;
		}

		.font-15 {
			font-size: 15px;
		}

		.font-16 {
			font-size: 16px;
		}

		.font-20 {
			font-size: 20px;
		}

		.devider {
			border-top: 2px double #555;
		}

		.devider2 {
			border-top: 1px solid #000;
			margin-bottom: 0 !important;
		}

		.bold {
			font-weight: bold;
		}

		table .detail1 {
			font-size: 16px !important;
		}

		table .detail1 td {
			padding: 5px 0;
		}

		h1 {
			font-size: 40px;
		}
	</style>
</head>
<body>
	<div style="width: 914px; border: 2px dashed #333; padding:5px; margin-left: auto; margin-right: auto;">
		<div style="width: 900px; border: 2px dotted #333; padding:5px;">
			<table width="100%">
				<tr>
					<td colspan="2">
						<table width="100%">
							<tr>
								<td width="20%" align="center">
									<img src="{{ asset('img/pp150x150.png') }}" width="50%">
								</td>
								<td width="80%" style="padding-left:70px;"><h1>SURAT Booking Bus</h1></td>
							</tr>
							<tr>
								<td width="50%" colspan="2">
									<table width="100%">
										<tr>
											<td width="60%"><span class="font-20"><b>No Booking</b> : {{$booking->no_booking}}</span></td>
											<td width="40%">
												<table width="100%" class="font-16">
													<tr style="display: none;">
														<td><b>Kode Cabang/Unit</b></td>
														{{-- <td>: {{ $pawn->store->id }}</td> --}}
													</tr>
													<tr>
														<td><b>Cabang/Unit</b></td>
														<td>: Tangerang</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><hr class="devider"></td></tr>
				<tr>
					<td width="70%" valign="top">
						<table width="100%" class="detail1">
							<tr>
								<td width="25%">Nama</td>
								<td width="75%" class="bold">: {{ $booking->customer }}</td>
							</tr>
							<tr>
								<td width="25%">Telp/HP</td>
								<td width="75%" class="bold">: {{ $booking->telephone }}</td>
							</tr>
							<tr>
								<td width="25%" valign="top">Lokasi Jemput</td>
								<td width="75%" class="bold" valign="top">: {{ $booking->lokasi_jemput }}</td>
							</tr>
						</table>
					</td>
					<td width="30%" valign="top">
						<table width="100%">
							<tr>
								<td>
									<div>Tanggal Awal Gadai :</div>
									<div class="font-16"><center><b>{{ date("d/m/Y", strtotime($booking->date_start)) }}</b></center></div>
									<hr class="devider2">
								</td>
							</tr>
							<tr>
								<td>
									<div>Tanggal Jatuh Tempo :</div>
									<div class="font-16"><center><b>{{ date("d/m/Y", strtotime($booking->date_end)) }}</b></center></div>
									<hr class="devider2">
								</td>
							</tr>							
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><hr class="devider"></td></tr>
				<tr>
					<td width="70%" valign="top">
						<table width="100%" class="detail1">
							<tr><td>Uraian Booking :</td></tr>
							<tr><td class="font-16 bold">
								{{-- @foreach($pawn->item as $item)
									{{ $item->remark }}
								@endforeach --}}
							</td></tr>
						</table>
					</td>
					<td width="30%" valign="top">
						<table width="100%">
							<tr>
								<td>
									<div>Nilai Booking :</div>
									<div class="font-16"><center><b>Rp. {{number_format($booking->harga_std)}} </b></center></div>
									<hr class="devider2">
								</td>
							</tr>
							<tr>
								<td>
									<div>Biaya Jemput :</div>
									<div class="font-16"><center><b>Rp. {{number_format($booking->biaya_jemput)}}</b></center></div>
									<hr class="devider2">
								</td>
							</tr>
							<tr>
								<td>
									<div>Diskon :</div>
									<div class="font-16"><center><b>Rp.{{number_format($booking->diskon)}} </b></center></div>
									<hr class="devider2">
								</td>
							</tr>
                            <tr>
								<td>
									<div>Total :</div>
									<div class="font-16"><center><b>Rp.{{number_format($booking->grand_total)}} </b></center></div>
									<hr class="devider2">
								</td>
							</tr>							
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><hr class="devider"></td></tr>
				<tr>
					<td width="60%">
						<table width="100%" style="margin-top: -13px;">
							<tr>
								<td width="33%" align="center" style="padding-top: 10px; border-left: 2px double #555;">
									<span class="font-16">Membuat</span>
									<div style="height: 120px;"></div>
									<span class="font-16" style="text-transform: uppercase;">{{$booking->users->name}}</span>
								</td>
								<td width="33%" align="center" style="padding-top: 10px; border-left: 2px double #555; border-right: 2px double #555;">
									<span class="font-16">Customer</span>
									<div style="height: 120px;"></div>
									<span class="font-16" style="text-transform: uppercase;">{{$booking->customer}}</span>
								</td>
								{{-- <td width="33%" align="center" style="padding-top: 10px; border-right: 2px double #555;">
									<span class="font-16">Nasabah</span>
									<div style="height: 120px;"></div>
									<span class="font-16" style="text-transform: uppercase;"></span>
								</td> --}}
							</tr>
						</table>
					</td>
					<td width="40%" valign="top">
						<table width="100%" style="margin-top: -5px">
							<tr>
								<td class="font-14 justify">
									Dengan menandatangani Surat Bukti Booking ini, kedua belah pihak sepakat dan setuju bahwa bukti booking ini merupakan butik sah yang dikeluarkan oleh primajasa dan sepengetahuan customer.
								</td>
							</tr>
						</table>
						<hr class="devider">
						{{-- <div style="text-align: center; margin:0 auto;">
							<img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($pawn->application_number, 'C39') }}" alt="barcode"   /> <br>
							{{ $pawn->application_number }}
						</div> --}}
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>