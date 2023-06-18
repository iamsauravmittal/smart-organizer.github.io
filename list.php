<?php
$folderPath = 'uploads/';
$folders = glob($folderPath . '*', GLOB_ONLYDIR);
?>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">File/Folder Name</th>
                <th scope="col" class="text-center" style="width: 1%;">Open</th>
                    <?php if ($loggedIn) { ?>
                <th scope="col" class="text-center" style="width: 1%;">Delete</th>
                    <?php } ?>
                <th scope="col" class="text-center" style="width: 10%;">Generate</th>
                <th scope="col" class="text-center" style="width: 10%;">QR Code</th>
            </tr>
        </thead>
        <tbody>
<?php
foreach ($folders as $folder) {
    $folderName = basename($folder);
    $openLink = $folder;
    $currentPath = $_SERVER['PHP_SELF'];
    $baseUrl = 'https://' . $_SERVER['HTTP_HOST'];
    $path = str_replace(['/login.php', '/index.php'], '', $currentPath) . '/uploads/' . $folderName;
    $qrContent = $baseUrl . $path;
    $qrCodeId = 'qr-code-' . $folderName;
?>

            <tr>
                <td><?php echo $folderName; ?></td>
                                    <td>
                        <a href="<?php echo $openLink; ?>" target="_blank" class="btn btn-sm btn-primary">Open</a>
                    </td>
                <?php if ($loggedIn) { ?>
                    <td><button class="btn btn-sm btn-danger delete-btn" onclick="deleteFolder('<?php echo $folderName; ?>')">Delete</button></td>
                <?php } ?>
                    <td>
            <div class="d-flex align-items-center">
                <button id="generateBtn" class="btn btn-primary btn-sm mr-2" onclick="generateQRCode('<?php echo $qrContent; ?>', '<?php echo $qrCodeId; ?>'); this.disabled = true;">Generate</button>
            <td>
    <div id="<?php echo $qrCodeId; ?>">
  </div>
            </td>
</div>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function generateQRCode(qrContent, qrCodeId) {
        let qrCode = new QRCode(qrCodeId, {
            text: qrContent,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
        });
    }
</script>
<script>
    function deleteFolder(folderName) {
        var confirmation = confirm("Are you sure you want to delete the folder?");
        if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'delete.php?folder=' + folderName, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send();
        }
    }
</script>
