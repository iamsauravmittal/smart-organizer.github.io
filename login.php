<?php
// Start the session
session_start();

// Define the path to the password file
$passwordFile = 'password.txt';

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Redirect to the index page
    header("Location: index.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the submitted password matches the stored password
    $submittedPassword = $_POST['password'];
    $storedPassword = file_get_contents($passwordFile);

    if (trim($submittedPassword) === trim($storedPassword)) {
        // Set the login session variable
        $_SESSION['loggedin'] = true;

        // Redirect to the index page
        header("Location: index.php");
        exit();
    } else {
        // Password is incorrect, display error message
        $error = "Invalid password!";
    }
}

// Handle change password form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newPassword']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $newPassword = $_POST['newPassword'];

    // Save the new password to the password file
    file_put_contents($passwordFile, $newPassword);

    // Display success message
    $successMessage = "Password changed successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart Organizer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

</head>
<body>
    <div class="container">
        <h1 class="mt-4">Smart Organizer</h1>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="form-group">
                <label for="password">You need to log in to make changes.</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
            <hr>

            <h2>Change Password</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="newPassword">New Password:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
            <?php if (isset($successMessage)) { ?>
                <div class="alert alert-success mt-3"><?php echo $successMessage; ?></div>
            <?php } ?>
        <?php } ?>

        <h2 class="mt-4">File/Folders List:</h2>
        <?php include 'list.php'; ?>
    </div>
</body>
</html>