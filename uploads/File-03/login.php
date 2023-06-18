<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
        session_start();

        // Check if the user is already logged in
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header("Location: index.php");
            exit;
        }

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];

            // Retrieve the stored password from the file
            $passwordFile = 'password.txt';
            $storedPassword = file_get_contents($passwordFile);

            // Check the entered password against the stored password
            if ($password === $storedPassword) {
                $_SESSION['logged_in'] = true;
                $successMessage = "Login successful. Redirecting to the dashboard...";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 1000);
                      </script>";
            } else {
                $error = "Invalid password.";
            }
        }
    ?>

    <div class="container">
        <h1 class="mt-4">Login</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php elseif (isset($successMessage)): ?>
            <div class="alert alert-success mt-4"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter Password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
