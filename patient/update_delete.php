<?php

declare(strict_types= 1);
require_once __DIR__ . '/../includes/session.inc.php';
require_once __DIR__ . '/../includes/dbh.inc.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST['username'];
    $pincode = $_POST['pincode'];
    $password = $_POST['password'];
    $password_updated = $_POST['password_updated'];

    try {
        //code...
        $query = "SELECT id from patient where username=:current_username;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION['patient']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $patient_id = (int)$result["id"]; // Cast patient_id to integer

        $errors = [];

        if (empty($username) || empty($email) || empty($name)) {
            $errors["patient_error_profile"] = "Fill all fields!";
        }
        if (username_exists($pdo, $username, $patient_id)) {
            $errors["user_exists"] = "user already exists!";
        }
        if (email_exists($pdo, $email, $patient_id)) {
            $errors["email_exists"] = "email already exists!";
        }

        if ($errors) {
            $_SESSION["patient_error_profile"] = $errors;
            header("Location:dashboard.php?profile=1");
            die();
        }

        if (isset($_POST['update'])) {
            if ($password_updated === "true") {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE patient SET username=:username, email=:email, pincode=:pincode, name=:name, pwd=:password WHERE id=:id;";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(":password", $hashed_password);
            } else {
                $query = "UPDATE patient SET username=:username, email=:email, pincode=:pincode, name=:name WHERE id=:id;";
                $stmt = $pdo->prepare($query);
            }

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":pincode", $pincode);
            $stmt->bindParam(":id", $patient_id);
            $stmt->execute();

            $_SESSION['patient'] = $username;
            header('Location:dashboard.php?profile=1');
            die();
        } else if (isset($_POST['delete'])) {
            $query = "DELETE FROM patient WHERE id=:id;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $patient_id);
            $stmt->execute();
            header("Location:../index.php?deleted=1");
            exit();
        }

        $pdo = null;
        $stmt = null;

        die();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
    } catch (Exception $e) {
        error_log("General error: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
    }
} else {
    header("Location:dashboard.php");
    die();
}

function username_exists(object $pdo, string $username, int $id): bool
{
    $query = "SELECT username from patient where username=:username and id!=:id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return (bool)$result;
}

function email_exists(object $pdo, string $email, int $id): bool
{
    $query = "SELECT email from patient where email=:email and id!=:id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return (bool)$result;
}

?>