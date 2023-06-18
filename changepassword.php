<?php
// Start the session
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Redirect to login page if not logged in
if (!$loggedIn) {
    header("Location: login.php");
    exit();
}

// Handle change password form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newPassword'])) {
    $newPassword = $_POST['newPassword'];

    // Save the new password to the password.txt file
    $passwordFile = 'password.txt';
    file_put_contents($passwordFile, $newPassword);

    // Display success message
    $successMessage = "Password changed successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Change Password</h1>
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label for="newPassword">New Password:</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required placeholder="Enter new password">
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
            <?php if (isset($successMessage)) { ?>
                <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
            <?php } ?>
        </form>
    </div>
</body>
</html>
