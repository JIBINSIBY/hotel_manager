<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Guest Management' ?></title>
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

        .guest-name {
            font-weight: 600;
            color: var(--primary-bg);
        }

        .guest-email {
            color: var(--accent-color);
        }

        .guest-phone {
            font-family: monospace;
            color: var(--secondary-bg);
        }

        .guest-room {
            font-weight: 600;
            color: var(--info-color);
        }

        .guest-dates {
            color: var(--secondary-bg);
            font-size: 0.9rem;
        }

        .guest-status {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .guest-status i {
            margin-right: 6px;
        }

        .status-checked-in {
            background-color: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .status-checked-out {
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .status-reserved {
            background-color: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
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

        .add-guest-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .add-guest-btn:hover {
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
                        <a class="nav-link" href="<?= base_url('dashboard/rooms') ?>">
                            <i class="fas fa-bed me-2"></i> Rooms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('dashboard/guests') ?>">
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
                        <h2 class="mb-0">Guest Management</h2>
                        <p class="text-muted mb-0">Manage your hotel guests and their bookings</p>
                    </div>
                    <a href="<?= site_url('dashboard/guests/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Guest
                    </a>
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

                <!-- Guests Table -->
                <div class="card">
                    <div class="card-body">
                        <table id="guestsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Room</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (isset($guests) && is_array($guests)): 
                                    foreach ($guests as $guest): 
                                ?>
                                    <tr>
                                        <td>
                                            <div class="guest-name">
                                                <i class="fas fa-user me-2 text-muted"></i>
                                                <?= esc($guest['first_name'] ?? '') . ' ' . esc($guest['last_name'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guest-email">
                                                <i class="fas fa-envelope me-2"></i>
                                                <?= esc($guest['email'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guest-phone">
                                                <i class="fas fa-phone me-2 text-muted"></i>
                                                <?= esc($guest['phone'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guest-room">
                                                <i class="fas fa-door-open me-2"></i>
                                                <?= esc($guest['room_number'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guest-dates">
                                                <i class="fas fa-calendar-check me-2 text-success"></i>
                                                <?= isset($guest['check_in_date']) ? date('M d, Y', strtotime($guest['check_in_date'])) : '' ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="guest-dates">
                                                <i class="fas fa-calendar-times me-2 text-danger"></i>
                                                <?= isset($guest['check_out_date']) ? date('M d, Y', strtotime($guest['check_out_date'])) : '' ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (isset($guest['status'])): ?>
                                                <span class="guest-status <?= $guest['status'] === 'checked_in' ? 'status-checked-in' : ($guest['status'] === 'checked_out' ? 'status-checked-out' : 'status-reserved') ?>">
                                                    <i class="fas <?= $guest['status'] === 'checked_in' ? 'fa-check-circle' : ($guest['status'] === 'checked_out' ? 'fa-sign-out-alt' : 'fa-clock') ?>"></i>
                                                    <?= ucfirst(str_replace('_', ' ', $guest['status'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('dashboard/guests/edit/' . ($guest['id'] ?? '')) ?>" class="btn btn-primary btn-action" title="Edit Guest">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-action delete-guest" data-id="<?= $guest['id'] ?? '' ?>" title="Delete Guest">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach; 
                                endif; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Guest Modal -->
                <div class="modal fade" id="guestModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Guest Information
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="guestForm">
                                <input type="hidden" id="guest_id" name="guest_id">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-user me-2"></i>
                                                First Name
                                            </label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-user me-2"></i>
                                                Last Name
                                            </label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-envelope me-2"></i>
                                                Email
                                            </label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-phone me-2"></i>
                                                Phone
                                            </label>
                                            <input type="tel" class="form-control" name="phone" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-door-open me-2"></i>
                                                Room Number
                                            </label>
                                            <select class="form-select" name="room_number" required>
                                                <?php if (isset($available_rooms)): ?>
                                                    <?php foreach ($available_rooms as $room_number => $room_description): ?>
                                                        <option value="<?= esc($room_number) ?>"><?= esc($room_description) ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-user-clock me-2"></i>
                                                Status
                                            </label>
                                            <select class="form-select" name="status" required>
                                                <option value="reserved">Reserved</option>
                                                <option value="checked_in">Checked In</option>
                                                <option value="checked_out">Checked Out</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-calendar-check me-2"></i>
                                                Check In Date
                                            </label>
                                            <input type="date" class="form-control" name="check_in_date" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">
                                                <i class="fas fa-calendar-times me-2"></i>
                                                Check Out Date
                                            </label>
                                            <input type="date" class="form-control" name="check_out_date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Save Guest
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
            $('#guestsTable').DataTable({
                "pageLength": 10,
                "order": [[0, "asc"]],
                "language": {
                    "search": "Search guests:",
                    "lengthMenu": "Show _MENU_ guests per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ guests",
                    "emptyTable": "No guests available"
                }
            });

            // Delete guest confirmation
            $('.delete-guest').click(function() {
                if (confirm('Are you sure you want to delete this guest?')) {
                    const guestId = $(this).data('id');
                    window.location.href = `<?= base_url('dashboard/guests/delete/') ?>/${guestId}`;
                }
            });


        });
    </script>
</body>
</html> 