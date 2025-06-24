<?= $this->extend('dashboard/layout') ?>

<?= $this->section('content') ?>
<style>
    .add-guest-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .form-label {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .btn-save {
        padding: 0.75rem 2rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
    }
    .btn-cancel {
        padding: 0.75rem 2rem;
        font-weight: 500;
        border-radius: 8px;
        background-color: #f8f9fa;
        border-color: #e2e8f0;
        color: #2c3e50;
    }
    .btn-cancel:hover {
        background-color: #e2e8f0;
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Guest</h1>
        <a href="<?= site_url('dashboard/guests') ?>" class="btn btn-cancel">
            <i class="fas fa-arrow-left me-2"></i> Back to Guests
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card add-guest-card">
                <div class="card-body p-4">
                    <form action="<?= site_url('dashboard/guests/add') ?>" method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= old('first_name') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= old('last_name') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_number" class="form-label">Room Number</label>
                                    <select class="form-select" id="room_number" name="room_number" required>
                                        <option value="">Select Room</option>
                                        <?php foreach ($available_rooms as $room): ?>
                                            <option value="<?= $room['room_number'] ?>">
                                                <?= esc($room['room_number']) ?> - <?= esc($room['room_type']) ?> (<?= esc($room['bed_type']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="checked_in">Check In</option>
                                        <option value="reserved">Reserve</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_in_date" class="form-label">Check-in Date</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date" value="<?= old('check_in_date') ?? date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="check_out_date" class="form-label">Check-out Date</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date" value="<?= old('check_out_date') ?? date('Y-m-d', strtotime('+1 day')) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="number_of_adults" class="form-label">Number of Adults</label>
                                    <input type="number" class="form-control" id="number_of_adults" name="number_of_adults" value="<?= old('number_of_adults') ?? '1' ?>" min="1" max="10" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="number_of_children" class="form-label">Number of Children</label>
                                    <input type="number" class="form-control" id="number_of_children" name="number_of_children" value="<?= old('number_of_children') ?? '0' ?>" min="0" max="10">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= site_url('dashboard/guests') ?>" class="btn btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-save">
                                        <i class="fas fa-plus me-2"></i> Add Guest
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card add-guest-card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Room Information</h5>
                    <div id="room-info">
                        <p class="text-muted">Select a room to see its details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Handle room selection change
    $('#room_number').change(function() {
        const roomNumber = $(this).val();
        if (roomNumber) {
            // Find the selected room's information
            const selectedOption = $(this).find('option:selected');
            const roomInfo = selectedOption.text();
            
            // Update the room information card
            $('#room-info').html(`
                <div class="mb-3">
                    <small class="text-muted d-block">Selected Room</small>
                    <div class="fw-bold">${roomInfo}</div>
                </div>
            `);
        } else {
            $('#room-info').html('<p class="text-muted">Select a room to see its details</p>');
        }
    });

    // Date validation
    $('#check_in_date, #check_out_date').change(function() {
        const checkIn = new Date($('#check_in_date').val());
        const checkOut = new Date($('#check_out_date').val());
        
        if (checkOut <= checkIn) {
            alert('Check-out date must be after check-in date');
            $('#check_out_date').val('');
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 