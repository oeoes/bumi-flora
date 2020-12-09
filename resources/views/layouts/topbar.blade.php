<div id="header" class="page-header ">
    <div class="navbar navbar-expand-lg">
        <!-- brand -->
        <a href="index.html" class="navbar-brand d-lg-none">
            <svg width="32" height="32" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                <g class="loading-spin" style="transform-origin: 256px 256px">
                    <path
                        d="M200.043 106.067c-40.631 15.171-73.434 46.382-90.717 85.933H256l-55.957-85.933zM412.797 288A160.723 160.723 0 0 0 416 256c0-36.624-12.314-70.367-33.016-97.334L311 288h101.797zM359.973 134.395C332.007 110.461 295.694 96 256 96c-7.966 0-15.794.591-23.448 1.715L310.852 224l49.121-89.605zM99.204 224A160.65 160.65 0 0 0 96 256c0 36.639 12.324 70.394 33.041 97.366L201 224H99.204zM311.959 405.932c40.631-15.171 73.433-46.382 90.715-85.932H256l55.959 85.932zM152.046 377.621C180.009 401.545 216.314 416 256 416c7.969 0 15.799-.592 23.456-1.716L201.164 288l-49.118 89.621z">
                    </path>
                </g>
            </svg>
            <!-- <img src="../assets/img/logo.png" alt="..."> -->
            <span class="hidden-folded d-inline l-s-n-1x d-lg-none">BumiFlora</span>
        </a>
        <!-- / brand -->
        <!-- Navbar collapse -->
        <div class="collapse navbar-collapse order-2 order-lg-1" id="navbarToggler">
            <form class="input-group m-2 my-lg-0 ">
                <div class="input-group-prepend">
                    <button type="button" class="btn no-shadow no-bg px-0">
                        <i data-feather="search"></i>
                    </button>
                </div>
                <input type="text" class="form-control no-border no-shadow no-bg typeahead"
                    placeholder="Search components..." data-plugin="typeahead" data-api="">
            </form>
        </div>
        <ul class="nav navbar-menu order-1 order-lg-2">
            <li class="nav-item d-none d-sm-block">
                <a class="nav-link px-2" data-toggle="fullscreen" data-plugin="fullscreen">
                    <i data-feather="maximize"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link px-2" data-toggle="dropdown">
                    <i data-feather="settings"></i>
                </a>
                <!-- ############ Setting START-->
                <div class="dropdown-menu dropdown-menu-center mt-3 w animate fadeIn">
                    <div class="setting px-3">
                        <div class="mb-2 text-muted">
                            <strong>Setting:</strong>
                        </div>
                        <div class="mb-3" id="settingLayout">
                            <label class="ui-check ui-check-rounded my-1 d-block">
                                <input type="checkbox" name="stickyHeader">
                                <i></i>
                                <small>Sticky header</small>
                            </label>
                            <label class="ui-check ui-check-rounded my-1 d-block">
                                <input type="checkbox" name="stickyAside">
                                <i></i>
                                <small>Sticky aside</small>
                            </label>
                            <label class="ui-check ui-check-rounded my-1 d-block">
                                <input type="checkbox" name="foldedAside">
                                <i></i>
                                <small>Folded Aside</small>
                            </label>
                            <label class="ui-check ui-check-rounded my-1 d-block">
                                <input type="checkbox" name="hideAside">
                                <i></i>
                                <small>Hide Aside</small>
                            </label>
                        </div>
                        <div class="mb-2 text-muted">
                            <strong>Color:</strong>
                        </div>
                        <div class="mb-2">
                            <label class="radio radio-inline ui-check ui-check-md">
                                <input type="radio" name="bg" value="">
                                <i></i>
                            </label>
                            <label class="radio radio-inline ui-check ui-check-color ui-check-md">
                                <input type="radio" name="bg" value="bg-dark">
                                <i class="bg-dark"></i>
                            </label>
                        </div>
                        <div class="mb-2 text-muted">
                            <strong>Layout:</strong>
                        </div>
                        <div class="mb-2">
                            <a href="dashboard.html" class="btn btn-sm btn-white no-ajax">Default</a>
                            <a href="layout.a.html?bg" class="btn btn-sm btn-white no-ajax">A</a>
                        </div>
                    </div>
                </div>
                <!-- ############ Setting END-->
            </li>
            <!-- Notification -->
            <li class="nav-item dropdown">
                <a id="bell" class="nav-link px-2 mr-lg-2" data-toggle="dropdown">
                    <i data-feather="bell"></i>
                    <span id="bell-badge" class="badge badge-pill badge-up bg-primary"></span>
                </a>
                <!-- dropdown -->
                <div class="dropdown-menu dropdown-menu-right mt-3 w-md animate fadeIn p-0">
                    <div class="scrollable hover" style="max-height: 250px">
                        <div id="notif-list" class="list list-row">
                                                        
                        </div>
                    </div>
                    <div class="d-flex px-3 py-2 b-t">
                        <div class="flex">
                            <span id="notif-count"></span> Unread Messages
                        </div>
                        <a href="{{ route('notifications.page') }}">See all
                            <i class="fa fa-angle-right text-muted"></i>
                        </a>
                    </div>
                </div>
                <!-- / dropdown -->
            </li>
            <!-- User dropdown menu -->
            <li class="nav-item dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link d-flex align-items-center px-2 text-color">
                    <span class="avatar w-24" style="margin: -2px;"><img src="{{ asset('assets/img/a3.jpg') }}"
                            alt="..."></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right w mt-3 animate fadeIn">
                    <a class="dropdown-item">
                        <span><b>{{ auth()->user()->name }}</b></span>
                    </a>

                    <div class="dropdown-divider"></div>
                    <div style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#edit-account-info">
                        Account Settings
                    </div>

                    <a class="dropdown-item" href="{{ route('logout') }}">Sign out</a>
                </div>
            </li>
            <!-- Navarbar toggle btn -->
            <li class="nav-item d-lg-none">
                <a href="#" class="nav-link px-2" data-toggle="collapse" data-toggle-class data-target="#navbarToggler">
                    <i data-feather="search"></i>
                </a>
            </li>
            <li class="nav-item d-lg-none">
                <a class="nav-link px-1" data-toggle="modal" data-target="#aside">
                    <i data-feather="menu"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

<div id="edit-account-info" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Perbarui Akun</div>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control rounded-pill outline-danger" value="{{ auth()->user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control rounded-pill" required>
                    </div>
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input id="old_password" type="password" class="form-control rounded-pill" required>
                    </div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill pr-4 pl-4"
                    data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill pr-4 pl-4">Save
                    Changes</button>
            </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
