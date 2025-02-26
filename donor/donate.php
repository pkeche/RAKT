<?php

declare(strict_types=1);
require_once __DIR__ . '/../includes/session.inc.php';
require_once __DIR__ . '/../includes/dbh.inc.php';
require_once __DIR__ . '/../includes/template.php';
require_once __DIR__ . '/../email.php';

if (isset($_SESSION["donor"]) && isset($_GET["donate"]) && $_GET["donate"] === "success") {
    header("Location:dashboard.php");
    die();
}

function check_errors()
{
    if (isset($_SESSION["donor_error_donate"])) {
        $errors = $_SESSION["donor_error_donate"];
        echo "<br>";
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger alert-dismissible fade show text-center mx-auto" role="alert" style="width: fit-content;">';
            echo $error;
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="background:none;">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
            ';
        }
        unset($_SESSION["donor_error_donate"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $disease = $_POST["disease"];
        $unit = $_POST["unit"];
        $hospital1 = $_POST["hospital1"];
        $errors = [];

        if (empty($disease) || $unit == null || $hospital1 == null) {
            $errors["donate_empty"] = "Fill all fields!";
        }
        if ($unit && $unit <= 0) {
            $errors["donate_negative"] = "Blood units cannot be negative or zero!";
        }

        if ($errors) {
            $_SESSION["donor_error_donate"] = $errors;
            header("Location:dashboard.php?donate_blood=1");
            die();
        }

        // Get blood type of the donor
        $query = "SELECT blood, email FROM donor WHERE username=:current_username;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION['donor']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Donor not found.");
        }

        $blood = $result["blood"];
        $email = $result["email"];

        // Get donor ID
        $query = "SELECT id FROM donor WHERE username=:current_username;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION['donor']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Donor not found.");
        }

        $donor_id = $result["id"];

        // Insert donation into the database
        $query = "INSERT INTO donate(username, donor_id, disease, blood, unit, hospital1) VALUES(:current_username, :id, :disease, :blood, :unit, :hospital1);";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION["donor"]);
        $stmt->bindParam(":disease", $disease);
        $stmt->bindParam(":blood", $blood);
        $stmt->bindParam(":id", $donor_id);
        $stmt->bindParam(":unit", $unit);
        $stmt->bindParam(":hospital1", $hospital1);
        $stmt->execute();

        // Automatically approve the donation
        $donation_id = $pdo->lastInsertId();
        $input_status = "approved";

        // Map blood types to column names
        $blood_map = [
            "A+" => "AP",
            "A-" => "AN",
            "B+" => "BP",
            "B-" => "BN",
            "AB+" => "ABP",
            "AB-" => "ABN",
            "O+" => "OP",
            "O-" => "ON"
        ];
        $blood_column = $blood_map[$blood] ?? $blood;

        $blood_id = 1;

        // Get blood stock
        $query = "SELECT * FROM blood WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $blood_id);
        $stmt->execute();
        $blood_result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$blood_result) {
            throw new Exception("Blood stock not found.");
        }
        
        // Update blood stock
        $query = "UPDATE blood SET {$blood_column} = {$blood_column} + :unit WHERE id = :id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $blood_id, PDO::PARAM_INT);
        $stmt->bindParam(":unit", $unit, PDO::PARAM_INT);
        $stmt->execute();

        // Update donation status
        $query = "UPDATE donate SET status = :status WHERE id=:id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $donation_id);
        $stmt->bindParam(":status", $input_status);
        $stmt->execute();

        // Get info of the donor
        $query = "SELECT * FROM donor WHERE username=:current_username;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION['donor']);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);

        // Send email to the donor
        sendEmails([$email], "Donor", $info, $hospital1, $disease, $blood);

        $pdo = null;
        $stmt = null;
        header("Location:dashboard.php?donations_history=1");
        die();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    header("Location:dashboard.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Donate</title>
    <!-- Include Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/blood-drop.svg" type="image/x-icon">
    <!-- Apply custom styles for the form -->
    <style>
        html, body {
            min-height: 100%;
            margin: 0;
            padding: 0;
        }
        .form-container {
            border-radius: 10px;
            padding: 20px;
            margin: 10px auto 50px;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .active , .active:hover {
            background-color: #1abc9c; /* Highlight color for the active button */
            color:#fff;
        }
        .btn {
            border: 1px #1ac9bc solid;
            margin: 5px;
        }
        .custom-text-center {
            padding: 10px;
            max-width: 400px;
            margin: auto;
        }
        @media (min-width: 576px) {
            .text-center {
                display: flex;
                justify-content: center;
            }
            .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body style="background-color: #f5f5dc;">
    <div class="container" style="margin-top:80px;">
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color:#d9534f;">
        <a class="navbar-brand" href="../index.php" style="color: #fff;font-size:22px;letter-spacing:2px;">RAKT</a>
    </nav>
        <?php 
            check_errors();
        ?>
        <div class="text-center custom-text-center">
            <a class="btn" href="../patient/register.php">As Patient</a>
            <a class="btn active" href="../donor/donate.php">As Donor</a>
        </div>
        <!-- Donor Donate Form -->
        <div style="display:block;">
            <?php register_template("Donor Donate"); ?>
        </div>
    </div>
    <!-- Include Bootstrap JS and jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>