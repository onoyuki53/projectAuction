<?php
session_start();
require_once 'database.php'; // データベース接続ファイルをインクルード

// ログインチェック
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['login_user']; // ログインユーザーのIDを取得
$pdo = connect(); // PDO接続を確立

// 出品者の商品の取得
$sql = "SELECT i.item_id, i.item_name, i.item_price, i.max_price, i.is_sold, ii.image_path, i.buy_user
        FROM Item i 
        JOIN (SELECT item_id, MIN(image_path) as image_path FROM Item_Image GROUP BY item_id) ii 
        ON i.item_id = ii.item_id
        WHERE i.item_user = ?";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $seller_id, PDO::PARAM_STR);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 即決価格を現在価格に変更する処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_price'])) {
    $item_id = $_POST['item_id'];
    
    // 現在価格を取得
    $sql_current_price = "SELECT item_price FROM Item WHERE item_id = ?";
    $stmt_current_price = $pdo->prepare($sql_current_price);
    $stmt_current_price->bindParam(1, $item_id, PDO::PARAM_STR);
    $stmt_current_price->execute();
    $current_price = $stmt_current_price->fetchColumn();

    // 即決価格を現在価格に更新する
    $sql_update = "UPDATE Item SET max_price = ?, is_sold = 1 WHERE item_id = ? AND item_user = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(1, $current_price, PDO::PARAM_STR);
    $stmt_update->bindParam(2, $item_id, PDO::PARAM_STR);
    $stmt_update->bindParam(3, $seller_id, PDO::PARAM_STR);
    $stmt_update->execute();

    header("Location: seller.php?status=success&message=即決価格を現在価格に変更しました");
    exit();
}

// 商品を削除する処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = $_POST['item_id'];
    $sql_delete = "DELETE FROM Item WHERE item_id = ? AND item_user = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindParam(1, $item_id, PDO::PARAM_STR);
    $stmt_delete->bindParam(2, $seller_id, PDO::PARAM_STR);
    $stmt_delete->execute();

    header("Location: seller.php?status=success&message=商品を削除しました");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>出品者ページ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="header">
        <div class="header_logo">
            <a href="index.php">
                <img src="./img/logo_square.png" alt="Logo">
            </a>
        </div>
        <div class="header_btn">
            <a href="./regist/mypage.php" class="btn btn-primary">マイページ</a>
            <a href="logout.php" class="btn btn-secondary">ログアウト</a>
        </div>
    </div>
</header>
<div class="container">
    <h2>あなたの出品商品</h2>
    <?php if ($items): ?>
        <div class="product-list">
            <?php foreach ($items as $item): ?>
                <div class="product-item">
                    <a href="itemdetails.php?item_id=<?= htmlspecialchars($item['item_id']) ?>">
                        <div class="product-image"><img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['item_name']) ?>"></div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($item['item_name']) ?></h3>
                            <p>現在の入札額: ¥<?= number_format($item['item_price']) ?></p>
                            <?php if ($item['is_sold'] == 1): ?>
                                <p style="color: red;">商品が販売済みです</p>
                            <?php else: ?>
                                <p style="color: red;">即決価格: ¥<?= number_format($item['max_price']) ?></p>
                                <div class="btn-group mt-2">
                                    <?php if (!empty($item['buy_user'])): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                                            <button type="submit" name="confirm_price" class="btn btn-primary">価格を確定する</button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                                        <button type="submit" name="delete_item" class="btn btn-danger" onclick="return confirm('本当にこの商品を削除しますか？')">商品を削除する</button>
                                    </form>
                                </div><br>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>現在、出品している商品はありません。</p>
    <?php endif; ?>
</div>
</body>
<footer>
    <p>&copy; 2024 Tech Auction @Canva</p>
</footer>
</html>
