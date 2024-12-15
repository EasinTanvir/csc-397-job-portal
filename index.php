<?php

require_once 'includes/db.php';
require_once 'includes/header.php';

$limit = 8; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


$query = "SELECT * FROM jobs ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();


$countQuery = "SELECT COUNT(*) AS total FROM jobs";
$totalJobsResult = $conn->query($countQuery);
$totalJobs = $totalJobsResult->fetch_assoc()['total'];
$totalPages = ceil($totalJobs / $limit);
?>
<link rel="stylesheet" href="styles/home.css">
<div class="jobs-container">
    <?php if ($result->num_rows > 0): ?>
    <?php while ($job = $result->fetch_assoc()): ?>
    <div class="job-card">
        <img src="assets/images/placeholder.png" alt="Job Placeholder" class="job-image">
        <div class="job-content">
            <h3 class="job-title"><?= htmlspecialchars($job['title']) ?></h3>
            <p class="job-description"><?= htmlspecialchars($job['description']) ?></p>
            <a href="jobdetails.php?job_id=<?= $job['id'] ?>" class="btn">View Details</a>
        </div>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
    <p>No jobs posted yet.</p>
    <?php endif; ?>
</div>


<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

<?php require_once 'includes/footer.php'; ?>