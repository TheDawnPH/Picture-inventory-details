<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Asset Picture Inventory System</title>
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

        .hero p {
            font-size: 1.5rem;
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
        
        .error-container {
            padding: 50px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>404 - Page Not Found</h1>
            <p class="lead">The page you're looking for doesn't exist.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5 error-container">
        <div class="row">
            <div class="col-md-12">
                <a href="/" class="btn btn-warning btn-lg">
                    <i class="bi bi-house-door feature-icon"></i><br>
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>

</html>
