<table>
    <thead>
        <tr>
            <td colspan="2">Tanggal Transaksi</td>
            <td colspan="2"><strong style="font-wigh: 600">{{ $date_from }} - {{ $date_to }}</strong></td>
        </tr>
        <tr></tr>

        <tr style="background: #4caf50!important">
            <th>Item</th>
            <th>Satuan</th>
            <th>Jenis</th>
            <th>Harga Pokok</th>
            <th>Harga Jual</th>
            <th>Kuantitas</th>
            <th>Diskon</th>
            <th>Pendapatan</th>
            <th>Laba</th>
        </tr>
    </thead>
    <tbody>
        @foreach($omset as $om)
        <tr>
            <td>{{ $om->name }}</td>
            <td>{{ $om->unit }}</td>
            <td>{{ $om->category }}</td>
            <td>{{ $om->main_cost }}</td>
            <td>{{ $om->price }}</td>
            <td>{{ $om->qty }}</td>
            <td>{{ $om->discount }}</td>
            <td>{{ $om->omset }}</td>
            <td>{{ $om->profit }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="7"><strong style="font-wigh: 600">Total</strong></td>
            <td><strong style="font-wigh: 600">Rp.{{ number_format($total_omset, 2) }}</strong></td>
            <td><strong style="font-wigh: 600">Rp.{{ number_format($total_profit, 2) }}</strong> </td>
        </tr>
    </tbody>
</table>