<?php
ini_set('display_erros', true);
error_reporting((E_ALL));

session_start();

require 'database.php';

$erro = [];

$item_id = '123456789';

if (isset($_POST['upload'])) {
    //$image = uniqid(mt_rand(), true); //ファイル名をユニーク化
    $MAX = count($_FILES['image']['name'] ?? []);
    for($i=0; $i<$MAX; $i++){
        $image = $_FILES['image']['name'][$i];
        //$image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1); //アップロードされたファイルの拡張子を取得
        $file = './images/' . $item_id . '_' . $image;
        $pdo = connect();
        $stmt = $pdo->prepare('INSERT INTO `Item_Image` (`item_id`, `image_path`) VALUES (:item_id, :images)');
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':images', $file);
        if (!empty($_FILES)) { //ファイルが選択されていれば$imageにファイル名を代入
                $filename = $_FILES['image']['name'][$i];
                $uploaded_path = './images/' . $item_id . '_' . $filename;
                $result = move_uploaded_file($_FILES['image']['tmp_name'][$i], $uploaded_path); //imagesディレクトリにファイル保存
            //print($result);
            if ($result) { //画像ファイルかのチェック
                $message = '画像をアップロードしました' . $file;
                try{
                    $stmt->execute();
                } catch (Exception $e){
                    $message = '同じ画像は追加できません';
                }
            } else {
                $message = '画像ファイルではありません';
            }
        }
    }


}
?>

<h1>画像アップロード</h1>
<!--送信ボタンが押された場合-->
<?php if (isset($_POST['upload'])) : ?>
    <p><?php echo $message; ?></p>
    <p><a href="image.php">画像表示へ</a></p>
<?php else : ?>
    <form method="post" enctype="multipart/form-data">
        <p>アップロード画像</p>
        <input type="file" name="image[]" multiple>
        <input type="submit" name="upload" value="送信">
    </form>
<?php endif; ?>