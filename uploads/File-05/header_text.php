<?php
// Check if the header data file exists
$headerFile = 'header_data.php';
if (file_exists($headerFile)) {
    include $headerFile;
} else {
    // Default header text
    $headerText = 'Editable List';
}
?>
