<?php
header("HTTP/1.1 200 OK");
require_once('config.php');
require_once('configpacks.php');

$db = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
	
if ($_POST['WP_SECURITYKEY']==$wpluskey) {
	$buyer_email = trim($_POST['WP_BUYER_EMAIL']);
	$buyer_name = trim($_POST['WP_BUYER_NAME']);
	$saleid=trim($_POST['WP_SALEID']);
	$item_number=trim($_POST['WP_ITEM_NUMBER']);
	$item_name=trim($_POST['WP_ITEM_NAME']);
	$sale_amount=trim($_POST['WP_SALE_AMOUNT']);
	$sale_currency=trim($_POST['WP_SALE_CURRENCY']);
	$sid=trim($_POST['WP_SID']);
	$buyer_ip=trim($_POST['WP_BUYER_IP']);
	$action = trim($_POST['WP_ACTION']);
	$item_number = preg_replace('/^wso_/', '', $item_number);
	$timestamp = date("Y-m-d H:i:s");  
	FILE_PUT_CONTENTS("LOG-ACTIVITY-wplus_ipn.txt","[$timestamp] Action: ".$action." - SaleID: ".$saleid."\r\n",FILE_APPEND);
	if ($action=="sale" || $action=="subscr_created" || $action=="subscr_completed") {
		if ($action=="sale"){$trans_type=0;}else{$trans_type=1;}
		for($i = 1; $i <= 10; $i++) {
			$description = 'wplus_pack_description_' . $i;
			$credits = 'wplus_pack_credits_' . $i;
			$cart= 'wplus_pack_cart_url_' . $i;
			$productid= 'wplus_pack_product_id_' . $i;
			$$productid = preg_replace('/^wso_/', '', $$productid); 
			if ($$productid == $item_number) {
				
				$checkStmt = $db->prepare("SELECT COUNT(*) as count FROM sales WHERE txid = :txid");
				$checkStmt->bindParam(':txid', $saleid, PDO::PARAM_STR);
				$checkStmt->execute();
				$result = $checkStmt->fetch(PDO::FETCH_ASSOC);
				
				if($result['count'] == 0) {				
						
					$credits_to_add=$$credits;
					$stmt = $db->prepare("UPDATE users SET user_credits = user_credits + :credits_to_add WHERE user_id = :sid");
					$stmt->bindParam(':credits_to_add', $credits_to_add, PDO::PARAM_INT);
					$stmt->bindParam(':sid', $sid, PDO::PARAM_INT);
					$stmt->execute();
					
					$stmt = $db->prepare("INSERT INTO sales (platform, internal_product_id, pack_name, name, email, buyer_ip, purchase_date, txid, item_number, item_name, credits, user_id, sale_amount, sale_currency, type) VALUES (:platform, :internal_product_id, :pack_name, :name, :email, :buyer_ip, NOW(), :txid, :item_number, :item_name, :credits, :user_id, :sale_amount, :sale_currency, :type)");

					
					$stmt->bindValue(':platform', 1, PDO::PARAM_INT);
					$stmt->bindValue(':internal_product_id', 1, PDO::PARAM_INT);
					$stmt->bindParam(':pack_name', $$description, PDO::PARAM_STR);
					$stmt->bindParam(':name', $buyer_name, PDO::PARAM_STR);
					$stmt->bindParam(':email', $buyer_email, PDO::PARAM_STR);
					$stmt->bindParam(':buyer_ip', $buyer_ip, PDO::PARAM_STR);
					$stmt->bindParam(':txid', $saleid, PDO::PARAM_STR);
					$stmt->bindParam(':item_number', $item_number, PDO::PARAM_STR);
					$stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);
					$stmt->bindParam(':credits', $credits_to_add, PDO::PARAM_INT);
					$stmt->bindParam(':user_id', $sid, PDO::PARAM_INT);
					$stmt->bindParam(':sale_amount', $sale_amount, PDO::PARAM_STR);
					$stmt->bindParam(':sale_currency', $sale_currency, PDO::PARAM_STR);
					$stmt->bindValue(':type', $trans_type, PDO::PARAM_INT);
					$stmt->execute();
				}
			}
		}
	}
	
	if ($action=="refund" || $action=="subscr_cancelled" || $action=="subscr_failed_invalid" || $action=="subscr_refunded" || $action=="subscr_suspended" || $action=="subscr_ended") {
		$timestamp = date("Y-m-d H:i:s");  
		try {
			$db->beginTransaction();
			$stmt1 = $db->prepare("SELECT credits,user_id FROM sales WHERE txid = :txid AND cancel_date = '0000-00-00 00:00:00'");
			$stmt1->bindParam(':txid', $saleid, PDO::PARAM_STR);
			$stmt1->execute();

			$result = $stmt1->fetch(PDO::FETCH_ASSOC);

			if ($result) {
				$trans_credits = $result['credits'];
				$sid = $result['user_id'];
				$stmtUpdate = $db->prepare("UPDATE sales SET cancel_date = NOW() WHERE txid = :txid");
				$stmtUpdate->bindParam(':txid', $saleid, PDO::PARAM_STR);
				$stmtUpdate->execute();
				$stmt2 = $db->prepare("SELECT user_credits FROM users WHERE user_id = :sid");
				$stmt2->bindParam(':sid', $sid, PDO::PARAM_INT);
				$stmt2->execute();

				$userResult = $stmt2->fetch(PDO::FETCH_ASSOC);

				if ($userResult) {
					$updated_credits = max(0, $userResult['user_credits'] - $trans_credits); // Ensure it doesn't go below 0
					$stmt3 = $db->prepare("UPDATE users SET user_credits = :updated_credits WHERE user_id = :sid");
					$stmt3->bindParam(':updated_credits', $updated_credits, PDO::PARAM_INT);
					$stmt3->bindParam(':sid', $sid, PDO::PARAM_INT);
					$stmt3->execute();
					FILE_PUT_CONTENTS("LOG-ACTIVITY-wplus_ipn.txt","[$timestamp] REFUND for [$saleid] - User [$sid] has had [$trans_credits] credits deducted leaving them with [$updated_creits] credits\r\n",FILE_APPEND);
				} else {
					FILE_PUT_CONTENTS("LOG-ACTIVITY-wplus_ipn.txt","[$timestamp] REFUND for [$saleid] - User ID [$sid] not found\r\n",FILE_APPEND);
				}
			} else {
				FILE_PUT_CONTENTS("LOG-ACTIVITY-wplus_ipn.txt","[$timestamp] REFUND Transaction ID [$saleid] not found OR transaction already cancelled\r\n",FILE_APPEND);
			}

			$db->commit();

		} catch (PDOException $e) {
			$db->rollBack();
			FILE_PUT_CONTENTS("LOG-ACTIVITY-wplus_ipn.txt","[$timestamp] GENERAL ERROR: ".$e->getMessage()."\r\n",FILE_APPEND);
		}
		
		
	}
	
}
?>