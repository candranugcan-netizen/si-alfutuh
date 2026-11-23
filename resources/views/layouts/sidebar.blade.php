<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion toggled" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/logo.png') }}" alt="Logo RA Al Futuh" style="width: 45px; height: auto; border-radius: 5px;">
        </div>
        <div class="sidebar-brand-text mx-3">RA Al Futuh</div>
    </a>

    <hr class="sidebar-divider my-0">

    @hasanyrole('Orang Tua')
    <li class="nav-item {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('wali.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item {{ request()->routeIs('ortu.parenting.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('ortu.parenting.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Ruang Edukasi Orang Tua</span>
        </a>
    </li>
    @endhasanyrole

    @hasanyrole('Komite')
    <li class="nav-item {{ request()->routeIs('keuangan-komite.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('keuangan-komite.index') }}">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>Keuangan Komite</span>
        </a>
    </li>
    @endhasanyrole


    @hasanyrole('Super Admin|Kepala Sekolah|Guru')
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Navigasi Menu
    </div>

    <li class="nav-item {{ request()->routeIs('tahun-ajaran.*', 'kelas.*', 'guru.*', 'siswa.*', 'alumni.*') ? 'active' : '' }}">
        <!-- Hapus pengecekan kondisi dan jadikan selalu 'collapsed', serta aria-expanded="false" -->
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false" aria-controls="collapseMaster">
            <i class="fas fa-fw fa-folder"></i>
            <span>Data Master</span>
        </a>
        <!-- Hapus pengecekan kondisi ? 'show' : '' -->
        <div id="collapseMaster" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('tahun-ajaran.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('tahun-ajaran.index') }}">Tahun Ajaran</a>
                <a class="collapse-item {{ request()->routeIs('kelas.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('kelas.index') }}">Data Kelas</a>
                <a class="collapse-item {{ request()->routeIs('guru.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('guru.index') }}">Data Guru (GTK)</a>
                <a class="collapse-item {{ request()->routeIs('siswa.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('siswa.index') }}">Data Siswa</a>
                <a class="collapse-item {{ request()->routeIs('alumni.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('master.alumni.index') }}">Data Alumni</a>
            </div>
        </div>
    </li>


    <li class="nav-item {{ request()->routeIs('akademik.*', 'absensi.*', 'kenaikan-kelas.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('akademik.*', 'absensi.*', 'kenaikan-kelas.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseAkademik" aria-expanded="true" aria-controls="collapseAkademik">
            <i class="fas fa-fw fa-book-open"></i>
            <span>Akademik</span>
        </a>
        <div id="collapseAkademik" class="collapse {{ request()->routeIs('akademik.*', 'absensi.*', 'kenaikan-kelas.*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('akademik.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('master.akademik.index') }}">Data Akademik</a>
                <a class="collapse-item {{ request()->routeIs('absensi.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('master.absensi.index') }}">Absensi Siswa</a>
                <a class="collapse-item {{ request()->routeIs('kenaikan-kelas.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('master.kenaikan-kelas.index') }}">Kenaikan Kelas</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('kategori.*', 'transaksi.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('kategori.*', 'transaksi.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseKeuangan" aria-expanded="true" aria-controls="collapseKeuangan">
            <i class="fas fa-fw fa-wallet"></i>
            <span>Keuangan</span>
        </a>
        <div id="collapseKeuangan" class="collapse {{ request()->routeIs('kategori.*', 'transaksi.*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('kategori.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('keuangan.kategori.index') }}">Kategori Transaksi</a>
                <a class="collapse-item {{ request()->routeIs('transaksi.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('keuangan.transaksi.index') }}">Buku Kas</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('kategori_barang.*', 'ruangan.*', 'pencatatan.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('kategori_barang.*', 'ruangan.*', 'pencatatan.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseInventaris" aria-expanded="true" aria-controls="collapseInventaris">
            <i class="fas fa-fw fa-wallet"></i>
            <span>Inventaris</span>
        </a>
        <div id="collapseInventaris" class="collapse {{ request()->routeIs('kategori_barang.*', 'ruangan.*', 'pencatatan.*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('kategori_barang.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('inventaris.kategori_barang.index') }}">Kategori Barang</a>
                <a class="collapse-item {{ request()->routeIs('ruangan.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('inventaris.ruangan.index') }}">Ruangan</a>
                <a class="collapse-item {{ request()->routeIs('pencatatan.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('inventaris.pencatatan.index') }}">Pendataan</a>
                <a class="collapse-item {{ request()->routeIs('peminjaman.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('inventaris.peminjaman.index') }}">Peminjaman</a>
            </div>
        </div>
    </li>

    
    <li class="nav-item {{ request()->routeIs('erapor.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erapor.index') }}">
            <i class="fas fa-fw fa-award"></i>
            <span>E-Rapor</span>
        </a>
    </li>


    <li class="nav-item {{ request()->routeIs('berita.*', 'admin.parenting.*') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('berita.*', 'admin.parenting.*') ? '' : 'collapsed' }}"
           href="#" data-toggle="collapse" data-target="#collapseKonten" aria-expanded="true" aria-controls="collapseKonten">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Kelola Konten</span>
        </a>
        <div id="collapseKonten" class="collapse {{ request()->routeIs('berita.*', 'admin.parenting.*') ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('berita.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('berita.index') }}">Berita Publik</a>
                <a class="collapse-item {{ request()->routeIs('admin.parenting.*') ? 'active font-weight-bold text-success' : '' }}" href="{{ route('admin.parenting.index') }}">Ruang Edukasi Ortu</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ request()->routeIs('absensi_guru.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('absensi_guru.index') }}">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Absensi Guru</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('ppdb.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('ppdb.index') }}">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>PPDB</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('keuangan-komite.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('keuangan-komite.index') }}">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>Keuangan Komite</span>
        </a>
    </li>

    @endhasanyrole

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
