<?php
session_start(); // セッションの開始

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

// クッキーによるユーザーのログイン状態の確認
// セッションによるユーザーのログイン状態の確認
if (!isset($_SESSION['login_user'])) {
    die("ログインしていません。<a href='login.php'>ログイン</a>してください。");
}
// 入札情報の取得
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bid_amount']) && isset($_POST['item_id'])) {
    $user_id = $_COOKIE['user_id'];
    $item_id = $conn->real_escape_string($_POST['item_id']);
    $bid_amount = $conn->real_escape_string($_POST['bid_amount']);

    // 入札額のバリデーション
    if (!is_numeric($bid_amount) || $bid_amount <= 0) {
        die("有効な入札金額を入力してください。");
    }

    // 現在の最高入札額の取得
    $sql_max_bid = "SELECT MAX(bid_amount) AS max_bid FROM Bids WHERE item_id = '$item_id'";
    $result_max_bid = $conn->query($sql_max_bid);
    if ($result_max_bid->num_rows > 0) {
        $row_max_bid = $result_max_bid->fetch_assoc();
        $max_bid = $row_max_bid['max_bid'];
    } else {
        $max_bid = 0; // 入札がない場合、最高額は0
    }

    // 入札額が現在の最高額より高いかの確認
    if ($bid_amount <= $max_bid) {
        die("入札額は現在の最高入札額よりも高くなければなりません。");
    }

    // 入札情報の挿入
    $sql_insert_bid = "INSERT INTO Bids (user_id, item_id, bid_amount, bid_time) VALUES ('$user_id', '$item_id', '$bid_amount', NOW())";
    if ($conn->query($sql_insert_bid) === TRUE) {
        echo "入札が正常に行われました。";
    } else {
        echo "入札処理中にエラーが発生しました: " . $conn->error;
    }
} else {
    die("無効なリクエストです。");
}

$conn->close(); // データベース接続のクローズ
?>
