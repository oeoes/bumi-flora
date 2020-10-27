<div class="flex scrollable hover">
    <div class="nav-active-text-primary" data-nav>
        <ul class="nav bg">
            <li class="nav-header hidden-folded">
                <span class="text-muted">Main</span>
            </li>
            <li>
                <a href="{{ route('dashboard.index') }}">
                    <span class="nav-icon text-primary"><i data-feather='home'></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
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
                <a href="{{ route('entities.index') }}">
                    <span class="nav-icon text-danger"><i data-feather='users'></i></span>
                    <span class="nav-text">Entity</span>
                </a>
            </li>
            <li>
                <a href="{{ route('supports.index') }}">
                    <span class="nav-icon text-info"><i data-feather='clipboard'></i></span>
                    <span class="nav-text">Data Pendukung</span>
                </a>
            </li>

            <!-- persediaan -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Tools</span>
            </li>
            <li>
                <a href="{{ route('barcodes.index') }}">
                    <span class="nav-icon"><i data-feather='grid'></i></span>
                    <span class="nav-text">Barcode Generator</span>
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
                    <span class="nav-text">History Item</span>
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
           

            <!-- Pembelian -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Pembelian</span>
            </li>

            <li>
                <a href="{{ route('orders.index') }}">
                    <span class="nav-icon"><i data-feather='shopping-cart'></i></span>
                    <span class="nav-text">Pesanan Pembelian</span>
                </a>
            </li>
            <li>
                <a href="{{ route('orders.history_order') }}">
                    <span class="nav-icon"><i data-feather='shopping-bag'></i></span>
                    <span class="nav-text">History Pembelian</span>
                </a>
            </li>

            <!-- Akses managgement -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Akses Management</span>
            </li>
            <li>
                <a href="{{ route('access.index') }}">
                    <span class="nav-icon"><i data-feather='lock'></i></span>
                    <span class="nav-text">Roles & Permission</span>
                </a>
            </li>

            <!-- Pembelian -->
            <li class="nav-header hidden-folded">
                <span class="text-muted">Kasir</span>
            </li>

            <li>
                <a href="{{ route('orders.cashier_page') }}">
                    <span class="nav-icon"><i data-feather='airplay'></i></span>
                    <span class="nav-text">Home</span>
                </a>
            </li>

            <li>
                <a href="{{ route('cashier.history') }}">
                    <span class="nav-icon"><i data-feather='clock'></i></span>
                    <span class="nav-text">History Transaksi</span>
                </a>
            </li>
        </ul>
    </div>
</div>