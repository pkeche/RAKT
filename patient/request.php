<?php

declare(strict_types=1);
require_once("../includes/session.inc.php");
require("../includes/dbh.inc.php");
require_once("../services/notify/main.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $reason = $_POST["reason"];
        $unit = $_POST["unit"];
        $errors = [];

        if (empty($reason) || $unit == null) {
            $errors["request_empty"] = "Fill all fields!";
        }
        if ($unit && $unit <= 0) {
            $errors["request_negative"] = "Blood units cannot be negative or zero!";
        }

        if ($errors) {
            $_SESSION["patient_error_request"] = $errors;
            header("Location:dashboard.php?request_blood=1");
            exit();
        }

        // Get blood type of the patient
        $query = "SELECT blood FROM patient WHERE username=:current_username;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION['patient']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Patient not found.");
        }

        $blood = $result["blood"];

        // Get patient ID
        $query = "SELECT id FROM patient WHERE username=:current_username;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION['patient']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("Patient not found.");
        }

        $patient_id = $result["id"];

        // Insert request into the database
        $query = "INSERT INTO request(username, patient_id, reason, blood, unit) VALUES(:current_username, :id, :reason, :blood, :unit);";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":current_username", $_SESSION["patient"]);
        $stmt->bindParam(":reason", $reason);
        $stmt->bindParam(":blood", $blood);
        $stmt->bindParam(":id", $patient_id);
        $stmt->bindParam(":unit", $unit);
        $stmt->execute();

        // Automatically approve the request
        $request_id = $pdo->lastInsertId();
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

        if ($blood_result[$blood_column] - $unit >= 0) {
            // Update blood stock
            $query = "UPDATE blood SET {$blood_column} = {$blood_column} - :unit WHERE id = :id;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $blood_id, PDO::PARAM_INT);
            $stmt->bindParam(":unit", $unit, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $input_status = "rejected due to insufficient blood stock of " . $blood;
            $emailList = getMailIds($patient_id, $pdo);
            sendEmails($emailList,"Patient");
        }

        // Update request status
        $query = "UPDATE request SET status = :status WHERE id=:id;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $request_id);
        $stmt->bindParam(":status", $input_status);
        $stmt->execute();

        header("Location:dashboard.php?requests_history=1");

        $pdo = null;
        $stmt = null;

        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    header("Location:dashboard.php");
    exit();
}
?>
