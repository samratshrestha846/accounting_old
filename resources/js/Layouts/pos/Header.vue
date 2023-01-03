<template>
    <div>
        <!-- Header -->
        <header id="header" class="header">
            <div class="container-fluid">
                <div class="h-wrap">
                    <div class="logo">
                        <a href="/"><img src="/img/logo1.png" alt="images"></a>
                    </div>
                    <div class="time desktop-only">
                        <span>Friday, 19 November 2021, 04:15:26 PM</span>
                    </div>
                    <div class="header-right desktop-only">
                        <div class="navigation">
                            <nav class="navbar navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                    <ul class="navbar-nav">
                                        <li class="nav-item active">
                                            <a class="nav-link" href="/">Home</a>
                                        </li>
                                        <li class="nav-item active">
                                            <a class="nav-link" href="#">POS Settings</a>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Sales
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <li><a class="dropdown-item" href="#">Sales</a></li>
                                                <li><a class="dropdown-item" href="/suspendedsale">Suspended Sales</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="header-btns">
                            <div class="main-btns">
                                <a href="#"><i class="las la-chart-line"></i> Today's Sales</a>
                                <a href="#"><i class="las la-bell"></i> 1056 Product Alerts</a>
                            </div>
                            <div class="accounts desktop-only">
                                <nav class="navbar navbar-expand-md navbar-light">
                                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                        <ul class="navbar-nav">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Hi, Nectar Digit
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <!-- <li><a class="dropdown-item" href="#">Change Passeord</a></li> -->
                                                    <li><a class="dropdown-item" href="#">Logout</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="hr-mobile">
                        <div class="accounts mobile-only">
                            <nav class="navbar navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                    <ul class="navbar-nav">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="las la-user-check"></i>
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                <!-- <li><a class="dropdown-item" href="#">Change Passeord</a></li> -->
                                                <li><a class="dropdown-item" href="#">Logout</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="toggle-btn">
                            <div class="toggle-wrap">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="toggle-wrap">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="toggle-wrap">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Mobile Menu -->
        <div id="mySidenav" class="sidenav">
            <div class="mobile-logo">
                <a href="index.html"><img src="/img/logo1.png" alt="logo"></a>
                <a href="javascript:void(0)" id="close-btn" class="closebtn">&times;</a>
            </div>
            <div class="no-bdr1">
                <div class="time mobile-only">
                    <span>Friday, 19 November 2021, 04:15:26 PM</span>
                </div>
                <div class="header-right mobile-only">
                    <div class="header-btns">
                        <div class="main-btns">
                            <a href="#"><i class="las la-chart-line"></i> Today's Sales</a>
                            <a href="#"><i class="las la-bell"></i> 1056 Product Alerts</a>
                        </div>
                    </div>
                </div>
                <ul id="menu1">
                    <li><a href="/">Home</a></li>
                    <li><a href="#">POS Setting</a></li>
                    <li>
                        <a href="#" class="has-arrow">Sales</a>
                        <ul>
                            <li>
                                <a href="#">Sales</a>
                            </li>
                            <li>
                                <a href="/suspendedsale">Suspended Sales</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Mobile Menu End -->
    </div>
</template>
