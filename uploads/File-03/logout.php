<?php
    session_start();
    session_unset();
    session_destroy();

    // Redirect to the main page
    header('Location: index.php');
    exit;
?>
