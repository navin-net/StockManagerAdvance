<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('admin-2/css/style.css') }}">
</head>

<body>

    <div id="overlay" onclick="closeSidebar()"></div>

    <!-- ══════ SIDEBAR ══════ -->
    <nav id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-mark">◈</div>
            <span class="sidebar-brand">Monolith</span>
        </div>
        <div class="nav-menu">
            <a href="dashboard.html" class="nav-link-item" data-tip="Dashboard">
                <span class="nav-icon">▣</span><span class="nav-label">Dashboard</span>
            </a>
            <a href="#" class="nav-link-item" data-tip="Analytics">
                <span class="nav-icon">◈</span><span class="nav-label">Analytics</span>
            </a>
            <a href="#" class="nav-link-item" data-tip="Clients">
                <span class="nav-icon">◉</span><span class="nav-label">Clients</span>
            </a>
            <a href="pos.html" class="nav-link-item" data-tip="Point of Sale">
                <span class="nav-icon">◎</span><span class="nav-label">Point of Sale</span>
            </a>
            <a href="#" class="nav-link-item" data-tip="Reports">
                <span class="nav-icon">◧</span><span class="nav-label">Reports</span>
            </a>

            <div class="nav-sep"></div>

            <!-- Settings with submenu -->
            <div class="nav-group">
                <div class="nav-parent open" data-tip="Settings" onclick="toggleSubmenu(this)">
                    <span class="nav-icon">◬</span>
                    <span class="nav-label">Settings</span>
                    <span class="nav-arrow"></span>
                </div>
                <div class="nav-submenu open" id="settingsSubmenu">

                    <!-- Brands -->
                    <a href="brands.html" class="nav-sub-item active">
                        <span class="nav-sub-dot"></span>
                        <span class="nav-label">Brands</span>
                    </a>
                    <button class="nav-sub-add" onclick="openAddModal()" title="Add new brand">
                        <span class="nav-sub-add-icon">+</span>
                        <span class="nav-label">New Brand</span>
                    </button>

                    <!-- Category -->
                    <a href="#" class="nav-sub-item" style="margin-top:4px">
                        <span class="nav-sub-dot"></span>
                        <span class="nav-label">Category</span>
                    </a>
                    <button class="nav-sub-add" onclick="showToast('Category management coming soon!')"
                        title="Add new category">
                        <span class="nav-sub-add-icon">+</span>
                        <span class="nav-label">New Category</span>
                    </button>

                </div>
            </div>

        </div>
        <div class="sidebar-footer">
            <div class="avatar">JD</div>
            <span class="avatar-name">John Doe</span>
        </div>
    </nav>
    <!-- ══════ MAIN ══════ -->
    <div id="main">

        <!-- ══════ TOPBAR ══════ -->
        <div class="topbar">

            <!-- Left: toggle + title + breadcrumb -->
            <div class="topbar-left">
                <button id="sidebarToggleBtn" onclick="toggleSidebar()">☰</button>
                <div class="topbar-title-wrap">
                    <div class="page-title">Brand Management</div>
                    <div class="breadcrumb-row">
                        <a href="dashboard.html">Home</a>
                        <span class="breadcrumb-sep">›</span>
                        <a href="#">Settings</a>
                        <span class="breadcrumb-sep">›</span>
                        <span class="breadcrumb-current">Brands</span>
                    </div>
                </div>
            </div>

            <!-- Right: pos badge + divider + theme toggle + notif + profile -->
            <div class="topbar-right">

                <!-- POS status badge -->
                <div class="pos-badge">
                    <span class="pos-badge-dot"></span>
                    Terminal #1
                </div>

                <div class="topbar-divider"></div>

                <!-- Dark / Light toggle pill -->
                <div class="theme-toggle" id="themePill">
                    <button class="theme-toggle-opt active" id="btnDark" onclick="setTheme('dark')">◑ Dark</button>
                    <button class="theme-toggle-opt" id="btnLight" onclick="setTheme('light')">☀ Light</button>
                </div>

                <!-- Notification bell -->
                <div class="icon-btn" title="Notifications">
                    🔔
                    <span class="notif-dot"></span>
                </div>

                <!-- Add Brand shortcut -->
                <button class="btn-accent" onclick="openAddModal()">
                    <span style="font-size:16px;line-height:1">+</span>
                    <span class="d-none d-sm-inline">Add Brand</span>
                </button>

                <div class="topbar-divider"></div>

                <!-- Profile dropdown -->
                <div class="profile-wrap" id="profileWrap">
                    <div class="profile-btn" id="profileBtn" onclick="toggleProfileDropdown()">
                        <div class="profile-avatar">JD</div>
                        <span class="profile-name">John Doe</span>
                        <span class="profile-chevron"></span>
                    </div>

                    <div class="profile-dropdown" id="profileDropdown">
                        <!-- User info header -->
                        <div class="dd-header">
                            <div class="dd-user-name">John Doe</div>
                            <div class="dd-user-email"><a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                    data-cfemail="3c565354527c5153525350554854125553">[email&#160;protected]</a></div>
                        </div>

                        <!-- Menu items -->
                        <button class="dd-item" onclick="openProfileModal()">
                            <span class="dd-item-icon">◉</span>
                            My Profile
                        </button>
                        <button class="dd-item" onclick="openPasswordModal()">
                            <span class="dd-item-icon">◈</span>
                            Change Password
                        </button>

                        <div class="dd-sep"></div>

                        <button class="dd-item danger" onclick="signOut()">
                            <span class="dd-item-icon">→</span>
                            Sign Out
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="page-content">
            @yield('content')

        </div>


    </div><!-- /main -->

    <div id="toast"></div>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('admin-2/js/script.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

</body>

</html>
