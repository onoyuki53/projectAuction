<?php
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=auction;charset=utf8';
$user = 'user1';
$password = 'passwordA1!';

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'データベース接続失敗: ' . $e->getMessage();
    exit;
}

session_start();

if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

$login_user = $_SESSION['login_user'];
$err = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $new_mail = filter_input(INPUT_POST, 'mail');
        $new_password = filter_input(INPUT_POST, 'password');
        $new_address = filter_input(INPUT_POST, 'address');
        $new_phone = filter_input(INPUT_POST, 'phone');

        if (!empty($new_mail)) {
            $stmt = $pdo->prepare('UPDATE User SET mail = :mail WHERE user_id = :user_id');
            $stmt->bindValue(':mail', $new_mail);
            $stmt->bindValue(':user_id', $login_user);
            $stmt->execute();
        }

        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE User SET password = :password WHERE user_id = :user_id');
            $stmt->bindValue(':password', $hashed_password);
            $stmt->bindValue(':user_id', $login_user);
            $stmt->execute();
        }

        if (!empty($new_address) || !empty($new_phone)) {
            $stmt = $pdo->prepare('INSERT INTO UserAdd (user_id, address, phone) VALUES (:user_id, :address, :phone) ON DUPLICATE KEY UPDATE address = VALUES(address), phone = VALUES(phone)');
            $stmt->bindValue(':user_id', $login_user);
            $stmt->bindValue(':address', $new_address);
            $stmt->bindValue(':phone', $new_phone);
            $stmt->execute();
        }
    }

    if (isset($_POST['register_credit'])) {
        $credit = filter_input(INPUT_POST, 'credit');
        if (!empty($credit)) {
            // クレジットカード情報が既に存在するか確認
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM User_Credit WHERE user_id = :user_id');
            $stmt->bindValue(':user_id', $login_user);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // 既に存在する場合、更新
                $stmt = $pdo->prepare('UPDATE User_Credit SET credit = :credit WHERE user_id = :user_id');
                $stmt->bindValue(':credit', $credit);
                $stmt->bindValue(':user_id', $login_user);
                $stmt->execute();
            } else {
                // 存在しない場合、新規登録
                $stmt = $pdo->prepare('INSERT INTO User_Credit (user_id, credit) VALUES (:user_id, :credit)');
                $stmt->bindValue(':user_id', $login_user);
                $stmt->bindValue(':credit', $credit);
                $stmt->execute();
            }
        }
    }
}

// ユーザー情報の取得
$stmt = $pdo->prepare('SELECT * FROM User WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $login_user);
$stmt->execute();
$user_info = $stmt->fetch(PDO::FETCH_ASSOC);

// ユーザー追加情報の取得
$stmt = $pdo->prepare('SELECT * FROM UserAdd WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $login_user);
$stmt->execute();
$user_add_info = $stmt->fetch(PDO::FETCH_ASSOC);

// クレジットカード情報の取得
$stmt = $pdo->prepare('SELECT * FROM User_Credit WHERE user_id = :user_id');
$stmt->bindValue(':user_id', $login_user);
$stmt->execute();
$credit_info = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
</head>
<body>
    <h2>マイページ</h2>
    <form action="" method="post" class="h-adr">
        <span class="p-country-name" style="display:none;">Japan</span>
        <div>
            <label for="mail">メールアドレス:</label>
            <input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($user_info['mail'], ENT_QUOTES); ?>">
        </div>
        <div>
            <label for="password">新しいパスワード:</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <label for="postal">〒:</label>
            <input type="text" class="p-postal-code" size="8" maxlength="8" name="postal">
        </div>
        <div>
            <label for="address">住所:</label>
            <input type="text" id="address" name="address" class="p-region p-locality p-street-address p-extended-address" value="<?php echo htmlspecialchars($user_add_info['address'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div>
            <label for="phone">電話番号:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user_add_info['phone'] ?? '', ENT_QUOTES); ?>">
        </div>
        <div>
            <button type="submit" name="update">更新</button>
        </div>
    </form>
    <h3>クレジットカード情報</h3>
    <form action="" method="post">
        <div>
            <label for="credit">クレジットカード番号:</label>
            <input type="text" id="credit" name="credit" value="<?php echo htmlspecialchars($credit_info['credit'] ?? '', ENT_QUOTES); ?>">
        </div>
        <div>
            <button type="submit" name="register_credit">登録</button>
        </div>
    </form>
    <p><a href="mypage.php">mypageに戻る</a></p>
</body>
</html>
