<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="{{ route('home') }}" class="brand-link">
        {{-- <img src="" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}                
        <i class="fa-solid fa-warehouse elevation-3 ml-3" style="opacity: .8"></i>
        <span class="brand-text font-weight-light" style="font-size: 1rem;">Inventory Management</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">MASTER</li>
                <li class="nav-item">
                    <a href="{{ route('home') }}/" class="nav-link">
                        <i class="fa-solid fa-gauge"></i>
                        <p>Dashboard</p>
                    </a>                    
                </li>

                @if (Auth::user()->role_code == 'owner')
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa-solid fa-coins"></i>
                            <p>Keuangan</p>
                        </a>
                    </li>                    
                @endif                
                <li class="nav-item">
                    <a href="{{ route('warehouse.index') }}" class="nav-link">
                        <i class="fa-solid fa-warehouse"></i>
                        <p>Gudang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-solid fa-truck-moving"></i>
                        <p>Kendaraan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fa-regular fa-id-card"></i>
                        <p>Driver</p>
                    </a>
                </li>
                <hr style="border: 0.5px solid #fff; width:100%; opacity:0.2;">
                <li class="nav-header">TRANSAKSI</li>
                <li class="nav-item">
                    <a href="{{ route('transaction.incomingIndex') }}" class="nav-link">
                        <i class="fa-solid fa-boxes-packing"></i>
                        <p>Barang Masuk <small>(IN)</small></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transaction.outgoingIndex') }}" class="nav-link">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <p>Barang Keluar <small>(OUT)</small></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('transaction.transactionLogsIndex') }}" class="nav-link">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <p>Riwayat <small>(LOGS)</small></p>
                    </a>
                </li>
                <hr style="border: 0.5px solid #fff; width:100%; opacity:0.2;">                
                
            </ul>
        </nav>
    </div>
</aside>
