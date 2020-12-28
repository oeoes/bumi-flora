<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        table {
            width: 100%;
        }

        th,
        td {
            border: 2px solid black !important;
            padding: 10px;
            text-align: left;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <h2>Data Item</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Item</th>
                <th>Barcode</th>
                <th>Nama Item</th>
                <th>Jenis</th>
                <th>Satuan</th>
                <th>Merek</th>
                @if($reportType === 'main_cost' || $reportType === 'complete')
                <th>Harga Pokok</th>
                @endif
                @if($reportType === 'price' || $reportType === 'complete')
                <th>Harga Jual</th>
                @endif
                <th>Sat. Das</th>
                <th>Konv. Sat. Das</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $key => $item)
            <tr>
                <td>{{ $key+1 }}.</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->item }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->brand }}</td>
                @if($reportType === 'main_cost' || $reportType === 'complete')
                <td>Rp.{{ number_format($item->main_cost) }}</td>
                @endif
                @if($reportType === 'price' || $reportType === 'complete')
                <td>Rp.{{ number_format($item->price) }}</td>
                @endif
                <td>{{ $item->base_unit }}</td>
                <td>{{ $item->base_unit_conversion }}</td>
            </tr>
            @endforeach
            <div class="page-break"></div>
        </tbody>
    </table>
</body>

</html>