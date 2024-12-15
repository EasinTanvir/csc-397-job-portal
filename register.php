<?php
include 'includes/header.php';
include 'includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $query->bind_param("sss", $username, $email, $password);

    if ($query->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $message = "Error: " . $query->error;
    }
}
?>
<link rel="stylesheet" href="styles/login.css">
<div class="login-container">
    <h2>Register</h2>
    <?php if ($message): ?>
    <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>