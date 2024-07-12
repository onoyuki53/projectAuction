<?php
/* 商品出品 */

ini_set('display_errors', true);
error_reporting(E_ALL);

session_start();
require '../database.php';

function random($length = 8){
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

// クッキーが設定されているか確認
if (!isset($_COOKIE['user_name'])) {
    $logged_in = false;
} else {
    $logged_in = true;
    $user_id = $_COOKIE['user_name'];
}
// クッキーが設定されているか確認
if (!isset($_COOKIE['user_name'])) {
    header("Location: ../login.php");
    exit();
}
$item_id = random();
$item_name = '';
$item_price = 0;
$max_price = 0;
$item_user = '';
$item_status = 0;
$item_category = ''; // カテゴリーを追加

$item_user = $user_id;

if (isset($_POST['upload'])) {
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $max_price = $_POST['max_price'];
    $item_category = $_POST['category']; // カテゴリーの取得

    $MAX = count($_FILES['image']['name'] ?? []);
    for ($i = 0; $i < $MAX; $i++) {
        $image = $_FILES['image']['name'][$i];
        $file = './images/' . $item_id . '_' . $image;
        $pdo = connect();
        $stmt = $pdo->prepare('INSERT INTO `Item_Image` (`item_id`, `image_path`) VALUES (:item_id, :images)');
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':images', $file);

        if (!empty($_FILES)) {
            $filename = $_FILES['image']['name'][$i];
            $uploaded_path = '../images/' . $item_id . '_' . $filename;
            $result = move_uploaded_file($_FILES['image']['tmp_name'][$i], $uploaded_path);
            if ($result) {
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                    $message = '同じ画像は追加できません';
                }
            } else {
                $message = '画像ファイルではありません';
            }
        }
    }

    $pdo = connect();
    $stmt2 = $pdo->prepare("INSERT INTO `Item` (`item_id`, `item_name`, `item_price`, `max_price`, `item_user`, `category`) VALUES (:item_id, :item_name, :item_price, :max_price, :item_user, :category)");
    $stmt2->bindParam(':item_id', $item_id);
    $stmt2->bindParam(':item_name', $item_name);
    $stmt2->bindParam(':item_price', $item_price);
    $stmt2->bindParam(':max_price', $max_price);
    $stmt2->bindParam(':item_user', $item_user);
    $stmt2->bindParam(':category', $item_category); // カテゴリーのバインド
    $stmt2->execute();

    $message = '商品をアップロードしました';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>出品画面</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../registst.css" rel="stylesheet">
</head>
<body>
<div class="header">
	    <div class="header_logo">
        <a href="../index.php">
            <img src="../img/logo_square.png" alt="Logo">
        </a>
        </div>
        <div class="header_btn">
         <?php if ($logged_in): ?>
      	   <a href="./mypage.php" class="btn btn-primary">マイページ</a>
      	   <a href="../logout.php" class="btn btn-secondary">ログアウト</a>
         <?php else: ?>
           <a href="../login.php" class="btn btn-primary">ログイン</a>
         <?php endif; ?>
         </div>
     </div>

 <div class="container">
<h1 class="card-title text-center">出品画面</h1>

<?php if (isset($_POST['upload'])): ?>
    <p><?php echo $message; ?></p>
    <p><a href="../index.php">ホームへ</a></p>
<?php else: ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="item_name">商品名:</label>
            <input class="form-control" type="text" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="item_price">初期価格:</label>
            <input class="form-control" type="number" name="item_price" required>
        </div>
        <div class="form-group">
            <label for="max_price">即決価格:</label>
            <input class="form-control" type="number" name="max_price" required>
        </div>
        <div class="form-group">
            <label for="category">カテゴリー:</label>
            <select class="form-control" name="category" required>
                <option value="腕時計">腕時計</option>
                <option value="鞄">鞄</option>
                <option value="自転車">自転車</option>
                <option value="衣類">衣類</option>
                <option value="その他">その他</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image[]">商品画像:</label>
            <input type="file" name='image[]' multiple="multiple" onchange="preview(this);">
            <div id="preview"></div>
        </div>
        <input class="btn btn-primary btn-block" type='submit' name='upload' value='出品'>
    </form>
    <script>
        function preview(obj) {
            for (let i = 0; i < obj.files.length; i++) {
                const fileReader = new FileReader();
                fileReader.onload = (function (e) {
                    document.getElementById('preview').innerHTML += '<img src="' + e.target.result + '" width="300">';
                });
                fileReader.readAsDataURL(obj.files[i]);
            }
        }
    </script>
<?php endif; ?>
</div>
</body>
</html>