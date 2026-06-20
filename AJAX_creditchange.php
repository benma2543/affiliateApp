<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

try {
    // Connection to the database using PDO
    $dsn = "mysql:host=" . USER_DB_HOST . ";dbname=" . USER_DB_NAME . ";charset=" . USER_DB_CHARSET;
    $pdo = new PDO($dsn, USER_DB_USER, USER_DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get and sanitize the wordCount from POST request
    $wordCount = isset($_POST['wordCount']) ? filter_var($_POST['wordCount'], FILTER_SANITIZE_NUMBER_INT) : 0;

    // Ensure wordCount is a positive integer
    if($wordCount < 0) {
        echo "Invalid word count.";
        exit;
    }

    // Fetch the user_credits from database using prepared statements
    $query = "SELECT user_credits FROM users WHERE user_id = :userid";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row) {
        $currentCredits = $row['user_credits'];

        // Subtract wordCount but ensure it doesn't go below zero
        $newCredits = max(0, $currentCredits - $wordCount);

        // Update the user_credits in database using prepared statements
        $updateQuery = "UPDATE users SET user_credits = :newCredits WHERE user_id = :userid";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':newCredits', $newCredits, PDO::PARAM_INT);
        $updateStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        
        if($updateStmt->execute()) {
            echo $newCredits;
        } else {
            echo "Error updating credits.";
        }

    } else {
        echo "User not found.";
    }

} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
}


?>
