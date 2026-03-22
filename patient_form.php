<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
requireLogin();

$db = getDb();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$patient = ['id' => 0, 'name' => '', 'birthdate' => '', 'gender' => '', 'address' => '', 'contact' => ''];

if ($isEdit) {
    $stmt = $db->prepare('SELECT * FROM patients WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $patient = $res->fetch_assoc() ?: $patient;
    $stmt->close();
}

$pageTitle = $isEdit ? 'Edit Patient' : 'Add Patient';
include __DIR__ . '/includes/header.php';
?>
<section class="card">
    <h2><?php echo e($pageTitle); ?></h2>
    <form action="php/patient_actions.php" method="post" class="form-grid" id="patientForm">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <input type="hidden" name="action" value="<?php echo $isEdit ? 'update' : 'create'; ?>">
        <input type="hidden" name="id" value="<?php echo (int)$patient['id']; ?>">
        <label>Full Name <input type="text" name="name" required value="<?php echo e($patient['name']); ?>"></label>
        <label>Birthdate <input type="date" name="birthdate" required value="<?php echo e($patient['birthdate']); ?>"></label>
        <label>Gender
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male" <?php echo $patient['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $patient['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $patient['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
        </label>
        <label>Address <input type="text" name="address" required value="<?php echo e($patient['address']); ?>"></label>
        <label>Contact Number <input type="text" name="contact" required value="<?php echo e($patient['contact']); ?>"></label>
        <div class="form-actions">
            <button class="btn" type="submit"><?php echo $isEdit ? 'Update Patient' : 'Save Patient'; ?></button>
            <a class="btn-muted" href="patients.php">Cancel</a>
        </div>
    </form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
