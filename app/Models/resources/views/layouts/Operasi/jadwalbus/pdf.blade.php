<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Bus</title>
    <style>
        .page-break {
            page-break-before: always;
        }
        .small-text {
            font-size: 10px !important;
        }
    </style>
</head>
<body>


    @foreach ($typearmadas as $typearmada)
        @if (!$loop->first)
            <div class="page-break"></div>
        @endif
        <h3>Jadwal Bus</h3>
        <p>Periode: {{ $date_start }} - {{ $date_end }}</p>
        <h5>Type Armada: {{ $typearmada->name }}</h5>
        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th class="small-text">Armada</th>
                    @foreach ($dates as $date => $day)
                        <th class="small-text">{{ $day }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($armadas->where('type_id', $typearmada->id) as $armada)
                    <tr>
                        <td class="small-text">{{ $armada->nobody }}</td>
                        @foreach ($dates as $date => $day)
                            <td class="small-text">{{ $schedule[$armada->id][$date] }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td class="small-text"><strong>Total Armada</strong></td>
                    @foreach ($dates as $date => $day)
                        <td class="small-text"><strong>{{ $totalArmadas[$date] ?? 0 }}</strong></td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html>
