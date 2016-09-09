<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> 
                    <span>
                        <img alt="image" class="img-circle" src="{{ URL('assets/img/profile_smallv2.png')}}" />
                    </span>
                </div>
                <div class="logo-element"></div>
            </li>
            <li>
                <a href="{{ URL('/') }}"><i class="fa fa-home"></i> <span class="nav-label">Beranda</span></a>
            </li>
            <li class="active">
                <a href=""><i class="fa fa-twitter"></i> <span class="nav-label">Tweet</span> <span class="fa arrow"></span><span class="label label-success pull-right" style="margin-right: 10px;"> {{ number_format($AppController::countTweet()) }} </span></a>
                <ul class="nav nav-second-level">
                    <li><a href="{{ URL('tweet/view') }}">Daftar Tweet</a></li>
                    <li><a href="{{ URL('tweet/clear/view') }}">Daftar Tweet Preprosesing</a></li>
                </ul>
            </li>
            <li class="active">
                <a href=""><i class="fa fa-database"></i> <span class="nav-label">Klasterisasi </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="{{ URL('tweet/get') }}">Unduh Tweet</a></li>
                    <li><a href="{{ URL('processing') }}">Preprosesing</a></li>
                    <li><a href="{{ URL('/clustering') }}">Buat Klaster</a></li>

                </ul>
            </li>
            <li>
                <a href="{{ URL('stopword/view') }}"><i class="glyphicon glyphicon-list-alt"></i> <span class="nav-label">Stopword </span></a>
            </li>
            <li>
                <a href="{{ URL('/help') }}"><i class="fa fa-info-circle"></i> <span class="nav-label">Bantuan</span></a>
            </li>
        </ul>
    </div>
</nav>

