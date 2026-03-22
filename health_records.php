<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$db = getDb();
$patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : 0;
$editingId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$patients = $db->query('SELECT id, unique_id, name FROM patients ORDER BY name');

$recordsStmt = $db->prepare('SELECT hr.*, p.name AS patient_name, p.unique_id FROM health_records hr INNER JOIN patients p ON p.id = hr.patient_id' . ($patientId ? ' WHERE hr.patient_id = ?' : '') . ' ORDER BY hr.date DESC, hr.id DESC');
if ($patientId) {
    $recordsStmt->bind_param('i', $patientId);
}
$recordsStmt->execute();
$records = $recordsStmt->get_result();

$editing = null;
if ($editingId > 0) {
    $stmt = $db->prepare('SELECT * FROM health_records WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $editingId);
    $stmt->execute();
    $editing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$pageTitle = 'Health Records';
include __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h2><?php echo $editing ? 'Edit Health Record' : 'Add Health Record'; ?></h2>
    <form action="php/record_actions.php" method="post" class="form-grid">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $editing ? 'update' : 'create'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)($editing['id'] ?? 0); ?>">
        <label>Patient
            <select name="patient_id" required>
                <option value="">Select Patient</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?php echo (int)$p['id']; ?>" <?php echo (int)($editing['patient_id'] ?? $patientId) === (int)$p['id'] ? 'selected' : ''; ?>>
                        <?php echo e($p['name'] . ' (' . $p['unique_id'] . ')'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <label>Date <input type="date" name="date" required value="<?php echo e($editing['date'] ?? date('Y-m-d')); ?>"></label>
        <label>Blood Pressure <input type="text" name="bp" required value="<?php echo e($editing['bp'] ?? ''); ?>" placeholder="120/80"></label>
        <label>Temperature (°C) <input type="number" step="0.1" name="temp" required value="<?php echo e($editing['temp'] ?? ''); ?>"></label>
        <label>Symptoms <input type="text" name="symptoms" required value="<?php echo e($editing['symptoms'] ?? ''); ?>"></label>
        <label>Diagnosis <input type="text" name="diagnosis" required value="<?php echo e($editing['diagnosis'] ?? ''); ?>"></label>
        <label>Treatment <input type="text" name="treatment" required value="<?php echo e($editing['treatment'] ?? ''); ?>"></label>
        <div class="form-actions">
            <button class="btn" type="submit"><?php echo $editing ? 'Update Record' : 'Save Record'; ?></button>
            <?php if ($editing): ?><a class="btn-muted" href="health_records.php">Cancel Edit</a><?php endif; ?>
        </div>
    </form>
</section>

<section class="card">
    <div class="toolbar">
        <h2>Patient History</h2>
        <form method="get">
            <select name="patient_id" onchange="this.form.submit()">
                <option value="0">All Patients</option>
                <?php
                $patientsFilter = $db->query('SELECT id, name, unique_id FROM patients ORDER BY name');
                while ($pf = $patientsFilter->fetch_assoc()):
                ?>
                    <option value="<?php echo (int)$pf['id']; ?>" <?php echo $patientId === (int)$pf['id'] ? 'selected' : ''; ?>>
                        <?php echo e($pf['name'] . ' (' . $pf['unique_id'] . ')'); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Date</th><th>Patient</th><th>BP</th><th>Temp</th><th>Diagnosis</th><th>Treatment</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if ($records->num_rows === 0): ?>
                <tr><td colspan="7" class="empty">No health records found yet.</td></tr>
            <?php else: ?>
                <?php while ($r = $records->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo e($r['date']); ?></td>
                        <td><?php echo e($r['patient_name']); ?></td>
                        <td><?php echo e($r['bp']); ?></td>
                        <td><?php echo e($r['temp']); ?>°C</td>
                        <td><?php echo e($r['diagnosis']); ?></td>
                        <td><?php echo e($r['treatment']); ?></td>
                        <td class="actions">
                            <a class="btn-sm" href="health_records.php?edit=<?php echo (int)$r['id']; ?>">Edit</a>
                            <form action="php/record_actions.php" method="post" class="inline-form" onsubmit="return confirm('Delete this record?');">
                                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo (int)$r['id']; ?>">
                                <button class="btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<script src="js/records.js"></script>
<?php include __DIR__ . '/includes/footer.php'; ?>
