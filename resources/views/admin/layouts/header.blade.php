<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Unique</title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="html dashboard,dashboard template,admin,admin panel template, html and css template,css template, dashboard,html and css,template dashboard,admin theme,admin dashboard template,admin template,html css templates,bootstrap dashboard,bootstrap admin">

    <!-- <link rel="icon" href="{{asset('admin/images/brand-logos/favicon.ico')}}" type="image/x-icon"> -->

    <script src="{{asset('admin/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>

    <script src="{{asset('admin/js/main.js')}}"></script>

    <link id="style" href="{{asset('admin/libs/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/styles.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/icons.css')}}" rel="stylesheet">

    <link href="{{asset('admin/libs/node-waves/waves.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin/libs/simplebar/simplebar.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('admin/libs/swiper/swiper-bundle.min.css')}}">

    <link rel="stylesheet" href="{{asset('admin/libs/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/libs/@simonwep/pickr/themes/nano.min.css')}}">

    <link rel="stylesheet" href="{{asset('admin/libs/choices.js/public/assets/styles/choices.min.css')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />


</head>

<body>
    <style>
        body {
            overflow-x: hidden;
        }
    </style>

    <!-- Start Switcher -->
    <!-- <div class="offcanvas offcanvas-end" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title text-default" id="offcanvasRightLabel">Switcher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="border-bottom border-block-end-dashed">
                <div class="nav nav-tabs nav-justified" id="switcher-main-tab" role="tablist">
                    <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home"
                        type="button" role="tab" aria-controls="switcher-home" aria-selected="true">Theme Styles</button>
                    <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile"
                        type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">Theme Colors</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab"
                    tabindex="0">
                    <div class="">
                        <p class="switcher-style-head">Theme Color Mode:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-light-theme">
                                        Light
                                    </label>
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-dark-theme">
                                        Dark
                                    </label>
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Directions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-ltr">
                                        LTR
                                    </label>
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-ltr" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-rtl">
                                        RTL
                                    </label>
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-rtl">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Navigation Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-vertical">
                                        Vertical
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-style" id="switcher-vertical"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-horizontal">
                                        Horizontal
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-style"
                                        id="switcher-horizontal">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="navigation-menu-styles">
                        <p class="switcher-style-head">Vertical & Horizontal Menu Styles:</p>
                        <div class="row switcher-style gx-0 pb-2 gy-2">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-click">
                                        Menu Click
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-menu-click">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-hover">
                                        Menu Hover
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-menu-hover">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-click">
                                        Icon Click
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-icon-click">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-hover">
                                        Icon Hover
                                    </label>
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles"
                                        id="switcher-icon-hover">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidemenu-layout-styles">
                        <p class="switcher-style-head">Sidemenu Layout Styles:</p>
                        <div class="row switcher-style gx-0 pb-2 gy-2">
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-default-menu">
                                        Default Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-default-menu" checked>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-closed-menu">
                                        Closed Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-closed-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icontext-menu">
                                        Icon Text
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-icontext-menu">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-icon-overlay">
                                        Icon Overlay
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-icon-overlay">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-detached">
                                        Detached
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-detached">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-double-menu">
                                        Double Menu
                                    </label>
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles"
                                        id="switcher-double-menu">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Page Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-regular">
                                        Regular
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-regular"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-classic">
                                        Classic
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-classic">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-modern">
                                        Modern
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-modern">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Layout Width Styles:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-full-width">
                                        Full Width
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-full-width"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-boxed">
                                        Boxed
                                    </label>
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-boxed">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Menu Positions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-fixed">
                                        Fixed
                                    </label>
                                    <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-fixed"
                                        checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-menu-scroll">
                                        Scrollable
                                    </label>
                                    <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-scroll">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Header Positions:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-header-fixed">
                                        Fixed
                                    </label>
                                    <input class="form-check-input" type="radio" name="header-positions"
                                        id="switcher-header-fixed" checked>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-header-scroll">
                                        Scrollable
                                    </label>
                                    <input class="form-check-input" type="radio" name="header-positions"
                                        id="switcher-header-scroll">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <p class="switcher-style-head">Loader:</p>
                        <div class="row switcher-style gx-0">
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-enable">
                                        Enable
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-loader"
                                        id="switcher-loader-enable">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select">
                                    <label class="form-check-label" for="switcher-loader-disable">
                                        Disable
                                    </label>
                                    <input class="form-check-input" type="radio" name="page-loader"
                                        id="switcher-loader-disable" checked>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                    <div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Menu Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Light Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-light" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Dark Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-dark" >
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Color Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Gradient Menu" type="radio" name="menu-colors"
                                        id="switcher-menu-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Transparent Menu"
                                        type="radio" name="menu-colors" id="switcher-menu-transparent">
                                </div>
                            </div>
                            <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Menu dynamically change from below Theme Primary color picker</div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Header Colors:</p>
                            <div class="d-flex switcher-style pb-2">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Light Header" type="radio" name="header-colors"
                                        id="switcher-header-light" checked>
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Dark Header" type="radio" name="header-colors"
                                        id="switcher-header-dark">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Color Header" type="radio" name="header-colors"
                                        id="switcher-header-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Gradient Header" type="radio" name="header-colors"
                                        id="switcher-header-gradient">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Transparent Header" type="radio" name="header-colors"
                                        id="switcher-header-transparent">
                                </div>
                            </div>
                            <div class="px-4 pb-3 text-muted fs-11">Note:If you want to change color Header dynamically change from below Theme Primary color picker</div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Theme Primary:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-1" type="radio"
                                        name="theme-primary" id="switcher-primary">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-2" type="radio"
                                        name="theme-primary" id="switcher-primary1">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-3" type="radio" name="theme-primary"
                                        id="switcher-primary2">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-4" type="radio" name="theme-primary"
                                        id="switcher-primary3">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-primary-5" type="radio" name="theme-primary"
                                        id="switcher-primary4">
                                </div>
                                <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                    <div class="theme-container-primary"></div>
                                    <div class="pickr-container-primary"></div>
                                </div>
                            </div>
                        </div>
                        <div class="theme-colors">
                            <p class="switcher-style-head">Theme Background:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-1" type="radio"
                                        name="theme-background" id="switcher-background">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-2" type="radio"
                                        name="theme-background" id="switcher-background1">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-3" type="radio" name="theme-background"
                                        id="switcher-background2">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-4" type="radio"
                                        name="theme-background" id="switcher-background3">
                                </div>
                                <div class="form-check switch-select me-3">
                                    <input class="form-check-input color-input color-bg-5" type="radio"
                                        name="theme-background" id="switcher-background4">
                                </div>
                                <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                    <div class="theme-container-background"></div>
                                    <div class="pickr-container-background"></div>
                                </div>
                            </div>
                        </div>
                        <div class="menu-image mb-3">
                            <p class="switcher-style-head">Menu With Background Image:</p>
                            <div class="d-flex flex-wrap align-items-center switcher-style">
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img1" type="radio"
                                        name="theme-bg" id="switcher-bg-img">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img2" type="radio"
                                        name="theme-bg" id="switcher-bg-img1">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img3" type="radio" name="theme-bg"
                                        id="switcher-bg-img2">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img4" type="radio"
                                        name="theme-bg" id="switcher-bg-img3">
                                </div>
                                <div class="form-check switch-select m-2">
                                    <input class="form-check-input bgimage-input bg-img5" type="radio"
                                        name="theme-bg" id="switcher-bg-img4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid canvas-footer"> 
                    <a href="javascript:void(0);" id="reset-all" class="btn btn-danger m-1">Reset</a> 
                </div>
            </div>
        </div>
    </div> -->

    <div id="loader">
        <img src="{{asset('admin/images/media/loader.svg')}}" alt="">
    </div>

    <div class="page">
        <header class="app-header">

            <div class="main-header-container container-fluid">

                <div class="header-content-left">

                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="index.html" class="header-logo">
                                <img src="{{asset('admin/images/brand-logos/desktop-logo.png')}}" alt="logo" class="desktop-logo">
                                <img src="{{asset('admin/images/brand-logos/toggle-logo.png')}}" alt="logo" class="toggle-logo">
                                <img src="{{asset('admin/images/brand-logos/desktop-dark.png')}}" alt="logo" class="desktop-dark">
                                <img src="{{asset('admin/images/brand-logos/toggle-dark.png')}}" alt="logo" class="toggle-dark">
                                <img src="{{asset('admin/images/brand-logos/desktop-white.png')}}" alt="logo" class="desktop-white">
                                <img src="{{asset('admin/images/brand-logos/toggle-white.png')}}" alt="logo" class="toggle-white">
                            </a>
                        </div>
                    </div>
                    <div class="header-element">
                        <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>

                        <div class="main-header-center header-link search-bar-styles">
                            {{--
                            <input type="text" class="form-control" id="typehead" placeholder="Search for results..."
                                autocomplete="off">
                            <button type="button"  aria-label="button" class="btn pe-1"><i class="fe fe-search" aria-hidden="true"></i></button>
                        --}}

                            <div id="headersearch" class="header-search">
                                <div class="p-3">
                                    <div class="">
                                        <p class="fw-semibold text-muted mb-2 fs-13">Recent Searches</p>
                                        <div class="ps-2">
                                            <a href="javascript:void(0)" class="search-tags"><i class="fe fe-search me-2"></i>People<span></span></a>
                                            <a href="javascript:void(0)" class="search-tags"><i class="fe fe-search me-2"></i>Pages<span></span></a>
                                            <a href="javascript:void(0)" class="search-tags"><i class="fe fe-search me-2"></i>Articles<span></span></a>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <p class="fw-semibold text-muted mb-2 fs-13">Apps and pages</p>
                                        <ul class="ps-2">
                                            <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                                <a href="full-calendar.html"><span><i class="bx bx-calendar me-2 fs-14 bg-primary-transparent p-2 rounded-circle"></i>Calendar</span></a>
                                            </li>
                                            <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                                <a href="mail.html"><span><i class="bx bx-envelope me-2 fs-14 bg-primary-transparent p-2 rounded-circle"></i>Mail</span></a>
                                            </li>
                                            <li class="p-1 d-flex align-items-center text-muted mb-2 search-app">
                                                <a href="buttons.html"><span><i class="bx bx-dice-1 me-2 fs-14 bg-primary-transparent p-2 rounded-circle"></i>Buttons</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mt-3">
                                        <p class="fw-semibold text-muted mb-2 fs-13">Links</p>
                                        <ul class="ps-2 list-unstyled">
                                            <li class="p-1 align-items-center text-muted mb-1 search-app">
                                                <a href="javascript:void(0)" class="text-primary"><u>http://spruko/spruko.com</u></a>
                                            </li>
                                            <li class="p-1 align-items-center text-muted mb-1 search-app">
                                                <a href="javascript:void(0)" class="text-primary"><u>http://spruko/spruko.com</u></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="py-3 border-top px-0">
                                    <div class="text-center">
                                        <a href="javascript:void(0)" class="text-primary text-decoration-underline fs-15">View all</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="header-content-right">
                    <div class="header-element header-search d-lg-none d-block ">
                        <a aria-label="anchor" href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <i class="bx bx-search-alt-2 header-link-icon"></i>
                        </a>
                    </div>

                    <div class="header-element header-theme-mode">
                        <a href="javascript:void(0);" class="header-link layout-setting">
                            <span class="light-layout">
                                <i class="ri-moon-clear-line header-link-icon"></i>
                            </span>
                            <span class="dark-layout">
                                <i class="ri-sun-line header-link-icon"></i>
                            </span>
                        </a>
                    </div>


                    <div class="header-element header-fullscreen">
                        <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                            <i class="ri-fullscreen-line full-screen-open header-link-icon"></i>
                            <i class="ri-fullscreen-exit-line full-screen-close header-link-icon d-none"></i>
                        </a>
                    </div>

                    <div class="header-element">
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="me-sm-2 me-0">
                                    <img src="{{asset('admin/images/faces/9.jpg')}}" alt="img" width="32" height="32" class="rounded-circle">
                                </div>
                                <div class="d-sm-block d-none">
                                    <p class="fw-semibold mb-0 lh-1">Alison</p>
                                    <span class="op-7 fw-normal d-block fs-12">Administrator</span>
                                </div>
                            </div>
                        </a>
                        <ul class="main-header-dropdown dropdown-menu overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                            <li><a class="dropdown-item d-flex" href="profile.html"><i class="ti ti-user-circle fs-18 me-2 op-7"></i>Profile</a></li>

                            <li><a class="dropdown-item d-flex" href="{{route('admin.logout')}}"><i class="ti ti-logout fs-18 me-2 op-7"></i>Log Out</a></li>
                        </ul>
                    </div>
                    <div class="header-element">
                        <a href="javascript:void(0);" class="header-link switcher-icon settings-icon-header" data-bs-toggle="offcanvas" data-bs-target="#switcher-canvas">
                            <i class="bx bx-cog header-link-icon"></i>
                        </a>
                    </div>

                </div>

            </div>
        </header>
        <aside class="app-sidebar sticky" id="sidebar">

            <div class="main-sidebar-header">
                <a href="index.html" class="header-logo">
                    GTP
                </a>
            </div>
            <div class="main-sidebar" id="sidebar-scroll">

                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                        </svg>
                    </div>
                    <ul class="main-menu">

                        <li class="slide has-sub">
                            <a href="{{route('admin.dashboard')}}" class="side-menu__item">
                                <i class="ri-home-4-line side-menu__icon"></i>
                                <span class="side-menu__label"> My Dashboard</span>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide side-menu__label1">
                                    <a href="{{route('admin.dashboard')}}">My Dashboard</a>
                                </li>
                            </ul>
                        </li>

                        @can('Franchise List')
                        <li class="slide">
                            <a href="{{route('admin.franchises.index')}}" class="side-menu__item">
                                <i class="ri-store-line side-menu__icon"></i>
                                <span class="side-menu__label">Franchise Management</span>
                            </a>
                        </li>
                        @endcan

                        @can('Customer List')
                        <li class="slide">
                            <a href="{{route('admin.customers.index')}}" class="side-menu__item">
                                <i class="ri-user-settings-line side-menu__icon"></i> {{-- Using a generic user settings icon for now --}}
                                <span class="side-menu__label">Customer Management</span>
                            </a>
                        </li>
                        @endcan

                        @can('KYC List')
                        <li class="slide">
                            <a href="{{route('admin.kycs.index')}}" class="side-menu__item">
                                <i class="ri-file-user-line side-menu__icon"></i> {{-- Using a file-user icon for KYC --}}
                                <span class="side-menu__label">KYC Management</span>
                            </a>
                        </li>
                        {{-- Debugging line for KYC List permission --}}
                        @endcan
                        @can('Deposit List')
                        <li class="slide">
                            <a href="{{route('admin.deposits.index')}}" class="side-menu__item">
                                <i class="ri-file-user-line side-menu__icon"></i>
                                <span class="side-menu__label">Deposit Management</span>
                            </a>
                        </li>
                        @endcan
                        @can('Withdraw List')
                        <li class="slide">
                            <a href="{{route('admin.withdraws.index')}}" class="side-menu__item">
                                <i class="ri-file-user-line side-menu__icon"></i>
                                <span class="side-menu__label">Withdraw Management</span>
                            </a>
                        </li>
                        @endcan
                        @can('Order List')
                        <li class="slide">
                            <a href="{{route('admin.orders.index')}}" class="side-menu__item">
                                <i class="ri-file-user-line side-menu__icon"></i>
                                <span class="side-menu__label">Order Management</span>
                            </a>
                        </li>
                        @endcan
                        @can('Transaction List')
                        <li class="slide">
                            <a href="{{route('admin.transactions.index')}}" class="side-menu__item">
                                <i class="ri-file-user-line side-menu__icon"></i>
                                <span class="side-menu__label">Transaction Management</span>
                            </a>
                        </li>
                        @endcan

                        @can('Settings')
                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <i class="ri-settings-3-line side-menu__icon"></i>
                                <span class="side-menu__label">Site Setting</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide side-menu__label1">
                                    <a href="javascript:void(0)">Site Settings</a>
                                </li>
                                @can('Main Settings')
                                <li class="slide">
                                    <a href="{{route('admin.settings.main')}}" class="side-menu__item"> Main Settings</a>
                                </li>
                                @endcan
                                @can('Preference Settings')
                                <li class="slide">
                                    <a href="{{route('admin.settings.preference')}}" class="side-menu__item"> Preference Settings</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        <li class="slide has-sub">
                            <a href="javascript:void(0);" class="side-menu__item">
                                <i class="ri-settings-3-line side-menu__icon"></i>
                                <span class="side-menu__label">Setting Panel</span>
                                <i class="fe fe-chevron-right side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide side-menu__label1">
                                    <a href="javascript:void(0)">Setting Panel</a>
                                </li>
                                @can('User List')
                                <li class="slide">
                                    <a href="{{route('admin.users.index')}}" class="side-menu__item"> Manage User's</a>
                                </li>
                                @endcan


                                @can('role-list')
                                <li class="slide">
                                    <a href="{{route('roles.index')}}" class="side-menu__item"> Role Managment</a>
                                </li>
                                @endcan
                                @can('Permission List')
                                <li class="slide">
                                    <a href="{{route('admin.permissions.index')}}" class="side-menu__item"> Permission Managment</a>
                                </li>
                                @endcan

                                <li class="slide">
                                    <a href="{{route('admin.change-password.index')}}" class="side-menu__item"> Change Password</a>
                                </li>


                            </ul>
                        </li>


                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                        </svg></div>
                </nav>

            </div>
        </aside>