<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.index') }}">
                <span class="menu-title">Orders</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('price-offers.index') }}">
                <span class="menu-title">Price offer</span>
                <i class="mdi mdi-cash menu-icon"></i>
            </a>
        </li>

        {{-- <li class="nav-item nav-category">MENU MANAGEMENT</li> --}}

        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#menuConfig" role="button">
                <span class="menu-title">Menu Configuration</span>
                <i class="mdi mdi-food-fork-drink menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>

            <div class="collapse" id="menuConfig">
                <ul class="nav flex-column sub-menu">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('menus.index') }}">
                            <i class="mdi mdi-food me-2"></i> Menu
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">
                            <i class="mdi mdi-shape-outline me-2"></i> Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('option-groups.index') }}">
                            <i class="mdi mdi-tune-vertical me-2"></i>
                            Option Groups
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('options.index') }}">
                            <i class="mdi mdi-checkbox-marked-outline me-2"></i>
                            Options
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('tables.index') }}">
                <span class="menu-title">Tables</span>
                <i class="mdi mdi-table-chair menu-icon"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('taxes.index') }}">
                <span class="menu-title">Tax</span>
                <i class="mdi mdi-file-percent-outline menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
