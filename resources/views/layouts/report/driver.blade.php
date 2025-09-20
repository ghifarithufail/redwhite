@extends('main')
@section('content')
    <div class="card text-center">
        <h5 class="card-header">Report Driver</h5>
    </div>
    <div class="card mt-4">

        <div class="card-header" style="zoom: 0.8">
            <form>
                <div class="form-group row">
                    <div class="col-sm-3 mt-2">
                        {{-- <label for="date1">Kecamatan:</label> --}}
                        <input type="date" style="height: 40px" class="form-control" placeholder="kelurahan atau kecamatan"
                            value="{{ $request['start_date'] }}" name="start_date" id="start_date">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="date" style="height: 40px" class="form-control"
                            placeholder="kelurahan atau kecamatan" value="{{ $request['end_date'] }}" name="end_date"
                            id="end_date">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="pengemudi" name="pengemudi" value="{{ $request['pengemudi'] }}">
                    </div>
                    <div class="col-sm-3 mt-2">
                        <input type="text" class="form-control" placeholder="kondektur" value="{{ $request['kondektur'] }}" name="kondektur"
                            id="no_booking">
                    </div>
                    <div class="col-sm-2 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded text-white mt-2" style="height: 40px;"
                                id="search_btn">Search</button>
                            <a href="{{ route('driver/excel', [
                                'start_date' => $request['start_date'],
                                'end_date' => $request['end_date'],
                                'pengemudi' => $request['pengemudi'],
                                'kondektur' => $request['kondektur'],
                            ]) }}"
                                class="btn btn-success rounded text-white mt-2" style="height: 40px;"
                                id="search_btn">excel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" style="zoom: 0.75">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Kondektur</th>
                        <th>No SPJ</th>
                        <th>harga_booking</th>
                        <th>diskon</th>
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
                                <td>{{ number_format($harga_booking) }}</td>
                                <td>{{ number_format($diskon) }}</td>
                                <td>{{ number_format($bop) }}</td>
                                <td>{{ number_format($total_pendapatan) }}</td>
                            </tr>
                        {{-- @endforeach --}}
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-end p-3">
                {{ $driver->links() }}
            </div>
        </div>
    </div>
@endsection
