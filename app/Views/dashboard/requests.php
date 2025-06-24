<?= $this->extend('dashboard/layout') ?>

<?= $this->section('content') ?>
<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        border-radius: 0.75rem;
    }
    .card-header {
        background: white;
        padding: 1.25rem;
        border-top-left-radius: 0.75rem !important;
        border-top-right-radius: 0.75rem !important;
    }
    .card-body {
        padding: 1.25rem;
    }
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .status-pending {
        background-color: #eaecf4;
        color: #4e73df;
    }
    .status-in-progress {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-completed {
        background-color: #e8f5e9;
        color: #1e7e34;
    }
    .btn-action {
        width: 2.5rem;
        height: 2.5rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.2s;
        margin: 0 0.25rem;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .filter-section {
        background: #f8f9fc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .form-select, .form-control {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        border-color: #e3e6f0;
    }
    .form-select:focus, .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    .modal-content {
        border: none;
        border-radius: 0.75rem;
    }
    .modal-header {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        background: #f8f9fc;
    }
    .btn-new-request {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Service Requests</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="h5 mb-0">Service Requests List</div>
            <button class="btn btn-primary btn-new-request" data-bs-toggle="modal" data-bs-target="#requestModal">
                <i class="fas fa-plus me-2"></i> New Request
            </button>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted mb-2">Filter by Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted mb-2">Filter by Room</label>
                        <select class="form-select" id="roomFilter">
                            <option value="">All Rooms</option>
                            <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room['room_number'] ?>"><?= $room['room_number'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="table-responsive">
                <table id="requestsTable" class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Service Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                        <tr>
                            <td class="fw-medium"><?= esc($request['room_number']) ?></td>
                            <td><?= esc($request['service_name']) ?></td>
                            <td><?= esc($request['description']) ?></td>
                            <td>
                                <span class="status-badge status-<?= $request['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                                </span>
                            </td>
                            <td><?= esc($request['requester_name']) ?></td>
                            <td><?= $request['assignee_name'] ? esc($request['assignee_name']) : '<span class="text-muted">-</span>' ?></td>
                            <td><?= date('M d, Y H:i', strtotime($request['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-primary btn-action edit-request" data-id="<?= $request['id'] ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-action delete-request" data-id="<?= $request['id'] ?>" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Service Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="requestForm">
                <input type="hidden" id="request_id" name="request_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Room Number</label>
                        <select class="form-select" name="room_number" id="room_number" required>
                            <option value="">Select Room</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= esc($room['room_number']) ?>"><?= esc($room['room_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Type</label>
                        <select class="form-select" name="service_type_id" id="service_type_id" required>
                            <option value="">Select Service Type</option>
                            <?php foreach ($service_types as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="status" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Request</button>
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
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                    <p class="mb-0">Are you sure you want to delete this service request?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger px-4">Delete</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable with custom styling
    const table = $('#requestsTable').DataTable({
        order: [[6, 'desc']], // Sort by created date by default
        language: {
            search: "",
            searchPlaceholder: "Search requests..."
        },
        dom: "<'row'<'col-md-6'l><'col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
        }
    });

    // Handle status filter
    $('#statusFilter').on('change', function() {
        table.column(3).search($(this).val()).draw();
    });

    // Handle room filter
    $('#roomFilter').on('change', function() {
        table.column(0).search($(this).val()).draw();
    });

    // Handle form submission
    $('#requestForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#request_id').val();
        const url = id ? `<?= base_url('dashboard/requests/update') ?>/${id}` : '<?= base_url('dashboard/requests/create') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });

    // Handle edit button click
    $('.edit-request').on('click', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        
        $('#request_id').val(id);
        $('#room_number').val(row.find('td:eq(0)').text());
        $('#service_type_id').val(row.data('service-type-id'));
        $('#description').val(row.find('td:eq(2)').text());
        $('#status').val(row.find('td:eq(3)').text().toLowerCase().replace(' ', '_'));
        
        $('#requestModal').modal('show');
    });

    // Handle delete button click
    $('.delete-request').on('click', function() {
        const id = $(this).data('id');
        $('#confirmDelete').attr('href', `<?= base_url('dashboard/requests/delete') ?>/${id}`);
        $('#deleteModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 