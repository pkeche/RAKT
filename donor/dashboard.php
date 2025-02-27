<?php
    
    require_once __DIR__ . '/../includes/session.inc.php';
    require_once __DIR__ . '/../includes/dbh.inc.php';
    require_once __DIR__ . '/../includes/template.php';
    
    if (!isset($_SESSION["donor"])) {
        header("Location: login.php");
        die();
    }

    if (!isset($_GET['home']) && !isset($_GET["profile"]) && !isset($_GET["donate_blood"]) && !isset($_GET["donations_history"]) && !isset($_GET["logout"])) {
        // Redirect to the same page with the 'blood' parameter added
        header('Location:dashboard.php?home=1');
        die();
    }

    if (isset($_GET["logout"])) {
        // Unset all session variables
        unset($_SESSION["donor"]);
        // Destroy the session
        session_destroy();
        header("Location:../index.php");
        die();
    }

    function print_error(string $error)
    {
        echo '<div class="alert alert-danger alert-dismissible fade show text-center mx-auto" role="alert" style="width: fit-content;">';
        echo $error;
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
        ';
    }

    function check_errors()
    {
        if(isset($_SESSION["donor_error_donate"]))
        {
            $errors = $_SESSION["donor_error_donate"];
            foreach ($errors as $error) {
                print_error($error);
            }
            unset($_SESSION["donor_error_donate"]);
        }
    }

    function check_profile_errors()
    {
        if(isset($_SESSION["donor_error_profile"]))
        {
            $errors = $_SESSION["donor_error_profile"];
            foreach ($errors as $error) {
                print_error($error);
            }
            unset($_SESSION["donor_error_profile"]);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard</title>
    <!-- Include Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/blood-drop.svg" type="image/x-icon">
    <!-- Apply custom styles for the form -->
    <style>
/* General Layout */
html, body {
    height: 100%;
    min-height: 100%;
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

.navbar-nav .nav-item a, .dropdown a {
    position: relative;
    color: #fff !important;
    text-transform: uppercase;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
    margin-right: 10px;
    text-decoration: none;
    overflow: hidden;
}

.navbar-nav .nav-item a:hover {
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff !important;
    transform: scale(1.05);
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
}

.navbar-nav li a:hover {
    color: #1abc9c !important;
}

/* Dropdown Menu */
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

.hero-section h1 {
    font-size: 2.5rem;
}

.hero-section p {
    font-size: 1.55rem;
    margin-top: 15px;
    color: #444;
}

/* Buttons */
.btn, .hero-section .btn {
    font-size: 1.2rem;
    padding: 10px 30px;
    border-radius: 30px;
    transition: all 0.3s ease-in-out;
    background-color: #d9534f;
    color: #fff;
    font-weight: 600;
    border: none;
}

.btn:hover, .hero-section .btn:hover {
    background-color: #c8322d;
    color: white;
    transform: scale(1.08);
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}
        
    </style>
</head>
<body style="background-color: #f5f5dc;">
    <!-- Bootstrap navigation bar with responsive button -->
    <div class="container" style="margin-bottom: 100px;">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-shading" style="background-color:#d9534f;">
    <a class="navbar-brand" href="../index.php" style="color: #fff;font-size:22px;letter-spacing:2px;">RAKT</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav d-flex align-items-center">
            <li class="nav-item">
                <a class="nav-link" href="?home=1" style="color:#fff">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#fff;">
                    <?php echo $_SESSION['donor']; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <a class="dropdown-item" href="?profile=1">Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="?logout=1">Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>



    </div>

    <?php

        if(isset($_GET))
        {
            if(count($_GET) > 1)
            {
                print_error("Link Corrupted!! Correct the link.......");
            }
            else
            {
                $getOne = key($_GET);
            }
        }
        
        if ($getOne && $getOne==='home')
        {

            if (!isset($_SESSION["welcome_donor_message"])) {
                // Display the welcome message
                echo '<div class="alert alert-success alert-dismissible fade show text-center mx-auto" role="alert" style="width: fit-content;">
                        Welcome, ' . $_SESSION["donor"]. '
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    
                // Set a session variable to indicate that the welcome message has been displayed
                $_SESSION["welcome_donor_message"]=true;
            }
            
            $val = reset($_GET);

            if($val!=='1') 
            {
                print_error("Link Corrupted!! Correct the link.......");
                die();
            }
            
            $input = [
                "Donor",
                "Donate",
                "Make a new blood donation appointment.",
                "donate",
                "Donation",
                "View your past blood donation records.",
                "donations"
            ];
            
            home_template($input);

        }
        else if ($getOne && $getOne==='profile')
        {

            $val = reset($_GET);

            if($val!=='1') 
            {
                print_error("Link Corrupted!! Correct the link.......");
                die();
            }

            check_profile_errors();

            $query = "SELECT * from donor where username=:username;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":username",$_SESSION["donor"]);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            profile_template($row,'Donor');
        }
        else if ($getOne && $getOne==='donate_blood')
        {

            $val = reset($_GET);

            if($val!=='1') 
            {
                print_error("Link Corrupted!! Correct the link.......");
                die();
            }

            check_errors();



            donate_request_template("donate.php","Donate Blood","Disease","disease", "Donate");

        }
        else if ($getOne && $getOne==='donations_history')
        {

            $val = reset($_GET);

            if($val!=='1') 
            {
                print_error("Link Corrupted!! Correct the link.......");
                die();
            }

            $query = "SELECT id from donor where username=:current_username;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":current_username", $_SESSION['donor']);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $donor_id = $result["id"];
            
            $query = "SELECT * from donate where donor_id=:id order by id desc;;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id",$donor_id);
            $stmt->execute();

            $cnt=0;

            echo '<div class="container mt-5 mb-5">
                    <h2 class="text-center mb-4">Donation History</h2>
                    <div class="row align-items-center">';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                history_template($row,"Disease","disease", "hospital1");

                $cnt++;
            }

            echo '</div>
            </div>';

            if($cnt==0) print_error("No donations history!");

            // Close the PDO connection
            $pdo = null;
        }

    ?>

    <!-- Include Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>