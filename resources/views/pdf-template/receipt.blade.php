<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page {
            margin-left: 5px;
            margin-right: 5px;
            margin-top: 25px;
            margin-left: 5px;
        }

        table {
            width: 100%;
            font-size: 9px;
        }

        th,
        td {
            border: 2px solid black !important;
            /* padding: 10px; */
            text-align: left;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div style="text-align: center; font-size: 11px;">BUMI FLORA</div>
    <div style="text-align: center; font-size: 9px;">JL. KH. MAULANA HASANUDIN NO. 80 CIPONDOH</div>
    <div style="text-align: center; font-size: 9px;" \>TANGERANG - BANTEN</div>
    <div style="text-align: center; font-size: 9px;">Telp: 085772386441 Fax:</div>

    <table style="width: 100%; margin-top: 1rem">
        <tbody>
            <tr>
                <td>No.</td>
                <td>: {{ $calc['transaction_number'] }}</td>
            </tr>
            <tr>
                <td>Ksr.</td>
                <td>: {{ auth()->user()->name }} (waktu: {{ \Carbon\Carbon::now()->format("H:i:s") }})</td>
            </tr>
            <tr>
                <td>Cust.</td>
                <td>: {{ $calc['customer'] }}</td>
            </tr>
        </tbody>
    </table>

    <div style="width: 100%; height: 1px; background: #888;"></div>

    <table style="width: 100%; margin-top: 1rem">
        <tbody>
            @foreach($items as $item)
            @if($item['discount'] > 0)
            <tr>
                <td colspan="2" style="width: 80%; text-align: left;"><b>{{ $item['name'] }}</b></td>
                <td colspan="2" style="width: 20%; text-align: right;"><b>{{ $item['satuan'] }}</b></td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%; text-align: left">{{ $item['price'] }}</td>
                <td colspan="2" style="width: 50%; text-align: left">x {{ $item['qty'] }}</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%; text-align: left">Pot.:{{ $item['discount'] }}%</td>
                <td style="width: 10%; text-align: left">=</td>
                <td style="width: 40%; text-align: right">{{ $item['total'] }}</td>
            </tr>
            @else
            <tr>
                <td colspan="2" style="width: 80%; text-align: left;"><b>{{ $item['name'] }}</b></td>
                <td colspan="2" style="width: 20%; text-align: right;"><b>{{ $item['satuan'] }}</b></td>
            </tr>
            <tr>
                <td style="width: 30%; text-align: left">{{ $item['price'] }}</td>
                <td style="width: 30%; text-align: left">x {{ $item['qty'] }}</td>
                <td style="width: 10%; text-align: left">=</td>
                <td style="width: 30%; text-align: right">{{ $item['total'] }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <div style="width: 100%; height: 1px; background: #888; margin-top: 1rem"></div>

    @php
    $sum_qty = 0;
    foreach ($items as $item) {
    $sum_qty += $item['qty'];
    }
    @endphp
    <table style="width: 100%;">
        <tbody>
            <tr>
                <td colspan="2" style="text-align: left">ITEM: {{ count($items) }}; QTY: {{ $sum_qty }}</td>
                <td style="text-align: right">{{ $calc['total_price'] }}</td>
            </tr>
            <tr>
                <td style="text-align: left; width: 30%">Discount</td>
                <td style="text-align: left; width: 10%">=</td>
                <td style="text-align: right; width: 60%">{{ $calc['discount'] }}</td>
            </tr>
            <tr>
                <td style="text-align: left; width: 30%">Biaya Lain</td>
                <td style="text-align: left; width: 10%">=</td>
                <td style="text-align: right; width: 60%">{{ $calc['fee'] }}</td>
            </tr>
            <tr>
                <td style="text-align: left; width: 30%">Total Akhir</td>
                <td style="text-align: left; width: 10%">=</td>
                <td style="text-align: right; width: 60%">{{ $calc['bill'] }}</td>
            </tr>
            <tr>
                <td style="text-align: left; width: 30%">Tunai</td>
                <td style="text-align: left; width: 10%">=</td>
                <td style="text-align: right; width: 60%">{{ $calc['cash'] }}</td>
            </tr>
            <tr>
                <td style="text-align: left; width: 30%">Kembali</td>
                <td style="text-align: left; width: 10%">=</td>
                <td style="text-align: right; width: 60%">{{ $calc['cashback'] }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>