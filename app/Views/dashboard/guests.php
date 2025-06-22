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
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
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
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Guest Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal">
                        <i class="fas fa-plus me-2"></i> Add New Guest
                    </button>
                </div>

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Guests Table -->
                <div class="card">
                    <div class="card-body">
                        <table id="guestsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Guest Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($guests) && !empty($guests)) : ?>
                                    <?php foreach ($guests as $guest) : ?>
                                        <tr>
                                            <td><?= esc($guest['room_number']) ?></td>
                                            <td><?= esc($guest['first_name'] . ' ' . $guest['last_name']) ?></td>
                                            <td><?= esc($guest['email']) ?></td>
                                            <td><?= esc($guest['phone']) ?></td>
                                            <td><?= date('M d, Y', strtotime($guest['check_in_date'])) ?></td>
                                            <td><?= date('M d, Y', strtotime($guest['check_out_date'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $guest['status'] === 'checked_in' ? 'success' : ($guest['status'] === 'checked_out' ? 'danger' : 'warning') ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $guest['status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit-guest" data-id="<?= $guest['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-guest" data-id="<?= $guest['id'] ?>">
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
            </div>
        </div>
    </div>

    <!-- Add Guest Modal -->
    <div class="modal fade" id="addGuestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('dashboard/guests/add') ?>" method="post">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Number</label>
                                <input type="text" class="form-control" name="room_number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="reserved">Reserved</option>
                                    <option value="checked_in">Checked In</option>
                                    <option value="checked_out">Checked Out</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check In Date</label>
                                <input type="date" class="form-control" name="check_in_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check Out Date</label>
                                <input type="date" class="form-control" name="check_out_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Guest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Guest Modal -->
    <div class="modal fade" id="editGuestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editGuestForm" method="post">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="edit_phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Number</label>
                                <input type="text" class="form-control" name="room_number" id="edit_room_number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="edit_status" required>
                                    <option value="reserved">Reserved</option>
                                    <option value="checked_in">Checked In</option>
                                    <option value="checked_out">Checked Out</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check In Date</label>
                                <input type="date" class="form-control" name="check_in_date" id="edit_check_in_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check Out Date</label>
                                <input type="date" class="form-control" name="check_out_date" id="edit_check_out_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Guest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteGuestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this guest? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Delete Guest</a>
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
                order: [[4, 'desc']], // Sort by check-in date by default
                pageLength: 10,
                responsive: true
            });

            // Set min date for check-in and check-out dates
            const today = new Date().toISOString().split('T')[0];
            $('input[name="check_in_date"]').attr('min', today);
            $('input[name="check_out_date"]').attr('min', today);

            // Update check-out min date when check-in date changes
            $('input[name="check_in_date"]').on('change', function() {
                $('input[name="check_out_date"]').attr('min', this.value);
            });

            // Handle edit button click
            $('.edit-guest').on('click', function() {
                const id = $(this).data('id');
                const row = $(this).closest('tr');
                
                // Get data from the table row
                const firstName = row.find('td:eq(1)').text().split(' ')[0];
                const lastName = row.find('td:eq(1)').text().split(' ')[1];
                const email = row.find('td:eq(2)').text();
                const phone = row.find('td:eq(3)').text();
                const roomNumber = row.find('td:eq(0)').text();
                const checkIn = row.find('td:eq(4)').text();
                const checkOut = row.find('td:eq(5)').text();
                const status = row.find('td:eq(6)').text().toLowerCase().replace(' ', '_');

                // Convert date format from "Mar 19, 2024" to "2024-03-19"
                function convertDate(dateStr) {
                    const date = new Date(dateStr);
                    return date.toISOString().split('T')[0];
                }

                // Populate the edit form
                $('#edit_first_name').val(firstName);
                $('#edit_last_name').val(lastName);
                $('#edit_email').val(email);
                $('#edit_phone').val(phone);
                $('#edit_room_number').val(roomNumber);
                $('#edit_check_in_date').val(convertDate(checkIn));
                $('#edit_check_out_date').val(convertDate(checkOut));
                $('#edit_status').val(status);

                // Update form action URL
                $('#editGuestForm').attr('action', `<?= base_url('dashboard/guests/update/') ?>/${id}`);

                // Show the modal
                $('#editGuestModal').modal('show');
            });

            // Handle delete button click
            $('.delete-guest').on('click', function() {
                const id = $(this).data('id');
                $('#confirmDelete').attr('href', `<?= base_url('dashboard/guests/delete/') ?>/${id}`);
                $('#deleteGuestModal').modal('show');
            });
        });
    </script>
</body>
</html> 