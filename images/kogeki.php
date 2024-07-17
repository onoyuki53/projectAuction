<?php
// データベース接続設定
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "auction";

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQLクエリの作成
$sql = "UPDATE Item SET item_name = '工科大爆破予告'";

// クエリの実行
if ($conn->query($sql) === TRUE) {
    echo "Item_nameが全て予告に変更されました。";

} else {
    echo "Error updating record: " . $conn->error;
}

// 接続を閉じる
header('Location:../index.php');
$conn->close();
?>