<?php
session_start(); // セッションの開始

// データベース接続設定
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "auction";
$login_user = $_SESSION['login_user'];
// データベースへの接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続の確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// item_idの取得と存在確認
if(isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    // 商品情報の取得
    $sql_item = "SELECT item_name, item_price, item_user FROM Item WHERE item_id = '$item_id'";
    $result_item = $conn->query($sql_item);

    if ($result_item->num_rows > 0) {
        $row_item = $result_item->fetch_assoc();
        $item_name = $row_item['item_name'];
        $item_price = $row_item['item_price'];
        $item_user = $row_item['item_user'];

        // 商品画像の取得
        $sql_images = "SELECT image_path FROM Item_Image WHERE item_id = '$item_id'";
        $result_images = $conn->query($sql_images);
    } else {
        echo "商品が見つかりませんでした。";
    }
}
?>

</head>
<body>
    <h1><?php echo htmlspecialchars($item_name, ENT_QUOTES, 'UTF-8'); ?></h1>
    <p>価格: ¥<?php echo number_format($item_price); ?></p>
<p>出品者: <?php echo htmlspecialchars($item_user, ENT_QUOTES, 'UTF-8'); ?></p>

<div>
    <!-- 商品画像の表示 -->
    <?php
    if ($result_images->num_rows > 0) {
        $row_image = $result_images->fetch_assoc();
        echo "<img src='" . htmlspecialchars($row_image['image_path'], ENT_QUOTES, 'UTF-8') . "' alt='商品画像'>";
    } else {
        echo "画像はありません。";
    }
    ?>
</div>

<!-- 入札フォーム -->
<?php if (isset($_SESSION['login_user'])): ?>
    <form action="bid_process.php" method="post">
        <label for="bid_amount">入札金額:</label>
        <input type="text" id="bid_amount" name="bid_amount" required>
        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" value="入札する">
    </form>
<?php else: ?>
    <p>入札するには <a href="login.php">ログイン</a> してください。</p>
<?php endif; ?>

</body>
</html>

<?php
$conn->close(); // データベース接続のクローズ
?>
