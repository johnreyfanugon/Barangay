<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Invalid request.');
    redirect('../patients.php');
}

$db = getDb();
$action = (string)($_POST['action'] ?? '');

if ($action === 'create' || $action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = sanitizeText((string)($_POST['name'] ?? ''));
    $birthdate = (string)($_POST['birthdate'] ?? '');
    $gender = sanitizeText((string)($_POST['gender'] ?? ''));
    $address = sanitizeText((string)($_POST['address'] ?? ''));
    $contact = sanitizeText((string)($_POST['contact'] ?? ''));

    if ($name === '' || $birthdate === '' || $gender === '' || $address === '' || $contact === '') {
        setFlash('error', 'All patient fields are required.');
        redirect($action === 'create' ? '../patient_form.php' : '../patient_form.php?id=' . $id);
    }

    if ($action === 'create') {
        $dupStmt = $db->prepare('SELECT id FROM patients WHERE name = ? AND birthdate = ? LIMIT 1');
        $dupStmt->execute([$name, $birthdate]);
        if ($dupStmt->fetch()) {
            setFlash('error', 'Duplicate patient exists (same name and birthdate).');
            redirect('../patient_form.php');
        }

        $uniqueId = generatePatientUniqueId($db);
        $stmt = $db->prepare('INSERT INTO patients (unique_id, name, birthdate, gender, address, contact) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$uniqueId, $name, $birthdate, $gender, $address, $contact]);
        setFlash('success', 'Patient added successfully.');
        redirect('../patients.php');
    } else {
        $dupStmt = $db->prepare('SELECT id FROM patients WHERE name = ? AND birthdate = ? AND id <> ? LIMIT 1');
        $dupStmt->execute([$name, $birthdate, $id]);
        if ($dupStmt->fetch()) {
            setFlash('error', 'Another patient already has the same name and birthdate.');
            redirect('../patient_form.php?id=' . $id);
        }

        $stmt = $db->prepare('UPDATE patients SET name = ?, birthdate = ?, gender = ?, address = ?, contact = ? WHERE id = ?');
        $stmt->execute([$name, $birthdate, $gender, $address, $contact, $id]);
        setFlash('success', 'Patient updated successfully.');
        redirect('../patients.php');
    }
}

if ($action === 'delete') {
    requireRole(['Admin']);
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $db->prepare('DELETE FROM patients WHERE id = ?');
    $stmt->execute([$id]);
    setFlash('success', 'Patient deleted successfully.');
    redirect('../patients.php');
}

setFlash('error', 'Unknown action.');
redirect('../patients.php');
