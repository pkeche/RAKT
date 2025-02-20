<?php 

    declare(strict_types= 1);
    require_once("../includes/session.inc.php");
    require_once("../includes/dbh.inc.php");
    
    if($_SERVER["REQUEST_METHOD"]==="POST")
    {
        $pwd = $_POST["pwd"];
        $username = $_POST["username"];

        try 
        {
            //errors
            $errors = [];
    
            if(checkInput($pwd,$username))
            {
                $errors["check_input"] = "Fill all fields!";
            }

            if(username_exists($pdo,$username,$pwd))
            {
                $errors["incorrect"] = "Incorrect Login Info!";
            }

             if($errors)
             {
                $_SESSION["admin_error_login"] = $errors;
                header("Location:login.php");
                die();
             }

             $_SESSION["admin"]=$username;

             header("Location:login.php?login=success");

             $pdo = null;
             $stmt = null;

             die();

             
        } 
        catch (PDOException $e) 
        {
            //throw $th;
            die("query failed: ". $e->getMessage());
        }

    }
    else 
    {
        header("Location:login.php");
        die();
    }
    function checkInput(string $pwd, string $username)
    {
        return empty($pwd) || empty($username);
    }
    function username_exists(PDO $pdo, string $username, string $pwd): bool
    {
        $query = "SELECT * FROM admin WHERE username = :username AND pwd = :pwd";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->bindValue(":pwd", $pwd, PDO::PARAM_STR); // Fixed placeholder
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result == false;
    }
    
    
?>