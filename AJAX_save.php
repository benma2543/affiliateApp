<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("config.php");
require_once("user/protect.php");
require_once("user/user-lib.php");

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);



extract($_POST, EXTR_SKIP);

for ($i = 1; $i <= 9; $i++) {
    $varName = 's' . $i;
    $$varName = base64_decode($$varName);
}

if ($id == 0) {
    $sql = "
    INSERT INTO projects (
        user_id, project_name, date_created, last_edited, category, tone, 
        language, audience, p1_input,numdays,product_name,bonusnum1,bonusname1,bonusdesc1,bonusurl1,bonusthumb1,bonusnum2,bonusname2,bonusdesc2,bonusurl2,bonusthumb2,bonusnum3,bonusname3,bonusdesc3,bonusurl3,bonusthumb3,bonusnum4,bonusname4,bonusdesc4,bonusurl4,bonusthumb4,bonusnum5,bonusname5,bonusdesc5,bonusurl5,bonusthumb5,s1,s2,s3,s4,s5,s6,s7,s8,s9
    ) 
    VALUES (
        :user_id, :project_name, NOW(), NOW(), :category, :tone, 
        :language, :audience, :p1_input,:numdays,:product_name,:bonusnum1,:bonusname1,:bonusdesc1,:bonusurl1,:bonusthumb1,:bonusnum2,:bonusname2,:bonusdesc2,:bonusurl2,:bonusthumb2,:bonusnum3,:bonusname3,:bonusdesc3,:bonusurl3,:bonusthumb3,:bonusnum4,:bonusname4,:bonusdesc4,:bonusurl4,:bonusthumb4,:bonusnum5,:bonusname5,:bonusdesc5,:bonusurl5,:bonusthumb5,:s1,:s2,:s3,:s4,:s5,:s6,:s7,:s8,:s9
    )
";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(':user_id', $userid);
	$stmt->bindParam(':project_name', $projectname);
	$stmt->bindParam(':category', $category);
	$stmt->bindParam(':tone', $tone);
	$stmt->bindParam(':language', $language);
	$stmt->bindParam(':audience', $audience);
	$stmt->bindParam(':p1_input', $p1_input);
	$stmt->bindParam(':numdays', $numdays);
	$stmt->bindParam(':product_name', $product_name);

	for ($i = 1; $i <= 9; $i++) {
		$stmt->bindParam(":s$i", ${"s$i"});
	}
	for ($i = 1; $i <= 5; $i++) {
		$stmt->bindParam(":bonusnum$i", ${"bonusnum$i"});
		$stmt->bindParam(":bonusname$i", ${"bonusname$i"});
		$stmt->bindParam(":bonusdesc$i", ${"bonusdesc$i"});
		$stmt->bindParam(":bonusurl$i", ${"bonusurl$i"});
		$stmt->bindParam(":bonusthumb$i", ${"bonusthumb$i"});
	}
    $stmt->execute();
    $lastId = $db->lastInsertId();
    echo $lastId;
	exit();
} else {
    $sql = "SELECT * FROM projects WHERE id = :projectid AND user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':projectid', $id);
    $stmt->bindParam(':user_id', $userid);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
		$sql = "
			UPDATE projects 
			SET project_name=:project_name, last_edited = NOW(), category=:category, tone=:tone, 
				language=:language, audience=:audience, p1_input=:p1_input,numdays=:numdays,product_name=:product_name,bonusnum1=:bonusnum1,bonusname1=:bonusname1,bonusdesc1=:bonusdesc1,bonusurl1=:bonusurl1,bonusthumb1=:bonusthumb1,bonusnum2=:bonusnum2,bonusname2=:bonusname2,bonusdesc2=:bonusdesc2,bonusurl2=:bonusurl2,bonusthumb2=:bonusthumb2,bonusnum3=:bonusnum3,bonusname3=:bonusname3,bonusdesc3=:bonusdesc3,bonusurl3=:bonusurl3,bonusthumb3=:bonusthumb3,bonusnum4=:bonusnum4,bonusname4=:bonusname4,bonusdesc4=:bonusdesc4,bonusurl4=:bonusurl4,bonusthumb4=:bonusthumb4,bonusnum5=:bonusnum5,bonusname5=:bonusname5,bonusdesc5=:bonusdesc5,bonusurl5=:bonusurl5,bonusthumb5=:bonusthumb5,s1=:s1, s2=:s2, s3=:s3, s4=:s4, s5=:s5, s6=:s6, s7=:s7, s8=:s8, s9=:s9
			WHERE id = :projectid AND user_id=:user_id
		";

        $stmt = $db->prepare($sql);
		$stmt->bindParam(':project_name', $projectname);
		$stmt->bindParam(':category', $category);
		$stmt->bindParam(':tone', $tone);
		$stmt->bindParam(':language', $language);
		$stmt->bindParam(':audience', $audience);
		$stmt->bindParam(':p1_input', $p1_input);
		$stmt->bindParam(':projectid', $id);
		$stmt->bindParam(':user_id', $userid);
		$stmt->bindParam(':numdays', $numdays);
		$stmt->bindParam(':product_name', $product_name);


		for ($i = 1; $i <= 9; $i++) {
			$stmt->bindParam(":s$i", ${"s$i"});
		}
		for ($i = 1; $i <= 5; $i++) {
			$stmt->bindParam(":bonusnum$i", ${"bonusnum$i"});
			$stmt->bindParam(":bonusname$i", ${"bonusname$i"});
			$stmt->bindParam(":bonusdesc$i", ${"bonusdesc$i"});
			$stmt->bindParam(":bonusurl$i", ${"bonusurl$i"});
			$stmt->bindParam(":bonusthumb$i", ${"bonusthumb$i"});
		}        
        $stmt->execute();
        
        echo $id;
		exit();
        
    } else {
        echo "ERROR";
    }
}

?>
