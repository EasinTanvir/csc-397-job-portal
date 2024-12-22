<?php
include 'includes/header.php';
include 'includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $check_email_query = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $check_email_query->bind_param("s", $email);
    $check_email_query->execute();
    $check_email_query->bind_result($email_exists);
    $check_email_query->fetch();
    $check_email_query->close();

    if ($email_exists > 0) {
        $message = "Error: The email is already registered.";
    } else {
        // Proceed to insert the user if email doesn't exist
        $query = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $username, $email, $password);

        if ($query->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $message = "Error: " . $query->error;
        }
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
