<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
    data-theme="theme-default" data-assets-path="/" data-template="vertical-menu-template">
<!-- Mirrored from demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/dashboards-analytics.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Feb 2024 02:55:07 GMT -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="description"
        content="Most Powerful &amp; Comprehensive Bootstrap 5 HTML Admin Dashboard Template built for developers!" />
    <meta name="keywords" content="dashboard, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">

    <title>Redwhite Star | Primajasa</title>

    <!-- Favicon -->
    {{-- <link rel="icon" type="image/x-icon" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/favicon/favicon.ico" /> --}}

    <!-- ? PROD Only: Google Tag Manager (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    {{-- <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '../../../../www.googletagmanager.com/gtm5445.html?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5DDHKGP');</script> --}}
    <!-- End Google Tag Manager -->

    <!-- Favicon -->
    {{-- <link rel="icon" type="image/x-icon" href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/assets/img/favicon/favicon.ico" /> --}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seating.css') }}">


    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="/vendor/libs/select2/select2.css">
    <link rel="stylesheet" href="/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="/vendor/libs/spinkit/spinkit.css" />
    <link rel="stylesheet" href="/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="/vendor/libs/flatpickr/flatpickr.css">

    <!-- Helpers -->
    <script src="/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/js/config.js"></script>


    <style>
        .light-style .bootstrap-select .filter-option-inner-inner {
            color: #697a8d !important;
        }

        .loading {
            z-index: 99999;
            position: absolute;
            top: 0;
            left: -5px;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .loading-content {
            position: absolute;
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            top: 50%;
            left: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <script src="/vendor/libs/jquery/jquery.js"></script>
    <script src="/vendor/libs/jquery/jquery.min.js"></script>
    <section id="loading">
        <div id="loading-content"></div>
        {{-- <div class="loading-content sk-fold sk-primary ">
             <div class="sk-fold-cube"></div>
             <div class="sk-fold-cube"></div>
             <div class="sk-fold-cube"></div>
             <div class="sk-fold-cube"></div>
         </div> --}}
    </section>
    {{-- <div class="modal fade" id="modal_action" tabindex="-1" style="padding-left: 0px;" aria-modal="true" role="dialog" aria-hidden="true">
    </div> --}}

    <script>
        function showLoading() {
            document.querySelector('#loading').classList.add('loading');
            document.querySelector('#loading-content').classList.add('loading-content');
        }

        function hideLoading() {
            document.querySelector('#loading').classList.remove('loading');
            document.querySelector('#loading-content').classList.remove('loading-content');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content')
            }
        })

        function handleDelete(datatable, onSuccessAction) {
            $('#' + datatable).on('click', '.delete', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        handleAjax(this.href, 'delete').onSuccess(function(res) {
                            onSuccessAction && onSuccessAction(res)
                            // showToast(res.status, res.message)
                            window.LaravelDataTables[datatable].ajax.reload(null, false)
                        }, false).excute();
                    }
                })

            });
        }

        function handleAjax(url, method = 'get') {

            function onSuccess(cb, runDefault = true) {
                this.onSuccessCallback = cb
                this.runDefaultSuccessCallback = runDefault

                return this
            }

            function excute() {
                $.ajax({
                    url,
                    method,
                    beforeSend: function() {
                        showLoading()
                    },
                    complete: function() {
                        hideLoading(false)
                    },
                    success: (res) => {
                        if (this.runDefaultSuccessCallback) {
                            const modal = $('#modal_action');
                            modal.html(res);
                            modal.modal('show');
                        }

                        this.onSuccessCallback && this.onSuccessCallback(res)
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }

            function onError(cb) {
                this.onErrorCallback = cb
                return this
            }

            return {
                excute,
                onSuccess,
                runDefaultSuccessCallback: true
            }

        }
    </script>


    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">

        <div class="layout-container">

            {{-- Sidebar --}}
            @include('partials.sidebar')

            <!-- Layout container -->
            <div class="layout-page">
                @include('partials.navbar')

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->
                    <div class="modal " id="modal_action" tabindex="-1" style=" padding-left: 0px;"
                        aria-labelledby="largeModalLabel" aria-modal="true" role="dialog">
                    </div>

                    {{-- <div class="modal fade" id="modal_action" tabindex="-1" aria-labelledby="largeModalLabel"aria-hidden="true">
                    </div> --}}

                    @include('partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->

        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    {{-- <script src="/vendor/js/core.js"></script> --}}
    {{-- <script src="/vendor/libs/jquery/jquery.js"></script> --}}
    <script src="/vendor/libs/popper/popper.js"></script>
    <script src="/vendor/js/bootstrap.js"></script>
    <script src="/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/vendor/libs/hammer/hammer.js"></script>
    <script src="/vendor/libs/i18n/i18n.js"></script>
    <script src="/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/vendor/js/menu.js"></script>
    <script src="{{ asset('/vendor/libs/select2/select2.js') }}"></script>
    <script src="/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="/js/tables-datatables-advanced.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('/js/main.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Page JS -->
    <script src="/js/dashboards-analytics.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
</body>

</html>
