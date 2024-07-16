<?php
session_start();
require_once 'database.php'; // database.php をインクルード

$item_id = $_GET['item_id'];
$pdo = connect(); // PDO 接続を確立

// 商品情報の取得
$sql = "SELECT * FROM Item WHERE item_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $item_id, PDO::PARAM_STR); // item_id を文字列としてバインド
$stmt->execute();
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// 商品画像の取得
$sql_images = "SELECT image_path FROM Item_Image WHERE item_id = ?";
$stmt_images = $pdo->prepare($sql_images);
$stmt_images->bindParam(1, $item_id, PDO::PARAM_STR); // item_id を文字列としてバインド
$stmt_images->execute();
$images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

$logged_in = isset($_SESSION['login_user']);
$logged_in_user = $_SESSION['login_user'] ?? null; // ログイン中のユーザーIDを取得
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="header">
        <div class="header_logo">
            <a href="./index.php">
                <img src="./img/logo_square.png" alt="Logo">
            </a>
        </div>
        <div class="header_btn">
            <?php if ($logged_in): ?>
                <a href="./regist/mypage.php" class="btn btn-primary">マイページ</a>
                <a href="./logout.php" class="btn btn-secondary">ログアウト</a>
            <?php else: ?>
                <a href="./login.php" class="btn btn-primary">ログイン</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<div class="container">
    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'error' && isset($_GET['message'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_GET['message']) . '</p>';
    }
    ?>    
    <?php if ($item): ?>
        <div class="product-detail">
            <div class="slideshow-container">
                <?php foreach ($images as $index => $image): ?>
                    <div class="slide">
                        <img src="<?= htmlspecialchars($image["image_path"]) ?>" alt="<?= htmlspecialchars($item["item_name"]) ?>">
                    </div>
                <?php endforeach; ?>
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <div class="product-info">
                <h3><?= htmlspecialchars($item["item_name"]) ?></h3>
                <p>¥<?= number_format($item["item_price"]) ?></p>
                <p>現在の入札額: ¥<?= number_format($item["item_price"]) ?></p>
                <p style="color: red;">即決価格: ¥<?= number_format($item['max_price']); ?></p>
                
                <?php if ($logged_in_user === $item['item_user']): ?>
                    <p style="color: green;">これはあなたの商品です</p>
                <?php endif; ?>
            </div>
            <?php if ($logged_in && $logged_in_user !== $item['item_user']): ?>
                <div class="bid-form">
                    <form method="POST" action="bid.php">
                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                        <input type="number" name="bid_amount" placeholder="入札額" required />
                        <button type="submit" class="btn btn-primary">入札</button>
                    </form>
                </div>
            <?php elseif (!$logged_in): ?>
                <a href="login.php" class="btn btn-primary">ログインして入札</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>商品が見つかりませんでした。</p>
    <?php endif; ?>
</div>

<script>
    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        let slides = document.getElementsByClassName("slide");
        if (n > slides.length) { slideIndex = 1 }
        if (n < 1) { slideIndex = slides.length }
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex - 1].style.display = "block";
    }

    let autoSlideTimer = setInterval(function () {
        plusSlides(1);
    }, 3000);

    document.querySelector(".slideshow-container").addEventListener("click", function () {
        clearInterval(autoSlideTimer);
        autoSlideTimer = setInterval(function () {
            plusSlides(1);
        }, 3000);
    });
</script>
</body>
<footer>
    <p>&copy; 2023 ブランドバンクオークション</p>
</footer>
</html>
