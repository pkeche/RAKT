<?php
require_once __DIR__ . '/../includes/dbh.inc.php';

header("Content-Type: application/json");

if (isset($_GET['pincode'])) {
    $pincode = $_GET['pincode'];

    $query = "SELECT hospital1 FROM locations WHERE pincode = :pincode";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":pincode", $pincode);
    $stmt->execute();
    $hospitals = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($hospitals);
    exit;
}

echo json_encode([]);
?>
