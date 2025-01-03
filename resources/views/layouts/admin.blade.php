<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>@yield('title')</title>

    <!-- Normalize V8.0.1 -->
    <link rel="stylesheet" href="{{ asset('/css/normalize.css') }}">

    <!-- Bootstrap V4.3 -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">

    <!-- Bootstrap Material Design V4.0 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-material-design.min.css') }}">

    <!-- Font Awesome V5.9.0 -->
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">

    <!-- Sweet Alerts V8.13.0 CSS file -->
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    <!-- Sweet Alert V8.13.0 JS file-->
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>

    <!-- jQuery Custom Content Scroller V3.1.5 -->
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.css') }}">

    <!-- General Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


</head>

<body>

    <!-- Main container -->
    <main class="full-box main-container">
        <!-- Nav lateral -->
        <section class="full-box nav-lateral">
            <div class="full-box nav-lateral-bg show-nav-lateral"></div>
            <div class="full-box nav-lateral-content">
                <figure class="full-box nav-lateral-avatar">
                    <i class="far fa-times-circle show-nav-lateral"></i>
                    <img src="./assets/avatar/Avatar.png" class="img-fluid" alt="Avatar">
                    <figcaption class="roboto-medium text-center">
                        Carlos Chaucanes <br><small class="roboto-condensed-light">Cajero</small>
                    </figcaption>
                </figure>
                <div class="full-box nav-lateral-bar"></div>
                <nav class="full-box nav-lateral-menu">
                    <ul>
                        <li>
                            <a href="home.html"><i class="fab fa-dashcube fa-fw"></i> &nbsp; SISTEMA DE VENTAS POST</a>
                        </li>
                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp; Usuarios
                                <i class="fas fa-chevron-down"></i></a>
                            <ul>
                                <li>
                                    <a href="user-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo usuario</a>
                                </li>
                                <li>
                                    <a href="user-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de
                                        usuarios</a>
                                </li>
                                <li>
                                    <a href="user-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar
                                        usuario</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fas  fa-user-secret fa-fw"></i> &nbsp;
                                Clientes <i class="fas fa-chevron-down"></i></a>
                            <ul>
                                <li>
                                    <a href="client-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo cliente</a>
                                </li>
                                <li>
                                    <a href="client-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista
                                        de clientes</a>
                                </li>
                                <li>
                                    <a href="client-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar
                                        Cliente</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fas  fa-user-secret fa-fw"></i> &nbsp;
                                Empledos <i class="fas fa-chevron-down"></i></a>
                            <ul>
                                <li>
                                    <a href="empleyee-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo
                                        empleado</a>
                                </li>
                                <li>
                                    <a href="empleyee-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                                        Lista de empleados</a>
                                </li>
                                <li>
                                    <a href="empleyee-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar
                                        Empleado</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fas fa-home fa-fw"></i> &nbsp; Productos
                                <i class="fas fa-chevron-down"></i></a>
                            <ul>
                                <li>
                                    <a href="item-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo producto</a>
                                </li>
                                <li>
                                    <a href="item-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de
                                        productos</a>
                                </li>
                                <li>
                                    <a href="item-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Consulta de
                                        Productos</a>
                                </li>
                                <li>
                                    <a href="item-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Entradas</a>
                                </li>
                                <li>
                                    <a href="item-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Salidas</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fas fa-file-invoice-dollar fa-fw"></i>
                                &nbsp; Ventas <i class="fas fa-chevron-down"></i></a>
                            <ul>
                                <li>
                                    <a href="ventas-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Facturar</a>
                                </li>
                                <li>
                                    <a href="ventas-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                                        Devoluciones</a>
                                </li>
                                <li>
                                    <a href="ventas-search.html"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar
                                        Factura</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="nav-btn-submenu"><i class="fas fa-users fa-fw"></i> &nbsp;
                                Proveedores <i class="fas fa-chevron-down"></i></a>
                            <ul>
                                <li>
                                    <a href="proveedores-new.html"><i class="fas fa-plus fa-fw"></i> &nbsp; Nuevo</a>
                                </li>
                                <li>
                                    <a href="proveedores-list.html"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
                                        Lista de proveedores</a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
        <!-- Page content -->
        <section class="full-box page-content">
            <nav class="full-box navbar-info">
                <a href="#" class="float-left show-nav-lateral">
                    <i class="fas fa-exchange-alt"></i>
                </a>
                <a href="user-update.html">
                    <i class="fas fa-user-cog"></i>
                </a>
                <a href="#" class="btn-exit-system">
                    <i class="fas fa-power-off"></i>
                </a>
            </nav>

            <!-- Page header -->
            <div class="full-box page-header">
                <h3 class="text-left">
                    <i class="fab fa-dashcube fa-fw"></i> &nbsp; SIST EMA DE VENTAS POST
                </h3>
            </div>

            <!-- Content -->
            <div class="full-box tile-container">

                @yield('content')

            </div>


        </section>
    </main>


    <!--=============================================
 =            Include JavaScript files           =
 ==============================================-->
    <!-- jQuery V3.4.1 -->
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

    <!-- popper -->
    <script src="{{ asset('js/popper.min.js') }}"></script>

    <!-- Bootstrap V4.3 -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- jQuery Custom Content Scroller V3.1.5 -->
    <script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>

    <!-- Bootstrap Material Design V4.0 -->
    <script src="{{ asset('js/bootstrap-material-design.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').bootstrapMaterialDesign();
        });
    </script>

    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
