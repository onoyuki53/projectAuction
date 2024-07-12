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

// 現在の入札額を取得
$sql = "SELECT item_price FROM Item WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$stmt->bind_result($current_price);
$stmt->fetch();
$stmt->close();

// 入札額が現在の入札額より大きいか確認
if ($bid_amount > $current_price) {
    // 入札額の更新
    $sql = "UPDATE Item SET item_price = ?, buy_user = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $bid_amount, $user_id, $item_id);
    $stmt->execute();
    $stmt->close();
    // リダイレクト
    header("Location: index.php?status=success");
} else {
    // 入札額が低いため、エラーメッセージを表示
    header("Location: itemdetails.php?item_id=$item_id&status=error&message=低すぎる入札額です");
}

$conn->close();
?>
