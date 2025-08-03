<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistemas POS</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="/pointofsale/public/css/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="/pointofsale/public/css/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="/pointofsale/public/css/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/pointofsale/public/css/adminlte.css" />
    <link rel="stylesheet" href="/pointofsale/public/css/bootstrap-select.min.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link rel="stylesheet" href="/pointofsale/public/css/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <!-- jsvectormap -->
    <link rel="stylesheet" href="/pointofsale/public/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />

    <style>
        video {
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
        }
        canvas {
            display: none;
        }
    </style>
    


</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
                    <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <!--end::Start Navbar Links-->
                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Navbar Search-->
                    <li class="nav-item">
                        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <!--end::Navbar Search-->
                    <!--begin::Messages Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="/pointofsale/public/assets/img/user1-128x128.jpg" alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-end fs-7 text-danger"><i
                                                    class="bi bi-star-fill"></i></span>
                                        </h3>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="/pointofsale/public/assets/img/user8-128x128.jpg" alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            John Pierce
                                            <span class="float-end fs-7 text-secondary">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">I got your message bro</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="/pointofsale/public/assets/img/user3-128x128.jpg" alt="User Avatar"
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="float-end fs-7 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </h3>
                                        <p class="fs-7">The subject goes here</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <!--end::Messages Dropdown Menu-->
                    <!--begin::Notifications Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-envelope me-2"></i> 4 new messages
                                <span class="float-end text-secondary fs-7">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                                <span class="float-end text-secondary fs-7">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                <span class="float-end text-secondary fs-7">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                        </div>
                    </li>
                    <!--end::Notifications Dropdown Menu-->
                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->
                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            
                            <span class="d-none d-md-inline bi bi-person fs-5">{{Auth::user()->name}}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <img src="/pointofsale/public/assets/img/user.png"
                                    class="rounded-circle shadow" alt="User Image" />
                                <p>
                                    {{Auth::user()->name}} - 
                                    @switch(Auth::user()->role)
                                        @case("superadmin")
                                            Superadmin
                                            @break
                                        @case("admin")
                                            Administrador
                                            @break
                                        @case("cashier")
                                            Cajero
                                            @break
                                        $@default
                                            Usuario
                                    @endswitch
                                    <small>{{date("Y-m-d")}}</small>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Body-->
                            <li class="user-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-4 text-center"><a href="{{url('segurity/user')}}">Usuarios</a></div>
                                    <div class="col-4 text-center"><a href="{{url('sale/sale')}}">Ventas</a></div>
                                    <div class="col-4 text-center"><a href="{{url('sale/customer')}}">Clientes</a></div>
                                </div>
                                <!--end::Row-->
                            </li>
                            <!--end::Menu Body-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <a href="{{route('user.edit',Auth::user()->id)}}" class="btn btn-default btn-flat">Perfil</a>
                                <a href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-default btn-flat float-end">Salir</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="./index.html" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="/pointofsale/public/assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">SISTEMA POS</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="bi bi-house-door"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="bi bi-box-seam"></i>
                                <p>Almacen</p>
                                 <i class="nav-arrow bi bi-chevron-right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ms-3">
                                    <a href="{{ route('product.index') }}" class="nav-link">
                                        <i class="bi bi-box"></i>
                                        <p>Productos</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{ route('inventory.index') }}" class="nav-link">
                                        <i class="bi bi-clipboard-data"></i>
                                        <p>inventario</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{ route('category.index') }}" class="nav-link">
                                        <i class="bi bi-collection"></i>
                                        <p>Categorías</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a href="#" class="nav-link">
                                <i class="bi bi-bag"></i>
                                <p>Compras</p>
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ms-3">
                                    <a href="{{ route('income.index') }}" class="nav-link">
                                        <i class="bi bi-arrow-down-circle"></i>
                                        <p>Entradas</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{ route('supplier.index') }}" class="nav-link">
                                        <i class="bi bi-truck"></i>
                                        <p>Proveedores</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{ route('voucher.index') }}" class="nav-link">
                                        <i class="bi bi-file-text"></i>
                                        <p>Facturas</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{ route('purchase.startinventory') }}" class="nav-link">
                                        <i class="bi bi-eye"></i>
                                        <p>Factura Inicial</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-bag-check"></i>
                                <p>
                                    Ventas
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ms-3">
                                    <a href="{{ route('customer.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-person"></i>
                                        <p>Clientes</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{route('sale.index')}}" class="nav-link">
                                        <i class="nav-icon bi bi-cart-check"></i>
                                        <p>Venta</p>
                                    </a>
                                </li>
                                <li class="nav-item ms-3">
                                    <a href="{{route('cash_opening.index')}}" class="nav-link">
                                        <i class="bi bi-door-open"></i> 
                                        <p>Apertura Caja</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>
                                    Usuarios
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ms-3">
                                    <a href="{{route('user.index')}}" class="nav-link">
                                        <i class="nav-icon bi bi-person-lines-fill"></i>
                                        <p>Lista</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-arrow-left-right"></i>
                                <p>
                                    Movimientos
                                     <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ms-3">
                                    <a href="{{route('movement.index')}}" class="nav-link">
                                        <i class="nav-icon bi-arrow-left-right"></i>
                                        <p>Lista</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-text"></i>
                                <p>
                                    Reporteria
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ms-3">
                                    <a href="./index.html" class="nav-link">
                                        <i class="nav-icon bi bi-calendar-day"></i>
                                        <p>Ventas por Dia</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    @yield('content')
                    @yield('content-modal')
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">sistemapos.com</a>.
            </strong>
            Todos los derechos reservados
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="/pointofsale/public/js/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->

>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="/pointofsale/public/js/adminlte.js"></script>
    <script src="/pointofsale/public/js/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="/pointofsale/public/js/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="/pointofsale/public/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    
    <script src="/pointofsale/public/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
    <script src="/pointofsale/public/js/bootstrap-select.min.js"></script>
    <script src="/pointofsale/public/js/sweetalert2@11.js"></script>
    <script src="/pointofsale/public/js/panzoom.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    @stack('scripts')

    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script src="/pointofsale/public/js/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
        crossorigin="anonymous"></script>
    <!-- sortablejs -->
    <script>
        const connectedSortables = document.querySelectorAll('.connectedSortable');
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: 'shared',
                handle: '.card-header',
            });
        });

        const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = 'move';
        });
    </script>
    <!-- apexcharts -->
    <script src="/pointofsale/public/js/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
        crossorigin="anonymous"></script>
    <script src="/pointofsale/public/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
        crossorigin="anonymous"></script>
    <script src="/pointofsale/public/js/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
    <!-- jsvectormap -->
    <div id="global-spinner-overlay" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);justify-content:center;align-items:center;">
    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
        <span class="visually-hidden">Cargando...</span>
    </div>
</div>
</body>
<!--end::Body-->

</html>

@stack('scripts')
<script>
    function showSpinner() {
        document.getElementById('global-spinner-overlay').style.display = 'flex';
    }
    function hideSpinner() {
        document.getElementById('global-spinner-overlay').style.display = 'none';
    }
    const formatCurrency = new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,

    });


    function generateInvoice(info_sale,detail_sale,info_payment){
        const information_customer = document.getElementById("information_customer");
        information_customer.innerHTML = `<small class="text-muted mb-custom">Cliente: <b>${info_sale.customer_name}</b></small><br>
                                            <small class="text-muted mb-custom">Documento: <b>${info_sale.document_type}:${info_sale.document_number}</b></small><br>
                                            <small class="text-muted mb-custom">Dirección: <b>${info_sale.customer_address}</b></small><br>
                                            <small class="text-muted mb-custom">Télefono:<b>${info_sale.customer_phone}</b></small>`;
        document.getElementById("info_form_payment").textContent = info_sale.payment_form;
        document.getElementById("sale_number").textContent = info_sale.id;
        document.getElementById("date_sale").textContent = `Fecha: ${info_sale.updated_at}`;
        const body_details = document.querySelector("#details table tbody");
        const foot_details = document.querySelector("#details table tfoot")
        var discountTotals  = 0;
        var subtotals = 0;
        for(let i=0;i<detail_sale.length;i++){
            const detail = detail_sale[i];
            discountTotals += parseFloat(detail.discount); 
            subtotals += (parseFloat(detail.sale_price) * parseFloat(detail.quantity)); 
            const tr = document.createElement("tr");
            tr.innerHTML = `<td>${detail.quantity}</td><td>${detail.article} ${detail.concentration} ${detail.presentation}</td><td>${detail.discount}</td><td>${formatCurrency.format(parseFloat(detail.sale_price * detail.quantity).toFixed(0))}</td>`;
            body_details.appendChild(tr);
        }

        document.getElementById('receipt_subtotal').textContent = formatCurrency.format(subtotals);
        document.getElementById('receipt_discount').textContent = formatCurrency.format(discountTotals);
        document.getElementById('receipt_tax').textContent =  formatCurrency.format(0);;
        document.getElementById('receipt_total').textContent = formatCurrency.format(info_sale.sale_total);
        document.getElementById('receipt_change').textContent = formatCurrency.format(info_sale.change);

        const table_form_payment = document.querySelector("#table_method_payment tbody");
        var received = 0;
        info_payment.forEach(ele=>{
                const tr_pay = document.createElement("tr");
                tr_pay.innerHTML = `
                <td>${ele.method}</td>
                <td>${ele.value}</td>
            `;
            received=received + parseFloat(ele.value);
            table_form_payment.appendChild(tr_pay);
        });
        document.getElementById('receipt_received').textContent = formatCurrency.format(received);
        document.getElementById("employee").textContent = info_sale.user_name;
    }


    function printDiv(divId){
        let contenido = document.getElementById(divId).innerHTML;
        let ventana = window.open('', '', 'height=600,width=800');
        ventana.document.write('<html><head><title>Imprimir</title>');
        ventana.document.write('<style>body{font-family:sans-serif; font-size:12px;}</style>');
        ventana.document.write('</head><body>');
        ventana.document.write(contenido);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.focus();
        ventana.print();
        ventana.close();
        setInterval(() => {
            window.location.reload();
        },4000);
    }

</script>
