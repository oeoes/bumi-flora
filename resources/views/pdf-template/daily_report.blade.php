<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page {
            margin-left: 1rem;
            margin-right: 1rem;
            margin-top: 25px;
        }

        table {
            width: 100%;
            font-size: 10px;
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
    <div style="text-align: left; font-size: 11px; font-weight: bold">LAPORAN PENJUALAN</div>

    <div style="width: 100%; height: 1px; background: #888;"></div>

    <table style="width: 100%; margin-top: 1rem">
        <tbody>
            <tr>
                <td>PERIODE</td>
                <td>: {{ \Carbon\Carbon::now()->format('d-m-Y') }} s/d {{ \Carbon\Carbon::now()->addDay(1)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>USER</td>
                <td>: {{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td>STORAGE</td>
                <td>: UTAMA</td>
            </tr>
        </tbody>
    </table>

    <div style="width: 100%; height: 1px; background: #888; margin-top: 1rem"></div>

    <table style="width: 100%; margin-top: 1rem">
        <tbody>
            <tr>
                <td>JML TRANSAKSI</td>
                <td>: {{ $data['jumlah_transaksi'] }}</td>
            </tr>
            <tr>
                <td>TOT. POTONGAN</td>
                <td>: {{ number_format($data['total_discount']) }}</td>
            </tr>
            <tr>
                <td>TOT. PAJAK</td>
                <td>: {{ number_format($data['total_pajak']) }}</td>
            </tr>
            <tr>
                <td>TOT. BIAYA</td>
                <td>: {{ number_format($data['total_biaya']) }}</td>
            </tr>
            <tr>
                <td>TOTAL</td>
                <td>: {{ number_format($data['total_tunai'] + $data['total_debit'] + $data['total_ewallet'] + $data['total_transfer'] + $data['total_credit']) }}</td>
            </tr>
            <tr>
                <td>BAYAR TUNAI</td>
                <td>: {{ number_format($data['total_tunai']) }}</td>
            </tr>
            <tr>
                <td>BAYAR DEBIT</td>
                <td>: {{ number_format($data['total_debit']) }}</td>
            </tr>
            <tr>
                <td>BAYAR TRANSFER</td>
                <td>: {{ number_format($data['total_transfer']) }}</td>
            </tr>
            <tr>
                <td>BAYAR EWALLET</td>
                <td>: {{ number_format($data['total_ewallet']) }}</td>
            </tr>
            <tr>
                <td>BAYAR KREDIT</td>
                <td>: {{ number_format($data['total_credit']) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="width: 100%; height: 1px; background: #888; margin-top: 1rem"></div>

    <table style="width: 100%; margin-top: 1rem">
        <tbody>
            <tr>
                <td>JAM CETAK</td>
                <td>: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>