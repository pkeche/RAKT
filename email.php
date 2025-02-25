<?php
require '../includes/dbh.inc.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load the .env file from the root directory
$dotenv = Dotenv::createImmutable(__DIR__ );
$dotenv->load();

function getMailIds(string $patient_id, PDO $conn): array {
    $sql = "SELECT d.email 
            FROM donor d
            JOIN patient p ON d.pincode = p.pincode AND d.blood = p.blood
            WHERE p.id = :patient_id";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare statement: " . implode(" | ", $conn->errorInfo()));
        return [];  // Exit on error
    }

    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
    if (!$stmt->execute()) {
        error_log("Query execution failed: " . implode(" | ", $stmt->errorInfo()));
        return [];  // Exit on error
    }

    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($emails)) {
        error_log("No matching donors found for patient ID: $patient_id");
        return [];  // Exit if no donors found
    }

    return $emails;
}

function sendEmails(array $emails, string $role): void {
    foreach ($emails as $email) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $mail->Port       = $_ENV['SMTP_PORT'];

            // Sender and recipient
            $mail->setFrom($_ENV['SENDER'], 'Blood Bank');
            $mail->addAddress($email);

            // Email content
            if ($role == "Patient") {
                $mail->Subject = 'Urgent Blood Request';
                $mail->Body    = 'A patient in your area needs a blood donation. Please contact the nearest blood bank.';
                $mail->send();
            }
            if ($role == "Donor") {
                $mail->Subject = 'Your Blood Donation Request';
                $mail->Body    = 'Your Blood Donation Request has been accepted. Please contact the nearest blood bank.';
                $mail->send();
            }
            
        } catch (Exception $e) {
            error_log("Email could not be sent to $email. Error: " . $mail->ErrorInfo);
            error_log("Exception: " . $e->getMessage());
        }
    }
}