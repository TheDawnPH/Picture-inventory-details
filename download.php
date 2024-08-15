<?php
// Download all images as a zip file with folders based on asset tag and serial number
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Include config file
    include 'config.php';

    // Initialize variables
    $zip = new ZipArchive();
    $zip_name = "assets.zip";

    // Create a zip file
    if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE) {
        // Get all assets
        $sql = "SELECT * FROM assets";
        if ($result = mysqli_query($conn, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $asset_tag = $row['asset_tag'];
                    $serial_number = $row['serial_number'];
                    $image_path = $row['image_path'];

                    // Add image to zip file
                    $zip->addFile($image_path, "$asset_tag/$serial_number.jpg");
                }
                mysqli_free_result($result);
            } else {
                echo "No assets found";
            }
        } else {
            echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
        }

        // Close zip file
        $zip->close();
    } else {
        echo "ERROR: Could not create $zip_name";
    }

    // Download zip file
    if (file_exists($zip_name)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zip_name . '"');
        header('Content-Length: ' . filesize($zip_name));
        readfile($zip_name);
        unlink($zip_name);
    } else {
        echo "ERROR: $zip_name does not exist";
    }
}

?>