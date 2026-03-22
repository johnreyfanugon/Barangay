<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$db = getDb();
$reportType = (string)($_GET['type'] ?? 'patients');
$isPrint = isset($_GET['print']) && $_GET['print'] === '1';

if ($reportType === 'summary') {
    $data = $db->query('SELECT p.unique_id, p.name, COUNT(hr.id) AS checkups, MAX(hr.date) AS last_checkup FROM patients p LEFT JOIN health_records hr ON hr.patient_id = p.id GROUP BY p.id ORDER BY p.name');
} else {
    $reportType = 'patients';
    $data = $db->query('SELECT unique_id, name, birthdate, gender, address, contact FROM patients ORDER BY name');
}

if ($isPrint):
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Printable Report</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body onload="window.print()">
<main class="content print">
<?php endif; ?>

<?php if (!$isPrint) {
    $pageTitle = 'Reports';
    include __DIR__ . '/includes/header.php';
} ?>
<section class="card">
    <div class="toolbar">
        <h2>Reporting System</h2>
        <div class="actions">
            <a class="btn-sm" href="reports.php?type=patients">Patient List</a>
            <a class="btn-sm" href="reports.php?type=summary">Health Summary</a>
            <a class="btn" href="reports.php?type=<?php echo e($reportType); ?>&print=1" target="_blank">Print View</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
            <?php if ($reportType === 'summary'): ?>
            <tr><th>Patient ID</th><th>Name</th><th>Checkups</th><th>Last Checkup</th></tr>
            <?php else: ?>
            <tr><th>Patient ID</th><th>Name</th><th>Birthdate</th><th>Gender</th><th>Address</th><th>Contact</th></tr>
            <?php endif; ?>
            </thead>
            <tbody>
            <?php while ($row = $data->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <td><?php echo e((string)$cell); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<?php if (!$isPrint) include __DIR__ . '/includes/footer.php'; ?>
<?php if ($isPrint): ?>
</main>
</body>
</html>
<?php endif; ?>
