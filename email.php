<?php
include __DIR__ . '/includes/dbh.inc.php';
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ );
$dotenv->load();

function getMailIds(string $patient_id, string $pincode, PDO $conn): array {
    $sql = "SELECT d.email 
            FROM donor d
            JOIN patient p ON d.pincode = :pincode AND d.blood = p.blood
            WHERE p.id = :patient_id";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare statement: " . implode(" | ", $conn->errorInfo()));
        return [];
    }

    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
    $stmt->bindParam(':pincode', $pincode, PDO::PARAM_STR);
    if (!$stmt->execute()) {
        error_log("Query execution failed: " . implode(" | ", $stmt->errorInfo()));
        return [];
    }

    $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($emails)) {
        error_log("No matching donors found for patient ID: $patient_id");
        return [];
    }

    return $emails;
}

function sendEmails(array $emails, string $role, array $info, string $hospital1, string $reason, string $blood): void {
    if (empty($emails)) {
        error_log("No emails found, skipping email sending.");
        return;
    }

    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->SMTPDebug  = SMTP::DEBUG_SERVER; // Enable debugging
        $mail->Debugoutput = 'error_log'; // Log output to error log
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
        $mail->Port       = $_ENV['SMTP_PORT'];

        // Sender details (must match SMTP user)
        $mail->setFrom($_ENV['SMTP_USER'], 'Blood Bank');
        $mail->isHTML(true);

        foreach ($emails as $email) {
            $mail->clearAddresses(); // Important: Clear previous recipient before adding new
            $mail->addAddress($email);

            if ($role === "Patient-Donor") {
                $mail->Subject = 'Urgent Blood Request';
                $mail->Body    = "A patient in your area needs a blood donation.<br>
                                  <b>Patient Name:</b> {$info['name']}<br>
                                  <b>Hospital:</b> $hospital1<br>
                                  <b>Reason:</b> $reason<br>
                                  <b>Blood Group:</b> $blood";
            } 
            else if ($role === "Patient-Rejected") {
                $mail->Subject = 'Your Blood Request';
                $mail->Body    = "A Blood Donation Request has been raised due to short stock of {$blood} blood. All the eligible donors have been alerted.
                Get well soon.<br>
                                  <b>Patient Name:</b> {$info['name']}<br>
                                  <b>Hospital:</b> $hospital1<br>
                                  <b>Reason:</b> $reason<br>
                                  <b>Blood Group:</b> $blood";
            }
            else if ($role === "Patient-Approved") {
                $mail->Subject = 'Your Blood Request';
                $mail->Body    = "Your Blood Donation Request has been approved. Collect your blood from the hospital front desk.
                Get well soon.<br>
                                  <b>Patient Name:</b> {$info['name']}<br>
                                  <b>Hospital:</b> $hospital1<br>
                                  <b>Reason:</b> $reason<br>
                                  <b>Blood Group:</b> $blood";
            }  
            else if ($role === "Donor") {
                $mail->Subject = 'Your Blood Donation Request';
                $mail->Body    = "Your Blood Donation Request has been accepted.<br>
                                  <b>Name:</b> {$info['name']}<br>
                                  <b>Hospital:</b> $hospital1<br>
                                  <b>Listed Diseases:</b> $reason<br>
                                  <b>Blood Group:</b> $blood";
            }

            $mail->send();
            error_log("Email sent successfully to: $email");
        }
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}
