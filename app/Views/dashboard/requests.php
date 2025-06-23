<?= $this->extend('dashboard/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Service Requests</h1>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>Service Requests List</div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal">
                <i class="fas fa-plus me-2"></i> New Request
            </button>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="roomFilter">
                        <option value="">All Rooms</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?= $room['room_number'] ?>"><?= $room['room_number'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Requests Table -->
            <table id="requestsTable" class="table table-striped">
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
                            <td><?= esc($request['room_number']) ?></td>
                            <td><?= esc($request['service_name']) ?></td>
                            <td><?= esc($request['description']) ?></td>
                            <td>
                                <span class="badge bg-<?= $request['status'] === 'completed' ? 'success' : ($request['status'] === 'in_progress' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst(str_replace('_', ' ', $request['status'])) ?>
                                </span>
                            </td>
                            <td><?= esc($request['requester_name']) ?></td>
                            <td><?= $request['assignee_name'] ? esc($request['assignee_name']) : '-' ?></td>
                            <td><?= date('M d, Y H:i', strtotime($request['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-request" data-id="<?= $request['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-request" data-id="<?= $request['id'] ?>">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Request</button>
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
                Are you sure you want to delete this service request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#requestsTable').DataTable({
        order: [[6, 'desc']] // Sort by created date by default
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