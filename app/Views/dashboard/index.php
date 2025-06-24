<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #2c3e50;
            --secondary-bg: #34495e;
            --accent-color: #3498db;
            --text-light: #ecf0f1;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
            --info-color: #3498db;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--primary-bg);
            padding-top: 20px;
            position: fixed;
            width: inherit;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: var(--text-light);
            padding: 12px 20px;
            margin: 8px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: var(--secondary-bg);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: var(--accent-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .main-content {
            margin-left: 16.66667%; /* col-2 width */
            padding: 30px;
        }

        .stats-card {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 15px 15px 0 0 !important;
        }

        .welcome-header {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .activity-item {
            padding: 15px;
            border-left: 3px solid var(--accent-color);
            margin-bottom: 15px;
            background-color: #f8f9fa;
            border-radius: 0 8px 8px 0;
        }

        .room-status-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-light);
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="brand-title">
                    <i class="fas fa-hotel me-2"></i>
                    Hotel Manager
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard/rooms') ?>">
                            <i class="fas fa-bed me-2"></i> Rooms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard/guests') ?>">
                            <i class="fas fa-users me-2"></i> Guests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard/requests') ?>">
                            <i class="fas fa-concierge-bell me-2"></i> Service Requests
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <!-- Welcome Header -->
                <div class="welcome-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Welcome, <?= $user['username'] ?? 'User' ?></h2>
                        <p class="text-muted mb-0">Here's what's happening in your hotel today</p>
                    </div>
                    <span class="status-badge bg-primary"><?= ucfirst($user['role'] ?? 'staff') ?></span>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="stats-card bg-success bg-opacity-10">
                            <div class="stat-value text-success"><?= $total_guests ?? 0 ?></div>
                            <div class="stat-label">Checked-in Guests</div>
                            <div class="mt-3">
                                <a href="<?= base_url('dashboard/guests') ?>" class="btn btn-sm btn-success">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stats-card bg-danger bg-opacity-10">
                            <div class="stat-value text-danger"><?= $maintenance_rooms ?? 0 ?></div>
                            <div class="stat-label">Rooms in Maintenance</div>
                            <div class="mt-3">
                                <a href="<?= base_url('dashboard/rooms') ?>" class="btn btn-sm btn-danger">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stats-card bg-primary bg-opacity-10">
                            <div class="stat-value text-primary"><?= $total_rooms ?? 0 ?></div>
                            <div class="stat-label">Total Rooms</div>
                            <div class="mt-3">
                                <a href="<?= base_url('dashboard/rooms') ?>" class="btn btn-sm btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="stats-card bg-warning bg-opacity-10">
                            <div class="stat-value text-warning"><?= $pending_requests ?? 0 ?></div>
                            <div class="stat-label">Pending Requests</div>
                            <div class="mt-3">
                                <a href="<?= base_url('dashboard/requests') ?>" class="btn btn-sm btn-warning">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Status and Activities -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    Room Status Overview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="room-status-item bg-success bg-opacity-10">
                                    <span><i class="fas fa-check-circle me-2"></i>Available</span>
                                    <span class="status-badge bg-success"><?= $available_rooms ?? 0 ?></span>
                                </div>
                                <div class="room-status-item bg-warning bg-opacity-10">
                                    <span><i class="fas fa-bed me-2"></i>Occupied</span>
                                    <span class="status-badge bg-warning"><?= $occupied_rooms ?? 0 ?></span>
                                </div>
                                <div class="room-status-item bg-danger bg-opacity-10">
                                    <span><i class="fas fa-tools me-2"></i>Maintenance</span>
                                    <span class="status-badge bg-danger"><?= $maintenance_rooms ?? 0 ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Recent Activities
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($recent_activities) && !empty($recent_activities)) : ?>
                                    <?php foreach ($recent_activities as $activity) : ?>
                                        <div class="activity-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span><?= $activity['description'] ?></span>
                                                <small class="text-muted"><?= $activity['time'] ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No recent activities</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 