<?php

// Include config file
include 'config.php';

// Fetch all image paths, asset tags, and serial numbers from the database
$sql = "SELECT asset_tag, serial_number, image_path FROM assets";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Create a new ZIP archive
    $zip = new ZipArchive();
    $zipFilename = 'assets_images_' . date('Y-m-d_H-i-s') . '.zip';

    if ($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE) {
        exit("Cannot open <$zipFilename>\n");
    }

    // Add images to the ZIP archive
    while ($row = mysqli_fetch_assoc($result)) {
        $assetTag = $row['asset_tag'];
        $serialNumber = $row['serial_number'];
        $imagePath = $row['image_path'];

        // Ensure the file exists
        if (file_exists($imagePath)) {
            // Create a folder path based on asset tag and serial number
            $folderName = $assetTag . '_' . $serialNumber;
            $relativePath = $folderName . '/' . basename($imagePath);

            // Add the file to the ZIP archive under the correct folder
            $zip->addFile($imagePath, $relativePath);
        } else {
            // Optionally log or handle missing files
            error_log("File not found: $imagePath");
        }
    }

    // Close the ZIP archive
    $zip->close();

    // Set headers to prompt the download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
    header('Content-Length: ' . filesize($zipFilename));

    // Read and output the ZIP file
    readfile($zipFilename);

    // Optionally, delete the ZIP file from the server after download
    unlink($zipFilename);
} else {
    echo "No images found to download.";
}

// Close the database connection
mysqli_close($conn);
?>
