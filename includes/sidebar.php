<?php
declare(strict_types=1);
$scriptName = basename($_SERVER['PHP_SELF']);
function isActiveNav(string $file, string $scriptName): string {
    return $file === $scriptName ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
    <div class="brand">🌿 Barangay Health</div>
    <nav>
        <a class="<?php echo isActiveNav('dashboard.php', $scriptName); ?>" href="dashboard.php">Dashboard</a>
        <a class="<?php echo isActiveNav('patients.php', $scriptName); ?>" href="patients.php">Patients</a>
        <a class="<?php echo isActiveNav('health_records.php', $scriptName); ?>" href="health_records.php">Health Records</a>
        <a class="<?php echo isActiveNav('reports.php', $scriptName); ?>" href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>
