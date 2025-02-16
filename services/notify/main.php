<?php
require '../includes/dbh.inc.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getMailIds(string $patient_id, $conn): array {
    $sql = "SELECT d.email
            FROM donor d
            JOIN patient p ON d.pincode = p.pincode AND d.blood = p.blood
            WHERE p.id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare statement: " . $conn->error);
        return [];
    }

    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        error_log("Failed to execute query: " . $stmt->error);
        return [];
    }

    $emails = [];
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['email'];
    }

    $stmt->close();

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
            error_log("Email could not be sent to $email. Error: {$mail->ErrorInfo}");
            echo "Email could not be sent to $email. Error: {$mail->ErrorInfo}\n";
        }
    }
}

// Example usage
$patient_id = 1; // Replace with actual patient ID
$conn = new mysqli('localhost', 'username', 'password', 'database'); // Replace with actual DB credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$emails = getMailIds($patient_id, $conn);
if (empty($emails)) {
    echo "No emails found for patient ID $patient_id\n";
} else {
    sendMailsToDonors($emails);
}

$conn->close();
?>