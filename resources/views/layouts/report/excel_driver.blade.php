
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Kondektur</th>
                        <th>No SPJ</th>
                        <th>harga_booking</th>
                        <th>diskon</th>
                        <th>bbm</th>
                        <th>Uang Makan</th>
                        <th>uang kebersihan</th>
                        <th>tol</th>
                        <th>biaya_lain</th>
                        <th>bop</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($driver as $item)
                        {{-- @foreach ($item->booking_pendapatan as $data) --}}
                        @php
                            $harga_booking = $item->harga_std ?? 0;
                            $diskon        = $item->bookings->diskon ?? 0;
                            if($diskon != 0){
                                $diskon = ceil($item->bookings->diskon / $item->bookings->total_bus);
                            }
                            $bbm           = $item->spjs->bbm ?? 0;
                            $uang_makan    = $item->spjs->uang_makan ?? 0;
                            $parkir        = $item->spjs->parkir ?? 0;
                            $tol           = $item->spjs->tol ?? 0;
                            $biaya_lain    = $item->spjs->biaya_lain ?? 0;

                            $bop = $bbm + $uang_makan + $parkir + $tol + $biaya_lain;
                            $total_pendapatan = $harga_booking - $diskon - $bop;
                        @endphp
                            <tr>
                                <td>{{ $item->pengemudis->users->name }}</td>
                                <td>{{ $item->kondekturs ? $item->kondekturs->users->name : '-' }}</td>
                                <td>{{ $item->spjs->no_spj }}</td>
                                <td>{{ $harga_booking }}</td>
                                <td>{{ $diskon }}</td>
                                <td>{{ $bbm }}</td>
                                <td>{{ $uang_makan }}</td>
                                <td>{{ $parkir }}</td>
                                <td>{{ $tol }}</td>
                                <td>{{ $biaya_lain }}</td>
                                <td>{{ $bop }}</td>
                                <td>{{ $total_pendapatan }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>