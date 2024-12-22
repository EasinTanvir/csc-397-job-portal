<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['job_id'])) {
    die("Invalid job ID.");
}

$job_id = $_GET['job_id'];
$query = "SELECT * FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc();
?>
<link rel="stylesheet" href="styles/jobdetails.css">

<div class="job-details">
    <h1><?= htmlspecialchars($job['title']) ?></h1>
    <p class="description"><?= htmlspecialchars($job['description']) ?></p>
    <p><strong>Type:</strong> <?= htmlspecialchars($job['type']) ?></p>
    <p><strong>Salary:</strong> $<?= htmlspecialchars($job['salary']) ?></p>
    <p><strong>Shift:</strong> <?= htmlspecialchars($job['shift']) ?></p>

    <?php if (isset($_SESSION['user_id'])): ?>
    <button onclick="openApplicationForm()" class="btn">Apply</button>
    <?php else: ?>
    <a href="login.php" class="btn">Login to Apply</a>
    <?php endif; ?>
</div>

<!-- Application Form Modal -->
<div id="application-form" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeApplicationForm()">&times;</span>
        <h2>Apply for <?= htmlspecialchars($job['title']) ?></h2>
        <form method="POST" action="apply.php">
            <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="expected_salary">Expected Salary:</label>
            <input type="number" step="0.01" id="expected_salary" name="expected_salary" required>

            <button type="submit" class="btn">Submit Application</button>
        </form>
    </div>
</div>

<script>
function openApplicationForm() {
    document.getElementById("application-form").style.display = "block";
}

function closeApplicationForm() {
    document.getElementById("application-form").style.display = "none";
}
</script>

<?php require_once 'includes/footer.php'; ?>
