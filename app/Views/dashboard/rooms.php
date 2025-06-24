<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Room Management' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 25px;
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

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #2c3e50;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 32px;
            text-align: center;
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .modal-content {
            border: none;
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 0 0 15px 15px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
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

        .room-status {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .room-status i {
            margin-right: 6px;
        }

        .status-available {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .status-occupied {
            background-color: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }

        .status-maintenance {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            padding: 6px 30px 6px 10px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            padding: 6px 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px;
            margin: 0 2px;
        }

        .add-room-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .add-room-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('dashboard/rooms') ?>">
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
                        <h2 class="mb-0">Room Management</h2>
                        <p class="text-muted mb-0">Manage your hotel rooms and their status</p>
                    </div>
                    <button class="btn btn-primary add-room-btn" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="fas fa-plus me-2"></i> Add New Room
                    </button>
                </div>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Rooms Table -->
                <div class="card">
                    <div class="card-body">
                        <table id="roomsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Room ID</th>
                                    <th>Room Number</th>
                                    <th>Room Type</th>
                                    <th>AC/Non-AC</th>
                                    <th>Bed Type</th>
                                    <th>Rate/Day</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($rooms) && !empty($rooms)) : ?>
                                    <?php foreach ($rooms as $room) : ?>
                                        <tr>
                                            <td><?= esc($room['id']) ?></td>
                                            <td>
                                                <strong><?= esc($room['room_number']) ?></strong>
                                            </td>
                                            <td>
                                                <i class="fas fa-door-open me-2 text-muted"></i>
                                                <?= esc($room['room_type']) ?>
                                            </td>
                                            <td>
                                                <i class="fas <?= $room['ac_type'] === 'AC' ? 'fa-snowflake text-info' : 'fa-fan text-secondary' ?> me-2"></i>
                                                <?= esc($room['ac_type']) ?>
                                            </td>
                                            <td>
                                                <i class="fas fa-bed me-2 text-muted"></i>
                                                <?= esc($room['bed_type']) ?>
                                            </td>
                                            <td>
                                                <strong class="text-primary">₹<?= number_format($room['rate_per_day'], 2) ?></strong>
                                            </td>
                                            <td>
                                                <span class="room-status <?= $room['status'] === 'available' ? 'status-available' : ($room['status'] === 'occupied' ? 'status-occupied' : 'status-maintenance') ?>">
                                                    <i class="fas <?= $room['status'] === 'available' ? 'fa-check-circle' : ($room['status'] === 'occupied' ? 'fa-user' : 'fa-tools') ?>"></i>
                                                    <?= ucfirst($room['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('dashboard/rooms/edit/' . $room['id']) ?>" class="btn btn-primary btn-action" title="Edit Room">
                                    <i class="fas fa-edit"></i>
                                </a>
                                                <button class="btn btn-danger btn-action delete-room" data-id="<?= $room['id'] ?>" title="Delete Room">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Room Modal -->
                <div class="modal fade" id="addRoomModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Add New Room
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="<?= site_url('dashboard/rooms/add') ?>" method="post">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-door-open me-2"></i>
                                                Room Number
                                            </label>
                                            <input type="text" class="form-control" name="room_number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-building me-2"></i>
                                                Room Type
                                            </label>
                                            <select class="form-select" name="room_type" required>
                                                <option value="Standard">Standard</option>
                                                <option value="Deluxe">Deluxe</option>
                                                <option value="Suite">Suite</option>
                                                <option value="Family">Family</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-snowflake me-2"></i>
                                                AC Type
                                            </label>
                                            <select class="form-select" name="ac_type" required>
                                                <option value="AC">AC</option>
                                                <option value="NON-AC">NON-AC</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-bed me-2"></i>
                                                Bed Type
                                            </label>
                                            <select class="form-select" name="bed_type" required>
                                                <option value="Single">Single</option>
                                                <option value="Double">Double</option>
                                                <option value="Twin">Twin</option>
                                                <option value="King">King</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-rupee-sign me-2"></i>
                                                Rate per Day
                                            </label>
                                            <input type="number" class="form-control" name="rate_per_day" required step="0.01">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Status
                                            </label>
                                            <select class="form-select" name="status" required>
                                                <option value="available">Available</option>
                                                <option value="occupied">Occupied</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">
                                                <i class="fas fa-align-left me-2"></i>
                                                Description
                                            </label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Save Room
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#roomsTable').DataTable({
                "pageLength": 10,
                "order": [[0, "asc"]],
                "language": {
                    "search": "Search rooms:",
                    "lengthMenu": "Show _MENU_ rooms per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ rooms",
                    "emptyTable": "No rooms available"
                }
            });

            // Delete room confirmation
            $('.delete-room').click(function() {
                if (confirm('Are you sure you want to delete this room?')) {
                    const roomId = $(this).data('id');
                    window.location.href = `<?= base_url('dashboard/rooms/delete/') ?>/${roomId}`;
                }
            });


        });
    </script>
</body>
</html> 