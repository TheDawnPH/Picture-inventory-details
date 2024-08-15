<?php
// accesible only if user is on 172.16.x.x network
if (substr($_SERVER['REMOTE_ADDR'], 0, 8) !== '172.16.') {
    header('Location: 404.php');
    exit;
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
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('hero.jpg') center center / cover;
            color: white;
            text-align: center;
            padding: 100px 0;
        }

        .hero h1 {
            font-size: 3rem;
        }

        .btn-lg {
            width: 100%;
            margin: 10px 0;
        }

        .feature-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to Asset Picture Inventory System</h1>
            <p class="lead">Manage and track your assets with pictures.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row text-center">
            <div class="col-md-6">
                <a href="add" class="btn btn-warning btn-lg">
                    <i class="bi bi-plus-circle feature-icon"></i><br>
                    Add Asset
                </a>
            </div>
            <div class="col-md-6">
                <a href="view" class="btn btn-info btn-lg">
                    <i class="bi bi-eye feature-icon"></i><br>
                    View Assets
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>

</html>