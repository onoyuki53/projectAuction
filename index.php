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
// $sql = "SELECT Item.item_id, Item.item_name, Item.item_price, Item_Image.image_path FROM Item LEFT JOIN Item_Image ON Item.item_id = Item_Image.item_id;";
$sql = "SELECT i.item_id, i.item_name, i.item_price, i.max_price, ii.image_path 
        FROM Item i 
        JOIN (SELECT item_id, MIN(image_path) as image_path FROM Item_Image GROUP BY item_id) ii 
        ON i.item_id = ii.item_id
        WHERE i.is_sold != 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ブランドバンクオークション - 腕時計一覧</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
    <div class="header">
	    <div class="header_logo">
        <a href="./index.php">
            <img src="./logo_square.png" alt="Logo">
        </a>
        </div>
        <div class="header_btn">
         <?php if ($logged_in): ?>
      	   <a href="./mypage.php" class="btn btn-primary">マイページ</a>
      	   <a href="./logout.php" class="btn btn-secondary">ログアウト</a>
         <?php else: ?>
           <a href="./login.php" class="btn btn-primary">ログイン</a>
         <?php endif; ?>
         </div>
     </div>

 <div class="container">
 </div>
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
        <li><a href="#">鞄</a></li>
        <li><a href="#">自転車</a></li>
	<li><a href="#">衣類</a></li>
        <li><a href="#">その他</a></li>
      </ul>
    </div>

    <div class="product-list">
    <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo '<div class="product-item">';
              echo '<a href="itemdetails.php?item_id=' . $row["item_id"] . '">';
              echo '<div class="product-image"><img src="' . $row["image_path"] . '" alt="' . $row["item_id"] . '"></div>';
              echo '<div class="product-info">';
              echo '<h3>' . $row["item_name"] . '</h3>';
              echo '<p>¥' . number_format($row["item_price"]) . '</p>';
              echo '<p>現在の入札額: ¥' . number_format($row["item_price"]) . '</p>';
              echo '<p style="color: red;">即決価格: ¥' . number_format($row["max_price"]) . '</p>';
              echo '</div>';
              echo '</a>'; // Close the anchor tag here
                //   if ($logged_in) {
                //       echo '<div class="bid-form">';
                //       echo '<form method="POST" action="bid.php">';
                //       echo '<input type="hidden" name="item_id" value="' . $row["item_id"] . '">';
                //       echo '<input type="number" name="bid_amount" placeholder="入札額" required />';
                //       echo '<button typea="submit">入札</button>';
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
    <div class="slide">
      <img src="bag_category.png" alt="Image 1">
    </div>
    <div class="slide">
      <img src="bike_category.png" alt="Image 2">
    </div>
    <div class="slide">
      <img src="clothes_category.png" alt="Image 3">
    </div>
    <div class="slide">
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
    <p>&copy; 2024 テックオークション</p>
  </footer>
</body>
</html>

<?php
$conn->close(); // データベース接続のクローズ
?>
