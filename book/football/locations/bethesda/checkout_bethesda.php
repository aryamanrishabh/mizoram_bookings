<?php
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$timeslot = $_POST['timeslot'];
	$id = $_POST['id'];
	$orderAmount = '1000';
	$date = $_POST['date'];
	$mysqli = new mysqli('localhost', 'root', '', 'football');
	$stmt = $mysqli->prepare("INSERT INTO bethesda (id, name, phone, email, date, timeslot) VALUES (?,?,?,?,?,?)");
	$stmt->bind_param('ssssss',$id, $name, $phone, $email, $date, $timeslot);
	$stmt->execute();
	$msg = "<div class='alert alert-success'>Booking Successfull</div>";
	$bookings[] = $timeslot;
	$stmt->close();
	$mysqli->close();

	$host = "https://d9658cf53e49.ngrok.io";
	$notifyUrl = $host. "/moses/book/football/locations/bethesda/notify_bethesda.php";
	$returnUrl = $host. "/moses/book/football/locations/bethesda/return_bethesda.php";

	$orderDetails = array();
	$orderDetails["notifyUrl"] = $notifyUrl;
	$orderDetails["returnUrl"] = $returnUrl;

	$userDetails = getUserDetails($id);
	$order = getOrderDetails($id);

	$orderDetails["customerName"] = $userDetails["customerName"];
	$orderDetails["customerEmail"] = $userDetails["customerEmail"];
	$orderDetails["customerPhone"] = $userDetails["customerPhone"]; 

	$orderDetails["orderId"] = $order["orderId"];
	$orderDetails["orderAmount"] = $order["orderAmount"];
	$orderDetails["orderCurrency"] = $order["orderCurrency"];

	$orderDetails["appId"] = "48486e7258612f5e9e3d7ee6368484";

	$orderDetails["signature"] = generateSignature($orderDetails);

	echo json_encode($orderDetails);

	function generateSignature($postData){
		$secretKey = "3aadc1079c10030dfbb96550a95a5f049ce1b20c";
  
		// get secret key from your config
		ksort($postData);
		$signatureData = "";
		foreach ($postData as $key => $value){
			$signatureData .= $key.$value;
		}
		$signature = hash_hmac('sha256', $signatureData, $secretKey,true);
		$signature = base64_encode($signature);
		return $signature;
	}
?>

<form id="redirectForm" method="post" action="https://test.cashfree.com/billpay/checkout/post/submit">
    <input type="hidden" name="appId" value="<?php echo $orderDetails["appId"] ?>"/>
    <input type="hidden" name="orderId" value="<?php echo $orderDetails["orderId"] ?>"/>
    <input type="hidden" name="orderAmount" value="<?php echo $orderDetails["orderAmount"] ?>"/>
    <input type="hidden" name="orderCurrency" value="<?php echo $orderDetails["orderCurrency"] ?>"/>
    <
    <input type="hidden" name="customerName" value="<?php echo $orderDetails["customerName"] ?>"/>
    <input type="hidden" name="customerEmail" value="<?php echo $orderDetails["customerEmail"] ?>"/>
    <input type="hidden" name="customerPhone" value="<?php echo $orderDetails["customerPhone"] ?>"/>
    <input type="hidden" name="returnUrl" value="<?php echo $orderDetails["returnUrl"] ?>"/>
    <input type="hidden" name="notifyUrl" value="<?php echo $orderDetails["notifyUrl"] ?>"/>
    <input type="hidden" name="signature" value="<?php echo $orderDetails["signature"] ?>"/>
  </form>

  <script>document.getElementById("redirectForm").submit();</script>

<?php
	function getUserDetails($orderId){
		global $name, $email, $phone;
		return array(
			"customerName" => $name,
			"customerEmail" => $email,
			"customerPhone" => $phone
		);
	}

	function getOrderDetails($orderId){
		global $id, $orderAmount;
		return array(
			"orderId" => $id,
			"orderAmount" => $orderAmount,
			"orderCurrency" => "INR"
		);
	}
?>