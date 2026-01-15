<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-dark: #0f172a;
            --secondary-dark: #1e293b;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
            --text-light: #e2e8f0;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--primary-dark);
            color: var(--text-light);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* Added sidebar toggle styles for open/close animations */
        .sidebar {
            background-color: var(--secondary-dark);
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
            padding: 2rem 0;
            position: fixed;
            width: 260px;
            left: 0;
            top: 0;
            transition: transform 0.3s ease, width 0.3s ease;
            z-index: 1000;
        }

        /* Sidebar closed state */
        .sidebar.collapsed {
            width: 80px;
        }

        /* Hide text when sidebar is collapsed */
        .sidebar.collapsed .sidebar-brand h5,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-section-title {
            display: none;
        }

        /* Center icons when sidebar is collapsed */
        .sidebar.collapsed .sidebar-brand {
            justify-content: center;
            padding: 0 0.5rem;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        /* Mobile sidebar toggle */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        .sidebar-brand {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 0 1rem;
        }

        .nav-item {
            margin: 0;
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            width: 100%;
        }

        .nav-link:hover {
            color: var(--text-light);
            background-color: rgba(59, 130, 246, 0.1);
        }

        .nav-link.active {
            color: var(--accent-color);
            background-color: rgba(59, 130, 246, 0.15);
            border-left: 3px solid var(--accent-color);
            padding-left: calc(1rem - 3px);
        }

        .nav-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 0 1rem;
            margin-bottom: 1rem;
            letter-spacing: 0.05em;
        }

        /* Adjusted main-content margin for collapsed sidebar */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            padding-top: 120px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .main-content.sidebar-collapsed {
            margin-left: 80px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 140px;
            }

            .main-content.sidebar-open {
                margin-left: 260px;
                padding-top: 140px;
            }
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-toggle {
            background-color: var(--secondary-dark);
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background-color: rgba(59, 130, 246, 0.15);
            color: var(--accent-color);
        }



        /* Fixed navbar to top and adjust margins */
        .navbar-top {
            background-color: var(--secondary-dark);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            transition: left 0.3s ease;
        }

        /* Add margin to navbar when sidebar is open */
        .navbar-top.sidebar-open {
            left: 260px;
        }

        .navbar-top.sidebar-collapsed {
            left: 80px;
        }

        /* Mobile adjustments for fixed header */
        @media (max-width: 768px) {
            .navbar-top {
                left: 0;
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-top.sidebar-open {
                left: 260px;
            }
        }


        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--secondary-dark);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .stat-change {
            font-size: 0.875rem;
            color: #10b981;
        }

        .stat-change.negative {
            color: #ef4444;
        }

        .content-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card-section {
            background-color: var(--secondary-dark);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }




        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            border-bottom: 1px solid var(--border-color);
        }

        th {
            padding: 1rem;
            text-align: left;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .chart-placeholder {
            height: 300px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(139, 92, 246, 0.05));
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .content-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Added sidebar toggle button for mobile -->
    <button id="sidebarToggle" class="sidebar-toggle" title="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-graph-up" style="font-size: 1.5rem; color: var(--accent-color);"></i>
            <h5>Dashboard</h5>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-link active">
                <i class="bi bi-house-door"></i>
                <span>Overview</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-bar-chart"></i>
                <span>Analytics</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-file-earmark"></i>
                <span>Reports</span>
            </a>

            <div class="nav-section">
                <div class="nav-section-title">Management</div>
                <a href="#" class="nav-link">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-shield-check"></i>
                    <span>Security</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main id="mainContent" class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar-top">
            <div class="navbar-left d-flex align-items-center gap-2">
                <!-- Sidebar Toggle Button -->
                <button id="sidebarToggle" class="sidebar-toggle" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">Welcome back,</p>
                    <p style="margin: 0; font-size: 1.125rem; font-weight: 600;">Sarah Johnson</p>
                </div>
            </div>

            <div class="search-box">
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-menu">
                <button class="btn-secondary"
                    style="border: none; background: none; padding: 0; color: var(--text-muted); cursor: pointer;">
                    <i class="bi bi-bell" style="font-size: 1.25rem;"></i>
                </button>
                <div class="user-avatar">SJ</div>
            </div>
        </nav>




        <!-- Stats Grid -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-label">Total Users</div>
                <div class="stat-value">12,540</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> 12.5% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-label">Revenue</div>
                <div class="stat-value">$48,290</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> 8.2% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-label">Growth Rate</div>
                <div class="stat-value">24.5%</div>
                <div class="stat-change negative">
                    <i class="bi bi-arrow-down"></i> 2.1% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-label">Conversion</div>
                <div class="stat-value">3.2%</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> 0.8% from last month
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Chart Card -->
            <div class="card-section">
                <div class="card-header">
                    <h5>Revenue Trend</h5>
                    <select class="btn-secondary" style="padding: 0.35rem 0.75rem; font-size: 0.875rem;">
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                        <option>Last year</option>
                    </select>
                </div>
                <div class="chart-placeholder">
                    <p>📈 Chart visualization area</p>
                </div>
            </div>

            <!-- Recent Users Card -->
            <div class="card-section">
                <div class="card-header">
                    <h5>Recent Activity</h5>
                    <a href="#"
                        style="color: var(--accent-color); text-decoration: none; font-size: 0.875rem;">View all</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div
                        style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #1e40af); flex-shrink: 0;">
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-weight: 500; font-size: 0.875rem;">John Doe</p>
                            <p style="margin: 0; color: var(--text-muted); font-size: 0.75rem;">New user registered</p>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.75rem; white-space: nowrap;">2 hours
                            ago</span>
                    </div>
                    <div
                        style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6, #6d28d9); flex-shrink: 0;">
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-weight: 500; font-size: 0.875rem;">Jane Smith</p>
                            <p style="margin: 0; color: var(--text-muted); font-size: 0.75rem;">Completed payment</p>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.75rem; white-space: nowrap;">4 hours
                            ago</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); flex-shrink: 0;">
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-weight: 500; font-size: 0.875rem;">Mike Johnson</p>
                            <p style="margin: 0; color: var(--text-muted); font-size: 0.75rem;">Updated profile</p>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.75rem; white-space: nowrap;">6 hours
                            ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table Section -->
        <div class="card-section">
            <div class="card-header">
                <h5>Active Users</h5>
                <button class="btn-primary">Add User</button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: 500;">Sarah Johnson</td>
                            <td style="color: var(--text-muted);">sarah@example.com</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td style="color: var(--text-muted);">Jan 15, 2024</td>
                            <td><button class="btn-secondary" style="font-size: 0.75rem;">Edit</button></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;">Michael Brown</td>
                            <td style="color: var(--text-muted);">michael@example.com</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td style="color: var(--text-muted);">Jan 10, 2024</td>
                            <td><button class="btn-secondary" style="font-size: 0.75rem;">Edit</button></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;">Emily Davis</td>
                            <td style="color: var(--text-muted);">emily@example.com</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td style="color: var(--text-muted);">Jan 8, 2024</td>
                            <td><button class="btn-secondary" style="font-size: 0.75rem;">Edit</button></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;">James Wilson</td>
                            <td style="color: var(--text-muted);">james@example.com</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td style="color: var(--text-muted);">Jan 3, 2024</td>
                            <td><button class="btn-secondary" style="font-size: 0.75rem;">Edit</button></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 500;">Robert Wilson</td>
                            <td style="color: var(--text-muted);">robert@example.com</td>
                            <td><span class="badge badge-danger">Inactive</span></td>
                            <td style="color: var(--text-muted);">Jan 5, 2024</td>
                            <td><button class="btn-secondary" style="font-size: 0.75rem;">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Added JavaScript for sidebar toggle functionality -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const navbarTop = document.querySelector('.navbar-top');
        const sidebarToggle = document.getElementById('sidebarToggle');

        function isDesktop() {
            return window.innerWidth > 768;
        }

        // Toggle sidebar on button click
        sidebarToggle.addEventListener('click', () => {
            if (isDesktop()) {
                // Desktop: collapse/expand
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
                navbarTop.classList.toggle('sidebar-collapsed');
            } else {
                // Mobile: open/close with overlay
                sidebar.classList.toggle('open');
                mainContent.classList.toggle('sidebar-open');
                navbarTop.classList.toggle('sidebar-open');
            }
        });

        // Close sidebar on mobile when clicking nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (!isDesktop() && sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    mainContent.classList.remove('sidebar-open');
                    navbarTop.classList.remove('sidebar-open');
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (isDesktop()) {
                sidebar.classList.remove('open');
                mainContent.classList.remove('sidebar-open');
                navbarTop.classList.remove('sidebar-open');
            }
        });

        // Set active nav link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });
    </script>
</body>

</html>
