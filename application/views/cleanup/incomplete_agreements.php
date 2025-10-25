<!DOCTYPE html>
<html>
<head>
    <title>Incomplete Agreements Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Incomplete Agreements Found</h1>
        
        <div class="alert alert-info">
            <h4>Summary</h4>
            <ul>
                <li>Agreements with no locations: <?= count($no_locations) ?></li>
                <li>Agreements with no items: <?= count($no_items) ?></li>
                <li>Agreements with no vehicles: <?= count($no_vehicles) ?></li>
                <li>Agreements with zero amount: <?= count($zero_amount) ?></li>
                <li>Agreements with missing fields: <?= count($missing_fields) ?></li>
            </ul>
        </div>

        <?php if (!empty($no_locations)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Agreements with No Locations (<?= count($no_locations) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Agreement ID</th>
                                <th>Agreement No</th>
                                <th>Agreement Name</th>
                                <th>Amount</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($no_locations as $agreement): ?>
                            <tr>
                                <td><?= $agreement->agreement_id ?></td>
                                <td><?= $agreement->agreement_no ?></td>
                                <td><?= $agreement->agreement ?></td>
                                <td><?= $agreement->amount ?></td>
                                <td><?= $agreement->section ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($no_items)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Agreements with No Items (<?= count($no_items) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Agreement ID</th>
                                <th>Agreement No</th>
                                <th>Agreement Name</th>
                                <th>Amount</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($no_items as $agreement): ?>
                            <tr>
                                <td><?= $agreement->agreement_id ?></td>
                                <td><?= $agreement->agreement_no ?></td>
                                <td><?= $agreement->agreement ?></td>
                                <td><?= $agreement->amount ?></td>
                                <td><?= $agreement->section ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($no_vehicles)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Agreements with No Vehicles (<?= count($no_vehicles) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Agreement ID</th>
                                <th>Agreement No</th>
                                <th>Agreement Name</th>
                                <th>Amount</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($no_vehicles as $agreement): ?>
                            <tr>
                                <td><?= $agreement->agreement_id ?></td>
                                <td><?= $agreement->agreement_no ?></td>
                                <td><?= $agreement->agreement ?></td>
                                <td><?= $agreement->amount ?></td>
                                <td><?= $agreement->section ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($zero_amount)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Agreements with Zero Amount (<?= count($zero_amount) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Agreement ID</th>
                                <th>Agreement No</th>
                                <th>Agreement Name</th>
                                <th>Amount</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($zero_amount as $agreement): ?>
                            <tr>
                                <td><?= $agreement->agreement_id ?></td>
                                <td><?= $agreement->agreement_no ?></td>
                                <td><?= $agreement->agreement ?></td>
                                <td><?= $agreement->amount ?></td>
                                <td><?= $agreement->section ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($missing_fields)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Agreements with Missing Required Fields (<?= count($missing_fields) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Agreement ID</th>
                                <th>Agreement No</th>
                                <th>Agreement Name</th>
                                <th>Amount</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($missing_fields as $agreement): ?>
                            <tr>
                                <td><?= $agreement->agreement_id ?></td>
                                <td><?= $agreement->agreement_no ?></td>
                                <td><?= $agreement->agreement ?></td>
                                <td><?= $agreement->amount ?></td>
                                <td><?= $agreement->section ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="<?= base_url('cleanup') ?>" class="btn btn-secondary">Back to Cleanup Dashboard</a>
            <a href="<?= base_url('cleanup/create_backup') ?>" class="btn btn-warning">Create Backup</a>
        </div>
    </div>
</body>
</html>
