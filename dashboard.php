<?php
include 'includes/header.php';
include 'includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle job creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_job'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $salary = $_POST['salary'];
    $shift = $_POST['shift'];

    $query = $conn->prepare("INSERT INTO jobs (title, description, type, salary, shift, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("sssisi", $title, $description, $type, $salary, $shift, $user_id);

    if ($query->execute()) {
        $message = "Job created successfully!";
    } else {
        $message = "Error: " . $query->error;
    }
}

// Handle job deletion
if (isset($_GET['delete'])) {
    $job_id = $_GET['delete'];

    $query = $conn->prepare("DELETE FROM jobs WHERE id = ? AND user_id = ?");
    $query->bind_param("ii", $job_id, $user_id);

    if ($query->execute()) {
        $message = "Job deleted successfully!";
    } else {
        $message = "Error: " . $query->error;
    }
}

// Handle job edit (fetch data)
$edit_job = null;
if (isset($_GET['edit'])) {
    $job_id = $_GET['edit'];

    $query = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND user_id = ?");
    $query->bind_param("ii", $job_id, $user_id);
    $query->execute();
    $result = $query->get_result();
    $edit_job = $result->fetch_assoc();
}

// Handle job update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_job'])) {
    $job_id = $_POST['job_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $salary = $_POST['salary'];
    $shift = $_POST['shift'];

    $query = $conn->prepare("UPDATE jobs SET title = ?, description = ?, type = ?, salary = ?, shift = ? WHERE id = ? AND user_id = ?");
$query->bind_param("sssissi", $title, $description, $type, $salary, $shift, $job_id, $user_id);

    if ($query->execute()) {
        $message = "Job updated successfully!";
        $edit_job = null;
    } else {
        $message = "Error: " . $query->error;
    }
}

// Fetch all jobs posted by the user
$query = $conn->prepare("SELECT * FROM jobs WHERE user_id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$jobs = $query->get_result();
?>

<link rel="stylesheet" href="styles/dashboard.css">
<div class="dashboard-container">
    <h2>Dashboard</h2>
    <p><?php echo $message; ?></p>

    <!-- Job Creation Form -->
    <div class="form-container">
        <h3><?php echo $edit_job ? "Edit Job" : "Create Job"; ?></h3>
        <form method="POST">
            <input type="hidden" name="job_id" value="<?php echo $edit_job['id'] ?? ''; ?>">
            <input type="text" name="title" placeholder="Job Title" value="<?php echo $edit_job['title'] ?? ''; ?>"
                required>
            <textarea name="description" placeholder="Job Description"
                required><?php echo $edit_job['description'] ?? ''; ?></textarea>
            <select name="type" required>
                <option value="" disabled <?php echo empty($edit_job['type']) ? 'selected' : ''; ?>>Select Job Type
                </option>
                <option value="Full Time"
                    <?php echo isset($edit_job['type']) && $edit_job['type'] == 'Full Time' ? 'selected' : ''; ?>>Full
                    Time</option>
                <option value="Part Time"
                    <?php echo isset($edit_job['type']) && $edit_job['type'] == 'Part Time' ? 'selected' : ''; ?>>Part
                    Time</option>
            </select>
            <input type="number" step="0.01" name="salary" placeholder="Salary"
                value="<?php echo $edit_job['salary'] ?? ''; ?>" required>
            <input type="text" name="shift" placeholder="Shift (e.g., Morning, Evening)"
                value="<?php echo $edit_job['shift'] ?? ''; ?>">
            <button type="submit" name="<?php echo $edit_job ? "update_job" : "create_job"; ?>">
                <?php echo $edit_job ? "Update Job" : "Create Job"; ?>
            </button>
        </form>
    </div>

    <!-- Job List -->
    <div class="jobs-list">
        <h3>Your Craeted Jobs List</h3>
        <?php if ($jobs->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Titles</th>
                    <th>Type</th>
                    <th>Salary</th>
                    <th>Shift</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $job['title']; ?></td>
                    <td><?php echo $job['type']; ?></td>
                    <td><?php echo $job['salary']; ?></td>
                    <td><?php echo $job['shift']; ?></td>
                    <td>
                        <a href="dashboard.php?edit=<?php echo $job['id']; ?>">Edit</a> |
                        <a href="dashboard.php?delete=<?php echo $job['id']; ?>"
                            onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No jobs found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>