<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Invalid request.');
    redirect('../health_records.php');
}

$db = getDb();
$action = (string)($_POST['action'] ?? '');

if ($action === 'create' || $action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $patientId = (int)($_POST['patient_id'] ?? 0);
    $date = (string)($_POST['date'] ?? '');
    $bp = sanitizeText((string)($_POST['bp'] ?? ''));
    $temp = (string)($_POST['temp'] ?? '');
    $symptoms = sanitizeText((string)($_POST['symptoms'] ?? ''));
    $diagnosis = sanitizeText((string)($_POST['diagnosis'] ?? ''));
    $treatment = sanitizeText((string)($_POST['treatment'] ?? ''));

    if ($patientId <= 0 || $date === '' || $bp === '' || $temp === '' || $symptoms === '' || $diagnosis === '' || $treatment === '') {
        setFlash('error', 'Please complete all health record fields.');
        redirect('../health_records.php' . ($action === 'update' ? '?edit=' . $id : ''));
    }

    if ($action === 'create') {
        $stmt = $db->prepare('INSERT INTO health_records (patient_id, date, bp, temp, symptoms, diagnosis, treatment) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issdsss', $patientId, $date, $bp, $temp, $symptoms, $diagnosis, $treatment);
        $stmt->execute();
        $stmt->close();
        setFlash('success', 'Health record created.');
        redirect('../health_records.php?patient_id=' . $patientId);
    } else {
        $stmt = $db->prepare('UPDATE health_records SET patient_id = ?, date = ?, bp = ?, temp = ?, symptoms = ?, diagnosis = ?, treatment = ? WHERE id = ?');
        $stmt->bind_param('issdsssi', $patientId, $date, $bp, $temp, $symptoms, $diagnosis, $treatment, $id);
        $stmt->execute();
        $stmt->close();
        setFlash('success', 'Health record updated.');
        redirect('../health_records.php?patient_id=' . $patientId);
    }
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $db->prepare('DELETE FROM health_records WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    setFlash('success', 'Health record deleted.');
    redirect('../health_records.php');
}

setFlash('error', 'Unknown action.');
redirect('../health_records.php');
