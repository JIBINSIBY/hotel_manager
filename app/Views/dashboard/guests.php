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
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Guest Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#guestModal">
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

                <!-- Debug output -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <!-- Debug information -->
                <div class="d-none">
                    Debug Info:
                    <?php 
                    if (isset($guests)) {
                        echo "Guests array exists. Count: " . count($guests);
                        echo "<pre>";
                        print_r($guests);
                        echo "</pre>";
                    } else {
                        echo "Guests array is not set";
                    }
                    ?>
                </div>

                <!-- Guests Table -->
                <div class="card">
                    <div class="card-body">
                        <table id="guestsTable" class="table table-striped">
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
                                        <td><?= esc($guest['first_name'] ?? '') . ' ' . esc($guest['last_name'] ?? '') ?></td>
                                        <td><?= esc($guest['email'] ?? '') ?></td>
                                        <td><?= esc($guest['phone'] ?? '') ?></td>
                                        <td><?= esc($guest['room_number'] ?? '') ?></td>
                                        <td><?= isset($guest['check_in_date']) ? date('M d, Y', strtotime($guest['check_in_date'])) : '' ?></td>
                                        <td><?= isset($guest['check_out_date']) ? date('M d, Y', strtotime($guest['check_out_date'])) : '' ?></td>
                                        <td>
                                            <?php if (isset($guest['status'])): ?>
                                                <span class="badge bg-<?= $guest['status'] === 'checked_in' ? 'success' : ($guest['status'] === 'checked_out' ? 'danger' : 'warning') ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $guest['status'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-guest" data-id="<?= $guest['id'] ?? '' ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-guest" data-id="<?= $guest['id'] ?? '' ?>">
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
                
                <!-- DataTable Initialization Script -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof $ !== 'undefined' && $.fn.DataTable) {
                            $('#guestsTable').DataTable({
                                "pageLength": 10,
                                "order": [[0, "asc"]],
                                "responsive": true
                            });
                        } else {
                            console.error('DataTables is not loaded');
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <!-- Guest Modal -->
    <div class="modal fade" id="guestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Guest Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="guestForm">
                    <input type="hidden" id="guest_id" name="guest_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" id="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Number</label>
                                <select class="form-select" name="room_number" id="room_number" required>
                                    <option value="">Select a Room</option>
                                    <?php foreach ($available_rooms as $room_number => $room_details): ?>
                                        <option value="<?= esc($room_number) ?>"><?= esc($room_details) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="reserved">Reserved</option>
                                    <option value="checked_in">Checked In</option>
                                    <option value="checked_out">Checked Out</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Adults</label>
                                <input type="number" class="form-control" name="number_of_adults" id="number_of_adults" min="1" max="9" value="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Number of Children</label>
                                <input type="number" class="form-control" name="number_of_children" id="number_of_children" min="0" max="9" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-in Date</label>
                                <input type="text" class="form-control" name="check_in_date" id="check_in_date" required readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check-out Date</label>
                                <input type="text" class="form-control" name="check_out_date" id="check_out_date" required readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Guest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this guest?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#guestsTable').DataTable();

        let bookedDates = [];
        let deleteGuestId = null;
        
        // Initialize date pickers with common settings
        const datePickerOptions = {
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: new Date(),
            todayHighlight: true
        };

        // Initialize date pickers
        $('#check_in_date').datepicker(datePickerOptions);
        $('#check_out_date').datepicker(datePickerOptions);

        // Function to update booked dates for a room
        function updateBookedDates(roomNumber, guestId = null) {
            if (!roomNumber) return;

            $.get(`<?= base_url('dashboard/rooms/booked-dates') ?>`, {
                room_number: roomNumber,
                guest_id: guestId
            })
            .done(function(response) {
                bookedDates = response.bookedDates || [];
                
                // Reinitialize date pickers with new disabled dates
                $('#check_in_date').datepicker('destroy');
                $('#check_out_date').datepicker('destroy');
                
                const updatedOptions = {
                    ...datePickerOptions,
                    beforeShowDay: function(date) {
                        const dateStr = date.toISOString().split('T')[0];
                        return bookedDates.includes(dateStr) ? false : '';
                    }
                };
                
                $('#check_in_date').datepicker(updatedOptions);
                $('#check_out_date').datepicker(updatedOptions);
            });
        }

        // Handle room selection change
        $('#room_number').on('change', function() {
            const roomNumber = $(this).val();
            const guestId = $('#guest_id').val(); // For edit mode
            updateBookedDates(roomNumber, guestId);
        });

        // Handle check-in date change
        $('#check_in_date').on('change', function() {
            const checkInDate = $(this).val();
            if (checkInDate) {
                $('#check_out_date').datepicker('setStartDate', checkInDate);
            }
        });

        // Handle check-out date change
        $('#check_out_date').on('change', function() {
            const checkOutDate = $(this).val();
            if (checkOutDate) {
                $('#check_in_date').datepicker('setEndDate', checkOutDate);
            }
        });

        // Reset form function
        function resetForm() {
            $('#guestForm')[0].reset();
            $('#guest_id').val('');
            $('#room_number').prop('selectedIndex', 0);
            $('#check_in_date').val('');
            $('#check_out_date').val('');
            updateBookedDates($('#room_number').val());
        }

        // Edit guest functionality
        $('.edit-guest').on('click', function(e) {
            e.preventDefault();
            const guestId = $(this).data('id');
            
            // Reset form before populating
            resetForm();
            $('#guest_id').val(guestId);
            
            // Fetch guest data
            $.get(`<?= base_url('dashboard/guests/get') ?>/${guestId}`)
                .done(function(response) {
                    if (response.success && response.guest) {
                        const guest = response.guest;
                        
                        // Populate form fields
                        Object.keys(guest).forEach(key => {
                            const $field = $(`#${key}`);
                            if ($field.length) {
                                if ($field.is('select')) {
                                    // For select elements
                                    $field.val(guest[key]).trigger('change');
                                } else {
                                    // For other input elements
                                    $field.val(guest[key]);
                                }
                            }
                        });
                        
                        // Update booked dates excluding current guest's dates
                        updateBookedDates(guest.room_number, guestId);
                        
                        // Show modal
                        $('#guestModal').modal('show');
                    } else {
                        alert(response.message || 'Error loading guest data');
                    }
                })
                .fail(function() {
                    alert('Error loading guest data');
                });
        });

        // Add new guest button
        $('#addNewGuest').on('click', function() {
            resetForm();
            $('#guestModal').modal('show');
        });

        // Delete guest functionality
        $('.delete-guest').on('click', function(e) {
            e.preventDefault();
            deleteGuestId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirmDelete').on('click', function() {
            if (deleteGuestId) {
                window.location.href = `<?= base_url('dashboard/guests/delete') ?>/${deleteGuestId}`;
            }
        });

        // Form submission
        $('#guestForm').on('submit', function(e) {
            e.preventDefault();
            const guestId = $('#guest_id').val();
            const url = guestId 
                ? `<?= base_url('dashboard/guests/update') ?>/${guestId}`
                : '<?= base_url('dashboard/guests/add') ?>';
            
            $.ajax({
                url: url,
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#guestModal').modal('hide');
                        location.reload();
                    } else {
                        alert(response.message || 'Error saving guest');
                    }
                },
                error: function() {
                    alert('Error saving guest');
                }
            });
        });

        // Modal hidden event
        $('#guestModal').on('hidden.bs.modal', function() {
            resetForm();
        });
    });
    </script>
</body>
</html> 