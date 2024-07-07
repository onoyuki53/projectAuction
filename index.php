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

// ログインチェック
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php"); // ログインページへリダイレクト
//     exit();
// }

// 商品情報の取得と表示
$sql = "SELECT item_id, item_name, item_price, item_user FROM Item";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
</head>
<body>
    <h1>商品一覧</h1>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . $row["item_name"] . "</h2>";
            echo "<p>価格: ¥" . number_format($row["item_price"]) . "</p>";
            echo "<p>出品者: " . $row["item_user"] . "</p>";
            echo "<a href='itemdetails.php?item_id=" . $row["item_id"] . "'>詳細を見る</a>";
            echo "</div>";
        }
    } else {
        echo "商品が見つかりませんでした。";
    }
    ?>

</body>
</html>

<?php
$conn->close(); // データベース接続のクローズ
?>
