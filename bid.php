<?php
// データベース接続設定
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "auction";

// データベースへの接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続の確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// クッキーが設定されているか確認
if (!isset($_COOKIE['user_name'])) {
    header("Location: login.php");
    exit();
}

// クッキーからユーザー名を取得
$user_id = $_COOKIE['user_name'];

// POSTデータの取得
$item_id = $_POST['item_id'];
$bid_amount = $_POST['bid_amount'];

// 入札額の更新
$sql = "UPDATE Item SET item_price = ?, buy_user = ? WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $bid_amount, $user_id, $item_id);
$stmt->execute();

// リダイレクト
header("Location: test.php");

$stmt->close();
$conn->close();
?>
