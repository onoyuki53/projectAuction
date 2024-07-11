<?php
// 商品情報を取得するためのPHPコード（例）
$productName = "商品A";
$currentBid = 1000;
$imageUrls = [
    "./image/image1.jpg",
    "./image/image2.jpg"
];
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
            margin: auto;
        }
        .slide {
            display: none;
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
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            user-select: none;
        }
        .prev {
            left: 0;
            border-radius: 3px 0 0 3px;
        }
        .next {
            right: 0;
            border-radius: 0 3px 3px 0;
        }
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
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
    <div class="product-detail">
        <div class="slideshow-container">
            <?php foreach ($imageUrls as $url): ?>
                <div class="slide">
                    <img src="<?php echo $url; ?>" alt="<?php echo $productName; ?>">
                </div>
            <?php endforeach; ?>
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <div class="product-info">
            <h3><?php echo $productName; ?></h3>
            <p>¥<?php echo $currentBid; ?></p>
            <p>現在の入札額: ¥<?php echo $currentBid; ?></p>
        </div>
        <div class="bid-form">
            <input type="number" name="bid_amount" placeholder="入札額">
            <button type="submit">入札</button>
        </div>
    </div>
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

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("slide");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex-1].style.display = "block";
    }

    /*
    // 自動スライド切り替え機能をコメントアウト
    let autoSlideTimer = setInterval(function() {
        plusSlides(1);
    }, 3000);

    document.querySelector(".slideshow-container").addEventListener("click", function() {
        clearInterval(autoSlideTimer);
        autoSlideTimer = setInterval(function() {
            plusSlides(1);
        }, 3000);
    });
    */
</script>
</body>
</html>
