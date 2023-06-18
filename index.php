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

// Handle logout request
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle delete folder request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $folderName = $_GET['delete'];

    // Ensure the folder name is valid and prevent directory traversal
    if (preg_match('/^[a-zA-Z0-9_-]+$/', $folderName)) {
        $folderPath = 'uploads/' . $folderName;

        // Check if the folder exists
        if (is_dir($folderPath)) {
            // Delete the folder and its contents
            $success = deleteFolder($folderPath);

            if ($success) {
                $deleteMessage = "Folder deleted successfully.";
            } else {
                $deleteError = "Failed to delete the folder.";
            }
        } else {
            $deleteError = "Folder not found.";
        }
    } else {
        $deleteError = "Invalid folder name.";
    }
}

/**
 * Function to delete a folder and its contents recursively.
 *
 * @param string $folderPath The path to the folder to delete.
 * @return bool True if the folder was deleted successfully, false otherwise.
 */
function deleteFolder($folderPath)
{
    if (!is_dir($folderPath)) {
        return false;
    }

    $files = array_diff(scandir($folderPath), array('.', '..'));

    foreach ($files as $file) {
        $filePath = $folderPath . '/' . $file;

        if (is_dir($filePath)) {
            deleteFolder($filePath);
        } else {
            unlink($filePath);
        }
    }

    return rmdir($folderPath);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .folder-item {
            margin-bottom: 10px;
        }
        .delete-btn {
            display: none;
        }
        <?php if ($loggedIn) { ?>
        .delete-btn {
            display: inline-block;
        }
        <?php } ?>
    </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

</head>

<body>
    <div class="container">
        <h1 class="mt-4">Dashboard</h1>
        <?php if ($loggedIn) { ?>
            <button class="btn btn-primary" onclick="extractZip()">Add New</button>
            <a href="changepassword.php" class="btn btn-primary">Change Password</a>
            <a href="?logout" class="btn btn-secondary">Logout</a>
        <?php } ?>
        <div id="folders" class="mt-4">
            <?php include 'list.php'; ?>
        </div>
    </div>

    <script>
        // Function to extract ZIP file
        function extractZip() {
            // Send an AJAX request to extract.php
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'extract.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Reload the page to display the updated folder list
                    location.reload();
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
