<!DOCTYPE html>
<html>
<head>
    <title>Cleanup Incomplete Agreements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Cleanup Incomplete Agreements</h1>
        <p class="text-muted">Manage and delete incomplete agreements that are causing blank pages.</p>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Find Incomplete Agreements</h5>
                    </div>
                    <div class="card-body">
                        <p>Scan the database for agreements that are missing required data.</p>
                        <a href="<?= base_url('cleanup/find_incomplete_agreements') ?>" class="btn btn-info">
                            Find Incomplete Agreements
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Create Backup</h5>
                    </div>
                    <div class="card-body">
                        <p>Create a backup of incomplete agreements before deletion.</p>
                        <a href="<?= base_url('cleanup/create_backup') ?>" class="btn btn-warning">
                            Create Backup
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Delete Incomplete Agreements</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-danger"><strong>Warning:</strong> This will permanently delete incomplete agreements.</p>
                        <form method="post" action="<?= base_url('cleanup/delete_incomplete_agreements') ?>" onsubmit="return confirm('Are you sure you want to delete all incomplete agreements? This action cannot be undone!')">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirm_delete" id="confirm_delete" required>
                                <label class="form-check-label" for="confirm_delete">
                                    I understand this will permanently delete incomplete agreements
                                </label>
                            </div>
                            <button type="submit" class="btn btn-danger mt-3">Delete Incomplete Agreements</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Restore from Backup</h5>
                    </div>
                    <div class="card-body">
                        <p>Restore agreements from backup if needed.</p>
                        <form method="post" action="<?= base_url('cleanup/restore_from_backup') ?>" onsubmit="return confirm('Are you sure you want to restore from backup?')">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirm_restore" id="confirm_restore" required>
                                <label class="form-check-label" for="confirm_restore">
                                    I want to restore agreements from backup
                                </label>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">Restore from Backup</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>Instructions</h3>
            <ol>
                <li><strong>Find Incomplete Agreements:</strong> First, scan the database to see what incomplete agreements exist.</li>
                <li><strong>Create Backup:</strong> Always create a backup before deleting anything.</li>
                <li><strong>Delete Incomplete Agreements:</strong> Only after creating a backup, delete the incomplete agreements.</li>
                <li><strong>Restore if Needed:</strong> If something goes wrong, you can restore from the backup.</li>
            </ol>
        </div>

        <div class="mt-4">
            <h3>What Makes an Agreement Incomplete?</h3>
            <ul>
                <li>Missing agreement locations</li>
                <li>Missing agreement items</li>
                <li>Missing vehicles</li>
                <li>Zero or null amount</li>
                <li>Missing required fields (agreement name, number, dates)</li>
            </ul>
        </div>
    </div>
</body>
</html>
