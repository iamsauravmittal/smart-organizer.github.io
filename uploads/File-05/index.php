<?php
session_start();

// Check if the user is logged in
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

// Initialize the list variable
$list = [];

// Check if the list data file exists
$listFile = 'list_data.php';
if (file_exists($listFile)) {
    include $listFile;
}

// Include the header data file
include 'header_data.php';

// Define a variable to track the success message
$successMessage = '';

// Handle form submission if the user is logged in
if ($loggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form has a delete button with a valid index
    if (isset($_POST['delete']) && is_numeric($_POST['delete'])) {
        $index = $_POST['delete'];
        // Remove the item from the list
        if (isset($list[$index])) {
            array_splice($list, $index, 1);
            $successMessage = 'Document deleted successfully.';
        }
    } elseif (isset($_POST['edit']) && is_numeric($_POST['edit'])) {
        $index = $_POST['edit'];
        // Update the item in the list
        if (isset($list[$index])) {
            $updatedItem = $_POST['item_' . $index];
            $list[$index] = $updatedItem;
            $successMessage = 'Document updated successfully.';
        }
    } else {
        // Add a new item to the list
        $newItem = $_POST['new_item'];
        if (!empty($newItem)) {
            $list[] = $newItem;
            $successMessage = 'Document added successfully.';
        }
    }

    // Save the updated list data to the file
    file_put_contents($listFile, '<?php $list = ' . var_export($list, true) . ';');
}

// Search functionality
$searchResult = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    if (!empty($searchQuery)) {
        foreach ($list as $item) {
            if (stripos($item, $searchQuery) !== false) {
                $searchResult[] = $item;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
        $currentFolder = basename(dirname($_SERVER['PHP_SELF']));?>
    <title><?php echo $currentFolder; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4" >
        <?php
        $currentFolder = basename(dirname($_SERVER['PHP_SELF']));?>
        <?php if ($loggedIn): ?>
        <h1>Dashboard</h1><?php endif; ?>
        <h2>Name: <?php echo $currentFolder; ?></h2>
        <p><i>Description: <?php echo htmlspecialchars($headerText); ?></i></p>

        <?php if ($loggedIn): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" class="form-control" name="new_item" placeholder="Enter new document name" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Documents</button>
            </form>
            <br>
            <a href="changepassword.php" class="btn btn-secondary">Change Password</a>
            <a href="editheader.php" class="btn btn-secondary">Change Description</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        <?php else: ?>
            <p>You need to <a href="login.php">log in</a> to make changes.</p>
            <form method="GET" action="">
                <div class="form-group">
                    <input type="text" class="form-control" name="search" placeholder="Search documents">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
<?php
$currentFolder = basename(dirname($_SERVER['PHP_SELF']));
$homeUrl = str_replace('/uploads/' . $currentFolder, '', $_SERVER['REQUEST_URI']);
?>

<a href="<?php echo $homeUrl; ?>" class="btn btn-secondary">Back to Home</a>


            </form>
        <?php endif; ?>

        <?php if (!empty($searchResult)): ?>
            <h2 class="mt-4">Search Results:</h2>
            <div class="table-responsive">
     <table class="table table-bordered">
        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResult as $item): ?>
                            <tr>
                                <td><?php echo $item; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (!empty($list)): ?>
            <h2 class="mt-4">Document List:</h2>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Document</th>
                            <?php if ($loggedIn): ?>
                                <th scope="col" class="text-center" style="width: 1%;">Edit</th>
                                <th scope="col" class="text-center" style="width: 1%;">Delete</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $index => $item): ?>
                            <tr>
                                <td style="width: 85%;">
                                    <?php if ($loggedIn): ?>
                                        <form method="POST" action="">
                                            <input type="text" class="form-control" name="item_<?php echo $index; ?>" value="<?php echo htmlspecialchars($item); ?>" required placeholder="Enter document name">
                                </td>
                                <td class="text-center" style="width: 7%;">
                                    <input type="hidden" name="edit" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($item); ?>
                                    <?php endif; ?>
                                </td>
                                <?php if ($loggedIn): ?>
                                    <td class="text-center" style="width: 7%;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="delete" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Document?')">Delete</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="mt-4">No documents in the list.</p>
        <?php endif; ?>
    </div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
