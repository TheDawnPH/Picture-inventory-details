<?php
session_start();

// Include config file
include 'config.php';

// Select all assets, ordered by creation date
$sql = "SELECT * FROM assets ORDER BY created_at DESC WHERE visible = '1'";
$result = mysqli_query($conn, $sql);
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
        .table th, .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #343a40;
            color: #fff;
        }

        .no-assets {
            text-align: center;
            font-size: 1.2em;
            color: #6c757d;
        }

        .img-thumbnail {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container my-4">
        <h1 class="text-center mb-4">View Assets</h1>

        <!-- Search Bar -->
        <div class="row mb-3">
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Search assets..." oninput="this.value = this.value.toUpperCase()">
            </div>
        </div>

        <!-- Sorting Dropdown -->
        <div class="row mb-4">
            <div class="col">
                <select id="sort" class="form-select">
                    <option value="">Sort by...</option>
                    <option value="0">Sort by Asset Tag</option>
                    <option value="1">Sort by Serial Number</option>
                </select>
            </div>
        </div>

        <!-- Download Button -->
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="download_images.php" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download All Images
                </a>
            </div>
        </div>

        <!-- Assets Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Asset Tag</th>
                        <th>Serial Number</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['asset_tag']); ?></td>
                                <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                                <td>
                                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Asset Image" class="img-thumbnail">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-assets">No assets found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        $(document).ready(function() {
            // Search functionality
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('table tbody tr').each(function() {
                    var rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(value) > -1);
                });
            });

            // Sorting functionality
            $('#sort').on('change', function() {
                var index = $(this).val();
                var rows = $('table tbody tr').get();

                rows.sort(function(a, b) {
                    var A = $(a).children('td').eq(index).text().toUpperCase();
                    var B = $(b).children('td').eq(index).text().toUpperCase();

                    return A.localeCompare(B);
                });

                $.each(rows, function(index, row) {
                    $('table tbody').append(row);
                });
            });
        });
    </script>

</body>

</html>
