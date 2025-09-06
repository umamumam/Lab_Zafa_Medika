<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="white">
            <a href="/dashboard" class="logo">
                <img src="{{ asset('Logo2.png') }}" alt="navbar brand" class="navbar-brand" height="70" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Main Menu</h4>
                </li>
                <li class="nav-item">
                    <a href="/dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#registrasi" class="collapsed" aria-expanded="false">
                        <i class="fas fa-user-plus"></i>
                        <p>Registrasi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="registrasi">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/pasiens">
                                    <span class="sub-item">Pasien</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#order" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-flask-vial"></i>
                        <p>Order Laboratorium</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="order">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/visits">
                                    <span class="sub-item">Laboratorium Order</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#bayar" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-wallet"></i>
                        <p>Pembayaran</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="bayar">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/visits/bayar">
                                    <span class="sub-item">Daftar Pembayaran</span>
                                </a>
                            </li>
                            {{-- <li>
                                <a href="/laporan-pembayaran">
                                    <span class="sub-item">Laporan Pembayaran</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>
                @auth
                @if(Auth::user()->role == 'Admin' || Auth::user()->role == 'Petugas')
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sampel" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-microscope"></i>
                        <p>Pengambilan Sampel</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sampel">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/visits/sampling">
                                    <span class="sub-item">Pengambilan Sampel</span>
                                </a>
                            </li>
                            <li>
                                <a href="/visits/barcode">
                                    <span class="sub-item">Cetak Barcode Sampel</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#pemeriksaan" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-stethoscope"></i>
                        <p>Pemeriksaan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="pemeriksaan">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/visits/pemeriksaan">
                                    <span class="sub-item">Semua</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/hematologi">
                                    <span class="sub-item">Hematologi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/kimiaklinik">
                                    <span class="sub-item">Kimia Klinik</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/imunologiserologi">
                                    <span class="sub-item">Imunologi Serologi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/mikrobiologi">
                                    <span class="sub-item">Mikrobiologi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/khusus">
                                    <span class="sub-item">Khusus</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/lainnya">
                                    <span class="sub-item">Lainnya</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pemeriksaan/paket">
                                    <span class="sub-item">Paket & MCU</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @endauth
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#review" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-laptop-medical"></i>
                        <p>Review Hasil</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="review">
                        <ul class="nav nav-collapse">
                            @auth
                            @if(Auth::user()->role == 'Admin' || Auth::user()->role == 'Petugas')
                            <li>
                                <a href="/visits/validasi">
                                    <span class="sub-item">Validasi Hasil</span>
                                </a>
                            </li>
                            @endif
                            @endauth
                            <li>
                                <a href="/visits/cetak">
                                    <span class="sub-item">Cetak Hasil</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Generator</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#surat" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-file-contract"></i>
                        <p>Surat Keterangan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="surat">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/">
                                    <span class="sub-item">Surat Bebas Narkoba</span>
                                </a>
                            </li>
                            <li>
                                <a href="/">
                                    <span class="sub-item">Surat Antigen Covid-19</span>
                                </a>
                            </li>
                            <li>
                                <a href="/">
                                    <span class="sub-item">Kartu Golongan Darah</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @auth
                @if(Auth::user()->role == 'Admin' || Auth::user()->role == 'Kasir')
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Laporan</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#laporan" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-chart-line"></i>
                        <p>Laporan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="laporan">
                        <ul class="nav nav-collapse">
                            @auth
                            @if(Auth::user()->role == 'Admin')
                            <li>
                                <a href="/laporan-pembayaran">
                                    <span class="sub-item">Transaksi</span>
                                </a>
                            </li>
                            <li>
                                <a href="/visits/laporan-tahunan">
                                    <span class="sub-item">Pemeriksaan</span>
                                </a>
                            </li>
                            @endif
                            @endauth
                            <li>
                                <a href="/laporan-kasir-harian" target="blank">
                                    <span class="sub-item">Billing Kasir All</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @endauth

                @auth
                @if(Auth::user()->role == 'Admin')
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengaturan</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#master" class="collapsed" aria-expanded="false">
                        <i class="fa-solid fa-server"></i>
                        <p>Data Master</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="master">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/users">
                                    <span class="sub-item">Master Petugas</span>
                                </a>
                            </li>
                            <li>
                                <a href="/dokters">
                                    <span class="sub-item">Master Dokter</span>
                                </a>
                            </li>
                            <li>
                                <a href="/ruangans">
                                    <span class="sub-item">Master Ruangan</span>
                                </a>
                            </li>
                            <li>
                                <a href="/tests">
                                    <span class="sub-item">Master Test</span>
                                </a>
                            </li>
                            <li>
                                <a href="/detail_tests">
                                    <span class="sub-item">Master Detail Test</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pakets">
                                    <span class="sub-item">Master Paket</span>
                                </a>
                            </li>
                            <li>
                                <a href="/vouchers">
                                    <span class="sub-item">Master Voucher</span>
                                </a>
                            </li>
                            <li>
                                <a href="/metodebyrs">
                                    <span class="sub-item">Metode Pembayaran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @endauth
            </ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setActiveMenu() {
            const currentPath = window.location.pathname;
            const menuLinks = document.querySelectorAll('.sidebar-content a[href]');

            menuLinks.forEach(link => {
                const linkPath = link.getAttribute('href');
                if (linkPath === currentPath) {
                    link.classList.add('active');
                    let parentLi = link.closest('li');
                    while (parentLi) {
                        parentLi.classList.add('active');
                        const parentCollapseLink = parentLi.querySelector('[data-bs-toggle="collapse"]');
                        if (parentCollapseLink) {
                            const targetId = parentCollapseLink.getAttribute('href');
                            const targetCollapse = document.querySelector(targetId);
                            parentCollapseLink.classList.remove('collapsed');
                            parentCollapseLink.setAttribute('aria-expanded', 'true');
                            targetCollapse.classList.add('show');
                        }
                        parentLi = parentLi.parentElement.closest('li');
                    }
                }
            });
        }
        setActiveMenu();
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                const targetCollapse = document.querySelector(targetId);
                const isShown = targetCollapse.classList.contains('show');
                if (!isShown) {
                    document.querySelectorAll('.collapse.show').forEach(openCollapse => {
                        if (openCollapse.id !== targetId.substring(1)) {
                            const openToggle = document.querySelector(`[href="#${openCollapse.id}"]`);
                            openCollapse.classList.remove('show');
                            if (openToggle) {
                                openToggle.classList.add('collapsed');
                                openToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });
                }
                if (isShown) {
                    this.classList.add('collapsed');
                    this.setAttribute('aria-expanded', 'false');
                    targetCollapse.classList.remove('show');
                } else {
                    this.classList.remove('collapsed');
                    this.setAttribute('aria-expanded', 'true');
                    targetCollapse.classList.add('show');
                }
            });
        });
    });
</script>
