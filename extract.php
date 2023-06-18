<?php
// Retrieve the current count from a text file
$count = intval(file_get_contents("count.txt"));

// Increment the count
$count++;

// Store the updated count back in the text file
file_put_contents("count.txt", $count);

// Generate the file name with the sequential count
$fileName = "File-" . str_pad($count, 2, "0", STR_PAD_LEFT);

// Extract the ZIP file to the unique folder
$extractPath = "uploads/" . $fileName . "/";
mkdir($extractPath);
$zip = new ZipArchive();

if ($zip->open("main.zip") === true) {
    $zip->extractTo($extractPath);
    $zip->close();
    echo "ZIP file extracted successfully to: " . $extractPath;
} else {
    echo "Failed to extract the ZIP file.";
}
?>
