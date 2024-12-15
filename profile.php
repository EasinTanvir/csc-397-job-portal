<?php

require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT jobs.* FROM jobs
          JOIN applications ON jobs.id = applications.job_id
          WHERE applications.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<link rel="stylesheet" href="styles/profile.css">
<div class="profile-jobs">
    <h1>Jobs You've Applied To</h1>
    <?php if ($result->num_rows > 0): ?>
    <?php while ($job = $result->fetch_assoc()): ?>
    <div class="job-card">
        <h3><?= htmlspecialchars($job['title']) ?></h3>
        <p><?= htmlspecialchars($job['description']) ?></p>
        <p><strong>Type:</strong> <?= htmlspecialchars($job['type']) ?></p>
        <p><strong>Salary:</strong> $<?= htmlspecialchars($job['salary']) ?></p>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
    <p>You haven't applied for any jobs yet.</p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>