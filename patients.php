<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$db = getDb();
$search = trim((string)($_GET['search'] ?? ''));

if ($search !== '') {
    $stmt = $db->prepare("SELECT * FROM patients WHERE name ILIKE :term OR unique_id ILIKE :term ORDER BY id DESC");
    $stmt->execute(['term' => '%' . $search . '%']);
    $patients = $stmt->fetchAll();
} else {
    $patients = $db->query('SELECT * FROM patients ORDER BY id DESC')->fetchAll();
}

$pageTitle = 'Patient Management';
include __DIR__ . '/includes/header.php';
?>
<section class="card">
    <div class="toolbar">
        <input type="search" id="patientSearch" placeholder="Search patients instantly..." value="<?php echo e($search); ?>">
        <a class="btn" href="patient_form.php">+ Add Patient</a>
    </div>
    <div class="table-wrap">
        <table id="patientsTable">
            <thead>
            <tr><th>Unique ID</th><th>Name</th><th>Birthdate</th><th>Age</th><th>Gender</th><th>Address</th><th>Contact</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($patients as $row): ?>
                <tr>
                    <td><?php echo e($row['unique_id']); ?></td>
                    <td><?php echo e($row['name']); ?></td>
                    <td><?php echo e($row['birthdate']); ?></td>
                    <td><?php echo (int)computeAge($row['birthdate']); ?></td>
                    <td><?php echo e($row['gender']); ?></td>
                    <td><?php echo e($row['address']); ?></td>
                    <td><?php echo e($row['contact']); ?></td>
                    <td class="actions">
                        <a class="btn-sm" href="patient_form.php?id=<?php echo (int)$row['id']; ?>">Edit</a>
                        <form action="php/patient_actions.php" method="post" class="inline-form" onsubmit="return confirm('Delete this patient?');">
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="patientsPagination" class="pagination"></div>
</section>
<script src="js/patients.js"></script>
<?php include __DIR__ . '/includes/footer.php'; ?>
