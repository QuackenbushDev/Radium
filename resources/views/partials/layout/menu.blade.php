@if (!Auth::check())
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview">
                    <a href="{{ route('portal::dashboard') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('portal::profile') }}">
                        <i class="fa fa-user"></i> <span>Profile</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>
@else
    <aside class="main-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview">
                    <a href="{{ url('/') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="header">Management</li>
                <li class="treeview">
                    <a href="{{ route('user::index') }}">
                        <i class="fa fa-user"></i> <span>Users</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('nas::index') }}">
                        <i class="fa fa-server"></i> <span>NAS</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('group::index') }}">
                        <i class="fa fa-group"></i> <span>Groups</span>
                    </a>
                </li>
                <li class="header">Reports</li>
                <li class="treeview">
                    <a href="{{ route('report::onlineUsers') }}">
                        <i class="fa fa-user"></i> <span>Online Users</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('report::connectionAttempts') }}">
                        <i class="fa fa-lock"></i> <span>Connection Attempts</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('report::bandwidth') }}">
                        <i class="fa fa-area-chart"></i> <span>Bandwidth</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('report::accounting') }}">
                        <i class="fa fa-table"></i> <span>Accounting</span>
                    </a>
                </li>
                <li class="header">Graphs</li>
                <li class="treeview">
                    <a href="{{ route('graph::user') }}">
                        <i class="fa fa-user"></i> <span>User</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="{{ route('graph::statistics') }}">
                        <i class="fa fa-pie-chart"></i> <span>Statistics</span>
                    </a>
                </li>
                <li class="header">Admin</li>
                <li class="treeview">
                    <a href="{{ route('operator::index') }}">
                        <i class="fa fa-user"></i> <span>Operators</span>
                    </a>
                </li>
            </ul>
        </section>
    </aside>
@endif