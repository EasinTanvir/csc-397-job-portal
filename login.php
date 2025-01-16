<?php
session_start(); // Start the session

include 'includes/header.php';
include 'includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid credentials";
        }
    } else {
        $message = "Invalid credentials";
    }
}

// Check if account creation was successful
if (isset($_SESSION['account_created'])) {
    echo '<script>alert("' . $_SESSION['account_created'] . '");</script>';
    unset($_SESSION['account_created']); // Clear the session variable after showing the message
}
?>

<link rel="stylesheet" href="styles/login.css">
<div class="login-container">
    <h2>Login</h2>
    <?php if ($message): ?>
    <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>
