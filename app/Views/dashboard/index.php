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
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 10px 20px;
            margin: 5px 0;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
        }
        .main-content {
            padding: 20px;
        }
        .stats-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="text-center mb-4">
                    <h4 class="text-white">Hotel Manager</h4>
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
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Welcome, <?= $user['username'] ?? 'User' ?></h2>
                    <span class="badge bg-primary"><?= ucfirst($user['role'] ?? 'staff') ?></span>
                </div>

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">
                                <h4 class="mb-2"><?= $total_guests ?? 0 ?></h4>
                                <p class="text-muted mb-0">Checked-in Guests</p>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="<?= base_url('dashboard/guests') ?>">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-danger text-white mb-4">
                            <div class="card-body">
                                <h4 class="mb-2"><?= $maintenance_rooms ?? 0 ?></h4>
                                <p class="text-muted mb-0">Rooms in Maintenance</p>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="<?= base_url('dashboard/rooms') ?>">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-primary mb-3">Total Rooms</h3>
                            <h4><?= $total_rooms ?? 0 ?></h4>
                            <p class="text-muted">Available rooms</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-warning mb-3">Pending Requests</h3>
                            <h4><?= $pending_requests ?? 0 ?></h4>
                            <p class="text-muted">Awaiting action</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <h3 class="text-info mb-3">Completed Today</h3>
                            <h4><?= $completed_today ?? 0 ?></h4>
                            <p class="text-muted">Service requests</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Room Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Available</span>
                                    <span class="badge bg-success"><?= $available_rooms ?? 0 ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Occupied</span>
                                    <span class="badge bg-warning"><?= $occupied_rooms ?? 0 ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Maintenance</span>
                                    <span class="badge bg-danger"><?= $maintenance_rooms ?? 0 ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($recent_activities) && !empty($recent_activities)) : ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($recent_activities as $activity) : ?>
                                            <li class="mb-2">
                                                <small class="text-muted"><?= $activity['time'] ?></small>
                                                <div><?= $activity['description'] ?></div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else : ?>
                                    <p class="text-muted text-center">No recent activities</p>
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