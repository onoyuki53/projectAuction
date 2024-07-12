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
$update_success = false; // Variable to track if updates were successful

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        // 現在のパスワードの確認
        $current_password = filter_input(INPUT_POST, 'current_password');
        $stmt = $pdo->prepare('SELECT password FROM User WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $login_user);
        $stmt->execute();
        $stored_password = $stmt->fetchColumn();

        if (password_verify($current_password, $stored_password)) {
            // 新しいメールアドレスの更新
            $new_mail = filter_input(INPUT_POST, 'mail');
            if (!empty($new_mail)) {
                $stmt = $pdo->prepare('UPDATE User SET mail = :mail WHERE user_id = :user_id');
                $stmt->bindValue(':mail', $new_mail);
                $stmt->bindValue(':user_id', $login_user);
                $stmt->execute();
                $update_success = true;
            }

            // 新しいパスワードの更新
            $new_password = filter_input(INPUT_POST, 'new_password');
            $new_password_conf = filter_input(INPUT_POST, 'new_password_conf');
            if (!empty($new_password) && $new_password === $new_password_conf) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE User SET password = :password WHERE user_id = :user_id');
                $stmt->bindValue(':password', $hashed_password);
                $stmt->bindValue(':user_id', $login_user);
                $stmt->execute();
                $update_success = true;
            } elseif ($new_password !== $new_password_conf) {
                $err[] = "新しいパスワードが一致しません。";
            }

            // 住所と電話番号の更新
            $new_address = filter_input(INPUT_POST, 'address');
            $new_phone = filter_input(INPUT_POST, 'phone');
            if (!empty($new_address) || !empty($new_phone)) {
                $stmt = $pdo->prepare('INSERT INTO UserAdd (user_id, address, phone) VALUES (:user_id, :address, :phone) ON DUPLICATE KEY UPDATE address = VALUES(address), phone = VALUES(phone)');
                $stmt->bindValue(':user_id', $login_user);
                $stmt->bindValue(':address', $new_address);
                $stmt->bindValue(':phone', $new_phone);
                $stmt->execute();
                $update_success = true;
            }
        } else {
            $err[] = "現在のパスワードが正しくありません。";
        }
    }

    // クレジットカード情報の登録
    if (isset($_POST['register_credit'])) {
        // 省略
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
    <title>ユーザー情報変更</title>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="./registst.css" rel="stylesheet">
</head>
<body>
  <div class="header">
    <div class="header_logo">
        <img src="./logo_square.png" alt="Logo">
    </div>
  </div>
  <div class="container">

    <h2 class="card-title text-center">ユーザー情報変更</h2>
    <?php if (!empty($err)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo implode('<br>', $err); ?>
        </div>
    <?php elseif ($update_success): ?>
        <div class="alert alert-success" role="alert">
            更新が完了しました。
        </div>
    <?php endif; ?>
    <h3 class="mt-3">基本情報</h3>
    <form action="" method="post" class="h-adr">
        <span class="p-country-name" style="display:none;">Japan</span>
        <div class="form-group">
            <label for="mail">メールアドレス:</label>
            <input type="email" id="mail" name="mail" class="form-control" value="<?php echo htmlspecialchars($user_info['mail'], ENT_QUOTES); ?>">
        </div>
        <div class="form-group">
            <label for="current_password">現在のパスワード:</label>
            <input type="password" id="current_password" name="current_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="new_password">新しいパスワード:</label>
            <input type="password" id="new_password" name="new_password" class="form-control">
        </div>
        <div class="form-group">
            <label for="new_password_conf">新しいパスワード（確認用）:</label>
            <input type="password" id="new_password_conf" name="new_password_conf" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="postal">郵便番号:</label>
            <input type="text" class="p-postal-code form-control" size="8" maxlength="8" name="postal">
        </div>
        <div class="form-group">
            <label for="address">住所:</label>
            <input type="text" id="address" name="address" class="p-region p-locality p-street-address p-extended-address form-control" value="<?php echo htmlspecialchars($user_add_info['address'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">電話番号:</label>
            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_add_info['phone'] ?? '', ENT_QUOTES); ?>">
        </div>
        <div>
            <button class="btn btn-secondary" type="submit" name="update">更新</button>
        </div>
    </form>
    <h3 class="mt-3">クレジットカード情報</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="credit">クレジットカード番号:</label>
            <input type="text" id="credit" name="credit" class="form-control" value="<?php echo htmlspecialchars($credit_info['credit'] ?? '', ENT_QUOTES); ?>">
        </div>
        <div>
            <button class="btn btn-secondary" type="submit" name="register_credit">登録</button>
        </div>
    </form>
    <div>
    <a href="mypage.php" class="btn btn-primary mt-3">マイページに戻る</a>
    </div>
  </div>
</body>
</html>
