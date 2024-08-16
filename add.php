<?php
session_start();

// Generate CSRF token if it does not exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Include config file
include 'config.php';

// Initialize variables
$asset_tag = $serial_number = $image_path = "";
$asset_tag_err = $serial_number_err = $image_path_err = "";

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    // Validate asset tag
    $input_asset_tag = trim($_POST["asset_tag"]);
    if (empty($input_asset_tag)) {
        $asset_tag_err = "Please enter an asset tag.";
    } else {
        // Check for duplicate asset tag
        $sql = "SELECT id FROM assets WHERE asset_tag = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_asset_tag);
            $param_asset_tag = $input_asset_tag;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $asset_tag_err = "This asset tag is already taken.";
                } else {
                    $asset_tag = $input_asset_tag;
                }
            } else {
                $f = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate serial number
    $input_serial_number = trim($_POST["serial_number"]);
    if (empty($input_serial_number)) {
        $serial_number_err = "Please enter a serial number.";
    } else {
        // Check for duplicate serial number
        $sql = "SELECT id FROM assets WHERE serial_number = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_serial_number);
            $param_serial_number = $input_serial_number;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $serial_number_err = "This serial number is already taken.";
                } else {
                    $serial_number = $input_serial_number;
                }
            } else {
                $f = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate image path
    if (isset($_FILES['image_path']['name'])) {
        $input_image_path = $_FILES['image_path']['name'];
        if (empty($input_image_path)) {
            $image_path_err = "Please select an image.";
        } else {
            $image_path = $input_image_path;
        }
    }

    // Check for errors before inserting into the database
    if (empty($asset_tag_err) && empty($serial_number_err) && empty($image_path_err) && empty($f)) {
        $sql = "INSERT INTO assets (asset_tag, serial_number, image_path) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $param_asset_tag, $param_serial_number, $param_image_path);

            // Set up directories and file paths
            $target_dir = "uploads/" . $asset_tag . "_" . $serial_number . "/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if file is an image
            $check = getimagesize($_FILES["image_path"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $image_path_err = "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                $image_path_err = "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                $image_path_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Upload the file if all checks pass
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file)) {
                    // File successfully uploaded
                } else {
                    $image_path_err = "Sorry, there was an error uploading your file.";
                }
            }

            // Set parameters and execute insert
            $param_asset_tag = $asset_tag;
            $param_serial_number = $serial_number;
            $param_image_path = $target_file;

            if (mysqli_stmt_execute($stmt)) {
                $s = "Asset added successfully.";
            } else {
                $f = "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Picture Inventory System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        h1 {
            margin-bottom: 30px;
        }

        .btn-dark {
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1 class="text-center">Add Asset</h1>
        <?php if (isset($s)) { ?>
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($s); ?>
            </div>
        <?php } ?>
        <?php if (isset($f)) { ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($f); ?>
            </div>
        <?php } ?>
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form method="post" enctype="multipart/form-data" autocomplete="off" id="form" name="form">
                    <!-- Add CSRF token as a hidden input -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <div class="mb-3">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" class="form-control <?php echo (!empty($asset_tag_err)) ? 'is-invalid' : ''; ?>" id="asset_tag" name="asset_tag" value="<?php echo $asset_tag; ?>" oninput="this.value = this.value.toUpperCase()" required maxlength="15"
                            <div class="invalid-feedback"><?php echo $asset_tag_err; ?>
                    </div>
            </div>

            <div class="mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control <?php echo (!empty($serial_number_err)) ? 'is-invalid' : ''; ?>" id="serial_number" name="serial_number" value="<?php echo $serial_number; ?>" oninput="this.value = this.value.toUpperCase()" required>
                <div class="invalid-feedback"><?php echo $serial_number_err; ?></div>
            </div>

            <div class="mb-3">
                <label for="image_path" class="form-label">Image</label>
                <input type="file" class="form-control <?php echo (!empty($image_path_err)) ? 'is-invalid' : ''; ?>" id="image_path" name="image_path" accept="image/*" capture="camera" required>
                <div class="invalid-feedback"><?php echo $image_path_err; ?></div>
            </div>

            <div class="mb-3 justify-content-center d-flex">
                <button type="submit" class="btn btn-dark btn-lg">
                    <i class="bi bi-save"></i> Save Asset Information
                </button>
            </div>
            </form>
            <script>
                document.querySelector('form').onsubmit = e => {
                    e.target.submit();
                    e.target.reset();
                    return false;
                };
            </script>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

</body>

</html>