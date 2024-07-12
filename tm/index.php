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
    $logged_in = false;
} else {
    $logged_in = true;
    $user_id = $_COOKIE['user_name'];
}

// 商品情報の取得と表示
$sql = "SELECT Item.item_id, Item.item_name, Item.item_price, Item_Image.image_path FROM Item LEFT JOIN Item_Image ON Item.item_id = Item_Image.item_id;";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ブランドバンクオークション - 腕時計一覧</title>
  <style>
    body {
        background-color: #d6d4d4;
    }
    /* ヘッダー */
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

    /* 検索ボックス */
    .search-box-container {
    display: flex;
    justify-content: center;
    margin: 20px 20px;
    flex-direction: column;
    align-items: center;
    }

    .search-box {
    display: flex;
    width: 100%;
    max-width: 1000px;
    margin-bottom: 20px;
    }

    .search-box input[type="text"] {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
    width: 100%;
    }

    .search-box button {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    }

    .search-box button:hover {
    background-color: #555;
    }

    /* カテゴリーリンクの画像 */
    .category-slider {
    display: flex;
    justify-content: center;
    width: 100%;
    overflow: hidden;
    }

    .category-slider img {
    width: 100%;
    margin-right: 10px;
    object-fit: cover;
    }

    /* レイアウト */
    .main-container {
    display: flex;
    padding: 20px;
    gap: 20px;
    }

    /* カテゴリーフレーム */
    .category-frame {
    background-color: #f4f4f4;
    width: 20%;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }

    .category-frame h2 {
    font-size: 18px;
    margin-top: 0;
    }

    .category-frame ul {
    list-style: none;
    padding: 0;
    }

    .category-frame ul li {
    margin-bottom: 10px;
    }

    .category-frame ul li a {
    color: #333;
    text-decoration: none;
    font-size: 16px;
    }

    .category-frame ul li a:hover {
    text-decoration: underline;
    }

    /* 商品一覧 */
    .product-list {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    width: 80%;
    }

    .product-item {
    background-color: #f1f1f1;
    padding: 20px;
    display: flex;
    width: 100%;
    margin-bottom: 20px;
    align-items: center;
    border: 1px solid #ddd;
    text-decoration: none;
    color: inherit;
    }
    .product-item a {
    display: flex;
    text-decoration: none;
    color: inherit;
    }

    .product-item .product-image {
        flex-shrink: 0;
        margin-right: 20px;
    }

    .product-item .product-image img {
    max-width: 250px; /* Adjust the size as needed */
    height: auto;
    }

    .product-item .product-info {
    flex: flex;
    text-align: column;
    }

    .product-item .product-info h3 {
    margin-top: 0;

    }

    .product-item .product-info p {
    margin-bottom: 10px;
    }

    /* Bid form, if needed */
    .product-item .bid-form {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    }

    .product-item .bid-form input[type="number"] {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100px;
    margin-bottom: 10px;
    }

    .product-item .bid-form button {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    }

    .product-item .bid-form button:hover {
    background-color: #555;
    }

    /* フッター */
    footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
    }

    footer p {
    margin: 0;
    }

    footer a {
    color: #fff;
    text-decoration: none;
    margin-left: 10px;
    }

    footer a:hover {
    text-decoration: underline;
    }

    /* カテゴリースライド */
    .slideshow-container {
    max-width: 1000px;
    position: relative;
    margin: auto;
    }

    .slide {
    display: none;
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

    .fade {
    animation-name: fade;
    animation-duration: 1.5s;
    }

    @keyframes fade {
    from {opacity: .4} 
    to {opacity: 1}
    }

    /* 新しいクラス */
    .product-link {
    display: flex;
    text-decoration: none;
    color: inherit;
    width: 100%;
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

  <div class="category-slider">
    <img src="watch_category.png" alt="カテゴリ1" height="500">
  </div>

  <div class="search-box-container">
    <div class="search-box">
      <input type="text" placeholder="商品を検索..." />
      <button>検索</button>
    </div>
  </div>

  <div class="main-container">
    <div class="category-frame">
      <h2>カテゴリ</h2>
      <ul>
        <li><a href="#">腕時計</a></li>
        <li><a href="#">ジュエリー</a></li>
        <li><a href="#">バッグ</a></li>
        <li><a href="#">アクセサリー</a></li>
        <li><a href="#">その他</a></li>
    </ul>
    </div>

    <div class="product-list">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="product-item">';
                echo '<a href="itemdetails.php?item_id=' . $row["item_id"] . '">';
                echo '<div class="product-image"><img src="' . $row["image_path"] . '" alt="' . $row["item_name"] . '"></div>';
                echo '<div class="product-info">';
                echo '<h3>' . $row["item_name"] . '</h3>';
                echo '<p>¥' . number_format($row["item_price"]) . '</p>';
                echo '<p>現在の入札額: ¥' . number_format($row["item_price"]) . '</p>';
                echo '</div>';
                echo '</a>'; // Close the anchor tag here
                //   if ($logged_in) {
                //       echo '<div class="bid-form">';
                //       echo '<form method="POST" action="bid.php">';
                //       echo '<input type="hidden" name="item_id" value="' . $row["item_id"] . '">';
                //       echo '<input type="number" name="bid_amount" placeholder="入札額" required />';
                //       echo '<button type="submit">入札</button>';
                //       echo '</form>';
                //       echo '</div>';
                //   } else {
                //       echo '<a href="login.php" class="login-button">ログインして入札</a>';
                //   }
                echo '</div>'; // Close the product item div here
                
            }
        } else {
            echo "商品が見つかりませんでした。";
        }
        ?>
</div>
  </div>

  </div>
  <div class="slideshow-container">
    <p>他のカテゴリー</p>
    <div class="slide fade">
      <img src="bag_category.png" alt="Image 1">
    </div>
    <div class="slide fade">
      <img src="bike_category.png" alt="Image 2">
    </div>
    <div class="slide fade">
      <img src="clothes_category.png" alt="Image 3">
    </div>
    <div class="slide fade">
        <img src="watch_category.png" alt="Image 3">
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>

  <script src="script.js"></script>
  </div>

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

let autoSlideTimer = setInterval(function() {
  plusSlides(1);
}, 3000);

document.querySelector(".slideshow-container").addEventListener("click", function() {
  clearInterval(autoSlideTimer);
  autoSlideTimer = setInterval(function() {
    plusSlides(1);
  }, 3000);
});
  </script>

  <footer>
    <p>&copy; 2024 ブランドバンクオークション</p>
    <p>
      <a href="#">プライバシーポリシー</a>
    </p>
  </footer>
</body>
</html>

<?php
$conn->close(); // データベース接続のクローズ
?>
