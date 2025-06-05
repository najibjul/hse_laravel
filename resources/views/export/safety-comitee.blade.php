<table>
    <thead>
    <tr>
        <th>User</th>
        <th>Aktifitas / Problem</th>
        <th>Area</th>
        <th>Faktor</th>
        <th>Status Cek</th>
        <th>Status Terakhir</th>
        <th>Departemen</th>
        <th>Kategori</th>
        <th>Rekomendasi</th>
        <th>Rank</th>
        <th>Gambar Sebelum</th>
        <th>Gambar Sesudah</th>
        <th>Batas Penyelesaian</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
            <td style="height: 150px;">{{ $data->user->name }} ({{ $data->user->nip }})</td>
            <td>{{ $data->activity ? $data->activity : $data->qrpDetail->description }}</td>
            <td>{{ $data->area }}</td>
            <td>{{ $data->factor?->factor_name }}</td>
            <td>{{ $data->check_status }}</td>
            <td>{{ $data->qrpDetail?->qrpStatus->name  }}</td>
            <td>{{ $data->qrpDetail?->department->department_name }}</td>
            <td>{{ $data->qrpDetail?->category->category_name }}</td>
            <td>@if (isset($data->qrpDetail))@foreach (json_decode($data->qrpDetail->recomendation, true) as $recomendation) || {{ $recomendation['user'] }}{{ $recomendation['recomendation'] }}@endforeach @endif</td>
            <td>{{ $data->qrpDetail?->rank->rank_name }}</td>
            <td></td>
            <td></td>
            <td>{{ $data->qrpDetail?->due_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
