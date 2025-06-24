<?= $this->extend('dashboard/layout') ?>

<?= $this->section('content') ?>
<style>
    .edit-room-card {
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
        <h1 class="h3 mb-0 text-gray-800">Edit Room</h1>
        <a href="<?= site_url('dashboard/rooms') ?>" class="btn btn-cancel">
            <i class="fas fa-arrow-left me-2"></i> Back to Rooms
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card edit-room-card">
                <div class="card-body p-4">
                    <form action="<?= site_url('dashboard/rooms/edit/' . $room['id']) ?>" method="post">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_number" class="form-label">Room Number</label>
                                    <input type="text" class="form-control" id="room_number" name="room_number" value="<?= esc($room['room_number']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_type" class="form-label">Room Type</label>
                                    <select class="form-select" id="room_type" name="room_type" required>
                                        <option value="Standard" <?= $room['room_type'] === 'Standard' ? 'selected' : '' ?>>Standard</option>
                                        <option value="Deluxe" <?= $room['room_type'] === 'Deluxe' ? 'selected' : '' ?>>Deluxe</option>
                                        <option value="Suite" <?= $room['room_type'] === 'Suite' ? 'selected' : '' ?>>Suite</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ac_type" class="form-label">AC Type</label>
                                    <select class="form-select" id="ac_type" name="ac_type" required>
                                        <option value="AC" <?= $room['ac_type'] === 'AC' ? 'selected' : '' ?>>AC</option>
                                        <option value="Non-AC" <?= $room['ac_type'] === 'Non-AC' ? 'selected' : '' ?>>Non-AC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bed_type" class="form-label">Bed Type</label>
                                    <select class="form-select" id="bed_type" name="bed_type" required>
                                        <option value="Single" <?= $room['bed_type'] === 'Single' ? 'selected' : '' ?>>Single</option>
                                        <option value="Double" <?= $room['bed_type'] === 'Double' ? 'selected' : '' ?>>Double</option>
                                        <option value="King" <?= $room['bed_type'] === 'King' ? 'selected' : '' ?>>King</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate_per_day" class="form-label">Rate Per Day</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="rate_per_day" name="rate_per_day" value="<?= esc($room['rate_per_day']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="available" <?= $room['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                                        <option value="occupied" <?= $room['status'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                                        <option value="maintenance" <?= $room['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= esc($room['description']) ?></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= site_url('dashboard/rooms') ?>" class="btn btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-save">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card edit-room-card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Room Information</h5>
                    <div class="mb-3">
                        <small class="text-muted d-block">Created</small>
                        <div><?= date('M d, Y H:i', strtotime($room['created_at'])) ?></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Last Updated</small>
                        <div><?= date('M d, Y H:i', strtotime($room['updated_at'])) ?></div>
                    </div>
                    <hr>
                    <div class="d-grid">
                        <a href="<?= site_url('dashboard/rooms/delete/' . $room['id']) ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this room?')">
                            <i class="fas fa-trash me-2"></i> Delete Room
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 