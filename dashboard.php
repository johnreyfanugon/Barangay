<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

requireLogin();
$db = getDb();

$totalPatients = (int)$db->query('SELECT COUNT(*) AS c FROM patients')->fetch()['c'];
$totalCheckups = (int)$db->query('SELECT COUNT(*) AS c FROM health_records')->fetch()['c'];
$commonIllness = $db->query("SELECT diagnosis, COUNT(*) c FROM health_records GROUP BY diagnosis ORDER BY c DESC LIMIT 5");
$recentCheckups = $db->query("SELECT hr.id, p.name AS patient_name, hr.date, hr.diagnosis FROM health_records hr INNER JOIN patients p ON p.id = hr.patient_id ORDER BY hr.date DESC LIMIT 8");

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>
<section class="grid-cards">
    <article class="card stat-card"><h3>Total Patients</h3><strong><?php echo $totalPatients; ?></strong></article>
    <article class="card stat-card"><h3>Total Checkups</h3><strong><?php echo $totalCheckups; ?></strong></article>
    <article class="card stat-card"><h3>Active Users</h3><strong><?php echo isLoggedIn() ? '1 Session' : '0'; ?></strong></article>
</section>

<section class="grid-two">
    <article class="card">
        <h2>Most Common Illnesses</h2>
        <canvas id="illnessChart" aria-label="Most common illnesses chart"></canvas>
        <script>
            window.illnessData = [
                <?php while ($row = $commonIllness->fetch()): ?>
                {label: "<?php echo e($row['diagnosis'] ?: 'Unspecified'); ?>", value: <?php echo (int)$row['c']; ?>},
                <?php endwhile; ?>
            ];
        </script>
    </article>
    <article class="card">
        <h2>Recent Checkups</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Date</th><th>Patient</th><th>Diagnosis</th></tr></thead>
                <tbody>
                <?php while ($row = $recentCheckups->fetch()): ?>
                    <tr>
                        <td><?php echo e($row['date']); ?></td>
                        <td><?php echo e($row['patient_name']); ?></td>
                        <td><?php echo e($row['diagnosis']); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </article>
</section>
<script src="js/dashboard.js"></script>
<?php include __DIR__ . '/includes/footer.php'; ?>
