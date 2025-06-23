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
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Room Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                        <i class="fas fa-plus me-2"></i> Add New Room
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

                <!-- Rooms Table -->
                <div class="card">
                    <div class="card-body">
                        <table id="roomsTable" class="table table-striped">
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
                                            <td><?= esc($room['room_number']) ?></td>
                                            <td><?= esc($room['room_type']) ?></td>
                                            <td><?= esc($room['ac_type']) ?></td>
                                            <td><?= esc($room['bed_type']) ?></td>
                                            <td><?= esc($room['rate_per_day']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $room['status'] === 'available' ? 'success' : ($room['status'] === 'occupied' ? 'warning' : 'danger') ?>">
                                                    <?= ucfirst($room['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit-room" data-id="<?= $room['id'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-room" data-id="<?= $room['id'] ?>">
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
                                <h5 class="modal-title">Add New Room</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="<?= site_url('dashboard/rooms/add') ?>" method="post">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Room Number</label>
                                            <input type="text" class="form-control" name="room_number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Room Type</label>
                                            <select class="form-select" name="room_type" required>
                                                <option value="Standard">Standard</option>
                                                <option value="Deluxe">Deluxe</option>
                                                <option value="Suite">Suite</option>
                                                <option value="Family">Family</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">AC Type</label>
                                            <select class="form-select" name="ac_type" required>
                                                <option value="AC">AC</option>
                                                <option value="NON-AC">NON-AC</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bed Type</label>
                                            <select class="form-select" name="bed_type" required>
                                                <option value="Single">Single</option>
                                                <option value="Double">Double</option>
                                                <option value="Twin">Twin</option>
                                                <option value="King">King</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Rate per Day</label>
                                            <input type="number" class="form-control" name="rate_per_day" required step="0.01">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status" required>
                                                <option value="available">Available</option>
                                                <option value="occupied">Occupied</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Add Room</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Room Modal -->
                <div class="modal fade" id="editRoomModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Room</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="editRoomForm" method="post">
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Room Number</label>
                                            <input type="text" class="form-control" name="room_number" id="edit_room_number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Room Type</label>
                                            <select class="form-select" name="room_type" id="edit_room_type" required>
                                                <option value="Standard">Standard</option>
                                                <option value="Deluxe">Deluxe</option>
                                                <option value="Suite">Suite</option>
                                                <option value="Family">Family</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">AC Type</label>
                                            <select class="form-select" name="ac_type" id="edit_ac_type" required>
                                                <option value="AC">AC</option>
                                                <option value="NON-AC">NON-AC</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Bed Type</label>
                                            <select class="form-select" name="bed_type" id="edit_bed_type" required>
                                                <option value="Single">Single</option>
                                                <option value="Double">Double</option>
                                                <option value="Twin">Twin</option>
                                                <option value="King">King</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Rate per Day</label>
                                            <input type="number" class="form-control" name="rate_per_day" id="edit_rate_per_day" required step="0.01">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status" id="edit_status" required>
                                                <option value="available">Available</option>
                                                <option value="occupied">Occupied</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Room</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        // Initialize DataTable with variable
        var roomsTable = $('#roomsTable').DataTable();

        // Handle Edit Room
        $('.edit-room').click(function() {
            const id = $(this).data('id');
            
            // Fetch room data
            $.get(`<?= site_url('dashboard/rooms/edit') ?>/${id}`, function(room) {
                $('#edit_room_number').val(room.room_number);
                $('#edit_room_type').val(room.room_type);
                $('#edit_ac_type').val(room.ac_type);
                $('#edit_bed_type').val(room.bed_type);
                $('#edit_rate_per_day').val(room.rate_per_day);
                $('#edit_status').val(room.status);
                $('#edit_description').val(room.description);
                
                // Store room ID in the form
                $('#editRoomForm').data('room-id', id);
                
                // Show modal
                $('#editRoomModal').modal('show');
            });
        });

        // Handle Edit Form Submit
        $('#editRoomForm').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const id = form.data('room-id');
            const formData = form.serialize();
            
            // Log form data for debugging
            console.log('Form Data:', formData);
            console.log('Room ID:', id);
            
            $.ajax({
                type: 'POST',
                url: `<?= site_url('dashboard/rooms/edit') ?>/${id}`,
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        // Hide modal
                        $('#editRoomModal').modal('hide');
                        
                        // Show success message
                        alert('Room updated successfully!');
                        
                        // Reload the page to show updated data
                        location.reload();
                    } else {
                        alert('Failed to update room: ' + (response.errors ? Object.values(response.errors).join('\n') : 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    alert('Failed to update room. Please try again.');
                }
            });
        });

        // Handle Delete Room
        $('.delete-room').click(function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this room?')) {
                window.location.href = `<?= site_url('dashboard/rooms/delete') ?>/${id}`;
            }
        });
    });
    </script>
</body>
</html> 