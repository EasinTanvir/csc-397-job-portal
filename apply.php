<?php
session_start();
require_once 'includes/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['job_id'], $_POST['name'], $_POST['email'], $_POST['expected_salary'])) {
        die("Invalid input.");
    }

    $user_id = $_SESSION['user_id'];
    $job_id = $_POST['job_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $expected_salary = $_POST['expected_salary'];

    // Check if the user has already applied for this job
    $checkQuery = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ii', $user_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('You have already applied for this job!'); window.location.href='jobdetails.php?job_id=$job_id';</script>";
        exit();
    }

    // Insert the application into the applications table
    $insertQuery = "INSERT INTO applications (user_id, job_id, name, email, expected_salary) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('iissd', $user_id, $job_id, $name, $email, $expected_salary);

    if ($stmt->execute()) {
        echo "<script>alert('Application successful!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again later.'); window.location.href='jobdetails.php?job_id=$job_id';</script>";
    }
}
?>