<?php
require '../includes/dbh.inc.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getMailIds(string $patient_id, PDO $conn): void {
    $sql = "SELECT d.email
            FROM donor d
            JOIN patient p ON d.pincode = p.pincode AND d.blood = p.blood
            WHERE p.id = :patient_id";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->errorInfo()[2]);
        return;
    }

    $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
        error_log("Failed to execute query: " . $stmt->errorInfo()[2]);
        return;
    }

    $emails = [];
    foreach ($result as $row) {
        $emails[] = $row['email'];
    }

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
            $mail->setFrom($_ENV['SENDER'], 'Your Name');
            $mail->addAddress($email);

            // Email content
            $mail->Subject = 'Blood Request Notification';
            $mail->Body    = 'Blood Request Notification';

            $mail->send();
            echo "Email sent to $email\n";
        } catch (Exception $e) {
            error_log("Email could not be sent to $email. Error: {$mail->ErrorInfo}");
            echo "Email could not be sent to $email. Error: {$mail->ErrorInfo}\n";
        }
    }
}
?>