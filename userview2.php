<?php
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "auction";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = null; // 結果の初期化

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ユーザー入力を受け取る
    $user_input = $conn->real_escape_string($_POST['user_input']);

    // ユーザー入力がSQL文かユーザーIDかを判定
    if (stripos($user_input, "SELECT") === 0 || stripos($user_input, "select") === 0 || stripos($user_input, "UNION") === 0 || stripos($user_input, "union") === 0) {
        // ユーザー入力がSQL文の場合
        $sql = $user_input;
    } else {
        // ユーザー入力がユーザーIDの場合
        $sql = "SELECT Item.item_id AS id, Item.item_name AS name, Item.item_price AS price, Item.category AS category 
                FROM User 
                JOIN Item ON User.user_id = Item.item_user 
                WHERE User.user_id = '$user_input'";
    }

    // クエリを実行
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Query Executor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="header_logo">
            <a href="./index.php">
                <img src="./img/logo_square.png" alt="Logo">
            </a>
        </div>
        <div class="header_btn">
            <a href="./buyitem.php" class="btn btn-primary">買い物かご</a>
            <a href="./regist/mypage.php" class="btn btn-primary">マイページ</a>
            <a href="./logout.php" class="btn btn-secondary">ログアウト</a>
        </div>
    </div>

    <div class="container">
        <h1>SQL Query Executor</h1>
        <form method="post" class="mb-4">
            <div class="form-group">
                <label for="user_input">ユーザーIDまたはSQL文を入力してください:</label>
                <textarea id="user_input" name="user_input" rows="4" cols="50" class="form-control"></textarea>
            </div>
            <input type="submit" value="実行" class="btn btn-primary">
        </form>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="container">
            <?php if ($result !== null && $result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php while ($fieldinfo = $result->fetch_field()): ?>
                                <th><?= htmlspecialchars($fieldinfo->name) ?></th>
                            <?php endwhile; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <?php foreach ($row as $key => $value): ?>
                                    <?php if ($key == 'id'): ?>
                                        <td><a href="itemdetails.php?item_id=<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['id']) ?></a></td>
                                    <?php else: ?>
                                        <td><?= htmlspecialchars($value) ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($result !== null): ?>
                <div class="alert alert-warning">結果が見つかりませんでした。</div>
            <?php else: ?>
                <div class="alert alert-danger">エラー: <?= htmlspecialchars($conn->error) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <footer>
    <p>&copy; 2024 Tech Auction @Canva</p>
</footer>
</body>
</html>

<?php
$conn->close(); // データベース接続のクローズ
?>