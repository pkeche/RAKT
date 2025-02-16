<?php
require 'db_connection.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getMailIds(string $patient_id, $conn): array {
    $sql = "SELECT d.email
            FROM donor d
            JOIN patient p ON d.pincode = p.pincode AND d.blood = p.blood
            WHERE p.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $emails = [];
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['email'];
    }

    $stmt->close();
    return $emails; // Return the list of emails
}

function sendMailsToDonors(array $emails): void {
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
            $mail->Subject = 'Test Email';
            $mail->Body    = 'This is a test email';

            $mail->send();
            echo "Email sent to $email\n";
        } catch (Exception $e) {
            echo "Email could not be sent to $email. Error: {$mail->ErrorInfo}\n";
        }
    }
}
?>