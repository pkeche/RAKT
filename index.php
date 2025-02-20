<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAKT - Blood Bank Management</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/blood-drop.svg" type="image/x-icon">

    <style>
        /* General Layout */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #f5f5dc;
        }

        /* Navbar */
        .navbar {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .navbar-brand {
            font-size: 22px;
            letter-spacing: 2px;
            color: #fff !important;
            transition: all 0.3s ease-in-out;
        }

        .navbar-brand:hover {
            transform: scale(1.1);
            text-shadow: 0px 2px 6px rgba(255, 255, 255, 0.3);
        }

        .navbar-nav .nav-item a {
            position: relative;
            color: #fff !important;
            text-transform: uppercase;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .navbar-nav .nav-item a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff !important;
            transform: scale(1.05);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu {
            background-color: #d9534f;
            border: none;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu a {
            color: #fff !important;
            transition: background 0.3s ease-in-out;
        }

        .dropdown-menu a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Hero Section */
        .hero-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 110px 15px 18px;
            opacity: 0;
            transform: translateY(30px);
        }

        .hero-section.fade-in-active {
            animation: fadeIn 1.2s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-section h1 {
            font-size: 2.5rem;
        }

        .hero-section p {
            font-size: 1.55rem;
            margin-top: 15px;
            color: #444;
        }

        /* Enhanced Buttons */
        .hero-section .btn {
            font-size: 1.2rem;
            padding: 10px 30px;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
            background-color: #d9534f;
            color: #fff;
            font-weight: 600;
            border: none;
        }

        .hero-section .btn:hover {
            background-color: #c8322d;
            color: white;
            transform: scale(1.08);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        footer {
            background-color: #d9534f;
            color: #fff;
            padding: 12px;
            font-size: 20px;
            text-align: center;
            width: 100%;
            position: relative;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 15px 40px;
            }

            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .hero-section img {
                max-width: 95%;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-shading" style="background-color:#d9534f;">
        <a class="navbar-brand" href="index.php">RAKT</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="patient/register.php">
                        <i class="fa fa-user-plus"></i> Register
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="patient/login.php">
                        <i class="fa fa-sign-in"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Section -->
    <div class="container-fluid hero-section fade-in">
        <h1>Blood Bank Management System</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-6 text-center">
                <p class="lead">
                    Our system ensures efficient blood donation management, connecting donors and recipients for life-saving blood availability.
                </p>
                <p class="lead">
                    Be a hero—join us today and help save lives!
                </p>
                <a href="donor/register.php" class="btn btn-danger btn-lg mt-3">
                    <i class="fa fa-heart"></i> Save a Life
                </a>
                <br>
                <a href="patient/register.php" class="btn btn-danger btn-lg mt-3">
                    <i class="fa fa-heart"></i> Request Blood
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="images/home.svg" alt="Blood Donation" class="img-fluid">
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        Group "EPICS130". All rights reserved. &copy; 2025
    </footer>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for Fade-in Effect -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".hero-section").classList.add("fade-in-active");
        });
    </script>

</body>
</html>
