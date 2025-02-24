<?php 

    declare(strict_types= 1);
    require_once("../includes/dbh.inc.php");
    require_once("../includes/session.inc.php");

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $inserted = set_default($pdo);
        if ($inserted) {
        echo "Default admin inserted successfully!";}
        header("Location:login.php");
    }
    function set_default(PDO $pdo): bool
    {
        try {
            // Start transaction to ensure data consistency
            $pdo->beginTransaction();
    
            // Delete all existing admins
            $pdo->exec("DELETE FROM `admin`");
    
            // Load default admin values from external file
            $adminData = require '../database/admin_defaults.php';
    
            // Prepare insert query
            $query = "INSERT INTO `admin` (id, name, username, email, pwd, pincode) 
                      VALUES (:id, :name, :username, :email, :pwd, :pincode)";
            $stmt = $pdo->prepare($query);
    
            // Hash the password before storing it
            $hashedPwd = password_hash($adminData['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    
            // Bind values
            $stmt->bindParam(":id", $adminData['id'], PDO::PARAM_INT);
            $stmt->bindParam(":name", $adminData['name'], PDO::PARAM_STR);
            $stmt->bindParam(":username", $adminData['username'], PDO::PARAM_STR);
            $stmt->bindParam(":email", $adminData['email'], PDO::PARAM_STR);
            $stmt->bindParam(":pwd", $hashedPwd, PDO::PARAM_STR);
            $stmt->bindParam(":pincode", $adminData['pincode'], PDO::PARAM_INT);
    
            // Execute the insert query
            $success = $stmt->execute();
    
            // Commit the transaction
            $pdo->commit();
    
            return $success;
        } catch (Exception $e) {
            // Rollback on failure
            $pdo->rollBack();
            error_log("Error resetting admin table: " . $e->getMessage());
            return false;
        }
    }
    
    
    
?>