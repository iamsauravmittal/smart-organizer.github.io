<?php
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Initialize the header text variable
$headerText = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted header text
    $headerText = $_POST['header_text'];

    // Store the header text in the header data file
    file_put_contents('header_data.php', '<?php $headerText = ' . var_export($headerText, true) . ';');

    // Display a success message
    $successMessage = "Header updated successfully.";
} else {
    // Include the header data file
    include 'header_data.php';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Description</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Change Description</h1>

        <?php if ($loggedIn): ?>
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="header_text">Description</label>
                    <input type="text" class="form-control" name="header_text" id="header_text" value="<?php echo htmlspecialchars($headerText); ?>" required placeholder="Please provide the description for your file/folder here.">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>

            </form>
            <?php else: ?>
            <p>You need to <a href="login.php">log in</a> to change the description.</p>
        <?php endif; ?>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
