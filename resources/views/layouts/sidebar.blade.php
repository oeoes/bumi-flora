<div class="flex scrollable hover">
    <div class="nav-active-text-primary" data-nav>
        <ul class="nav bg">
            <li class="nav-header hidden-folded">
                <span class="text-muted">Main</span>
            </li>
            <li>
                <a href="">
                    <span class="nav-icon text-primary"><i data-feather='home'></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <!-- <li class="nav-header hidden-folded">
                <span class="text-muted">Applications</span>
            </li>
            <li>
                <a href="app.calendar.html">
                    <span class="nav-icon text-info"><i data-feather='calendar'></i></span>
                    <span class="nav-text">Calendar</span>
                    <span class="nav-badge"><b class="badge-circle xs text-danger"></b></span>
                </a>
            </li>
            <li>
                <a href="app.user.html">
                    <span class="nav-icon text-success"><i data-feather='users'></i></span>
                    <span class="nav-text">Users</span>
                </a>
            </li> -->
        </ul>
        <ul class="nav ">
            <li class="nav-header hidden-folded">
                <span class="text-muted">Master Data</span>
            </li>
            <li>
                <a href="{{ route('items.index') }}">
                    <span class="nav-icon text-primary"><i data-feather='list'></i></span>
                    <span class="nav-text">Data Item</span>
                </a>
            </li>
            <li>
                <a href="">
                    <span class="nav-icon text-danger"><i data-feather='users'></i></span>
                    <span class="nav-text">Stake Holders</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supports.index') }}">
                    <span class="nav-icon text-info"><i data-feather='clipboard'></i></span>
                    <span class="nav-text">Data Pendukung</span>
                </a>
            </li>
           

            <!-- Pembelian -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Pembelian</span>
            </li>

            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='shopping-cart'></i></span>
                    <span class="nav-text">Pesanan Pembelian</span>
                </a>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='shopping-bag'></i></span>
                    <span class="nav-text">Pembelian</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('storages.utama') }}" class="" data-pjax-state="">
                            <span class="nav-text">Daftar Pembelian</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('storages.gudang') }}" class="" data-pjax-state="">
                            <span class="nav-text">History Harga Beli</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='percent'></i></span>
                    <span class="nav-text">Bayar Hutang</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('storages.utama') }}" class="" data-pjax-state="">
                            <span class="nav-text">Daftar Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('storages.gudang') }}" class="" data-pjax-state="">
                            <span class="nav-text">Status Lunas BG/Cek</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='rotate-cw'></i></span>
                    <span class="nav-text">Retur Pembelian</span>
                </a>
            </li>


            <!-- Penjualan -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Penjualan</span>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='activity'></i></span>
                    <span class="nav-text">Pesanan Penjualan</span>
                </a>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='package'></i></span>
                    <span class="nav-text">Penjualan</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('storages.utama') }}" class="" data-pjax-state="">
                            <span class="nav-text">Daftar Penjualan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('storages.gudang') }}" class="" data-pjax-state="">
                            <span class="nav-text">Kasir</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('storages.gudang') }}" class="" data-pjax-state="">
                            <span class="nav-text">History Harga Jual</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='percent'></i></span>
                    <span class="nav-text">Bayar Piutang</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('storages.utama') }}" class="" data-pjax-state="">
                            <span class="nav-text">Daftar Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('storages.utama') }}" class="" data-pjax-state="">
                            <span class="nav-text">Status Lunas BG/Cek</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='rotate-cw'></i></span>
                    <span class="nav-text">Retur Penjualan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='plus'></i></span>
                    <span class="nav-text">Point Penjualan</span>
                </a>
            </li>


            <!-- persediaan -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Persediaan</span>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='database'></i></span>
                    <span class="nav-text">Penyimpanan</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('storages.utama') }}" class="" data-pjax-state="">
                            <span class="nav-text">Utama</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('storages.gudang') }}" class="" data-pjax-state="">
                            <span class="nav-text">Gudang</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path><polyline points="2.32 6.16 12 11 21.68 6.16"></polyline><line x1="12" y1="22.76" x2="12" y2="11"></line></svg></span>
                    <span class="nav-text">Record Item</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('records.keluar') }}" class="" data-pjax-state="">
                            <span class="nav-text">Item Keluar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Item Masuk</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='activity'></i></span>
                    <span class="nav-text">Stok Opname</span>
                </a>
            </li>

            <!-- Akuntansi -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Akuntansi</span>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='folder'></i></span>
                    <span class="nav-text">Daftar Perkiraan</span>
                </a>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='folder-plus'></i></span>
                    <span class="nav-text">Kas</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('records.keluar') }}" class="" data-pjax-state="">
                            <span class="nav-text">Kas Keluar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Kas Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Kas Transfer</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('storages.opname') }}">
                    <span class="nav-icon"><i data-feather='copy'></i></span>
                    <span class="nav-text">Daftar Jurnal</span>
                </a>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='settings'></i></span>
                    <span class="nav-text">Pengaturan</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('records.keluar') }}" class="" data-pjax-state="">
                            <span class="nav-text">Saldo Awal Perkiraan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Saldo Awal Hutang</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Saldo Awal Piutang</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Setting Perkiraan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Proses Data -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Proses Data</span>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><i data-feather='target'></i></span>
                    <span class="nav-text">Proses</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('records.keluar') }}" class="" data-pjax-state="">
                            <span class="nav-text">Proses Bulanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">Proses Tahunan</span>
                        </a>
                    </li>
                </ul>
            </li>

            
            <!-- Laporan -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Laporan</span>
            </li>
            <li class="">
                <a href="#" class="" data-pjax-state="anchor-empty">
                    <span class="nav-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path><polyline points="2.32 6.16 12 11 21.68 6.16"></polyline><line x1="12" y1="22.76" x2="12" y2="11"></line></svg></span>
                    <span class="nav-text">Laporan Master</span>
                    <span class="nav-caret"></span>
                </a>
                <ul class="nav-sub nav-mega">
                    <li>
                        <a href="{{ route('records.keluar') }}" class="" data-pjax-state="">
                            <span class="nav-text">Daftar Item</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('records.masuk') }}" class="" data-pjax-state="">
                            <span class="nav-text">DLL</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>