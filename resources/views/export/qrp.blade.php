<table>
    <thead>
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">DESKRIPSI</th>
            <th rowspan="2">KATEGORI</th>
            <th rowspan="2">RANK</th>
            <th rowspan="2">AREA</th>
            <th colspan="2">GAMBAR</th>
            <th rowspan="2">REKOMENDASI</th>
            <th rowspan="2">PIC</th>
            <th rowspan="2">BATAS WAKTU PENYELESAIAN</th>
            <th rowspan="2">STATUS</th>
        </tr>
        <tr>
            <th>BEFORE</th>
            <th>AFTER</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>{{ $dailyCheck->qrpDetail?->description }}</td>
            <td>{{ $dailyCheck->qrpDetail->category->category_name }}</td>
            <td>{{ $dailyCheck->qrpDetail->rank->rank_name }}</td>
            <td>{{ $dailyCheck->area }}</td>
            <td></td>
            <td></td>
            <td>
                <ul>
                    @foreach ($dailyCheck->qrpDetail->qrpRecomendations as $qrpRecomendation)
                        <li>{{ $qrpRecomendation->user->name }} ({{ $qrpRecomendation->user->nip }}) - {{ $qrpRecomendation->recomendation }}</li>
                    @endforeach
                </ul>
            </td>
            <td></td>
            <td>{{ \Carbon\Carbon::parse($dailyCheck->qrpDetail->due_date)->format('d M Y') }}</td>
            <td>
                {{ $dailyCheck->qrpDetail->qrpStatus->name }}
            </td>
        </tr>
    </tbody>
</table>