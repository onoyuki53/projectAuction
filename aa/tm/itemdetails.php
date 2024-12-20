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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d6d4d4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo a {
            color: #fff;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
        }
        .user-actions a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            padding: 10px;
            background-color: #555;
            border-radius: 5px;
        }
        .user-actions a:hover {
            background-color: #777;
        }
        .container {
            padding: 20px;
        }
        .product-detail {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .product-info {
            text-align: center;
        }
        .product-info h3 {
            margin-top: 20px;
        }
        .product-info p {
            margin-bottom: 10px;
        }
        .bid-form input[type="number"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100px;
            margin-bottom: 10px;
        }
        .bid-form button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .bid-form button:hover {
            background-color: #555;
        }
        .slideshow-container {
            max-width: 1000px;
            position: relative;
        }
        .slide {
            display: none; /* 初期状態で非表示 */
            width: 100%;
            height: auto;
        }
        .slide img {
            width: 100%;
            height: auto;
        }
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -22px;
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

    </style>
</head>
<body>
<header>
    <div class="logo">
        <a href="#">~オークション</a>
    </div>
    <div class="user-actions">
        <a href="./mypage.php">マイページ</a>
        <a href="./login.php">ログイン</a>
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
            </div>
            <?php if ($logged_in): ?>
                <div class="bid-form">
                    <form method="POST" action="bid.php">
                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item["item_id"]) ?>">
                        <input type="number" name="bid_amount" placeholder="入札額" required />
                        <button type="submit">入札</button>
                    </form>
                </div>
            <?php else: ?>
                <a href="login.php" class="login-button">ログインして入札</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>商品が見つかりませんでした。</p>
    <?php endif; ?>
</div>
<footer>
    <p>&copy; 2023 ブランドバンクオークション</p>
</footer>
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
</html>
