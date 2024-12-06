<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <title>Eilinger Stiftung - Dashboard</title>

    @vite(['resources/js/app.js', 'resources/sass/dashboard.scss'])
    @livewireStyles
</head>

<body data-sidebar="dark" data-layout-mode="light">
    <div id="layout-wrapper">
        @include('components.layout.topbar')
        @include('components.layout.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <script>
        let sidebar = document.querySelector("#sidebar-menu");
        let closeBtn = document.querySelector("#vertical-menu-btn");
        let body = document.body;

        closeBtn.addEventListener("click", function(event) {
            event.preventDefault();
            body.classList.toggle('sidebar-enable');
            if (window.screen.availWidth >= 992) {
                body.classList.toggle('vertical-collpsed');
            } else {
                body.classList.remove('vertical-collpsed');
            }
        });
    </script>
    @livewireScripts


</body>

</html>
