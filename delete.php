<?php
if (isset($_GET['folder'])) {
    $folderName = $_GET['folder'];

    // Ensure the folder name is valid and prevent directory traversal
    if (preg_match('/^[a-zA-Z0-9_-]+$/', $folderName)) {
        $folderPath = 'uploads/' . $folderName;

        // Check if the folder exists
        if (is_dir($folderPath)) {
            // Delete the folder and its contents
            $success = deleteFolder($folderPath);

            if ($success) {
                echo "Folder deleted successfully.";
            } else {
                echo "Failed to delete the folder.";
            }
        } else {
            echo "Folder not found.";
        }
    } else {
        echo "Invalid folder name.";
    }
} else {
    echo "Invalid request.";
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
