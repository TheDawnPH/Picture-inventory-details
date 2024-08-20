<?php
// if anyone visit this page redirect to 404 page
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header("location: 404.php");
    exit;
}

// mkdir and change permission upload folder in windows
$cmd = "mkdir uploads && icacls uploads /grant Everyone:F /t";
exec($cmd);

// mkdir and change permission upload folder in linux
$cmd = "mkdir uploads && chmod 777 uploads";
exec($cmd);

date_default_timezone_set('Asia/Manila');

// database connection
// Database connection
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'inventory');
define('DB_PORT', '3306');

// Get connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
} else {
    // create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS assets (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        asset_tag text NOT NULL,
        serial_number TEXT NOT NULL,
        image_path text NOT NULL,
        visible BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if (mysqli_query($conn, $sql)) {
        // echo "Table created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}
?>