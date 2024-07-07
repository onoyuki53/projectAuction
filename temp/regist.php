<?php
$dsn = 'mysql:host=localhost;dbname=login_sample;charset=utf8mb4;';
$username = 'user1';
$password = 'passwordA1!';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
$pdo = new PDO($dsn, $username, $password, $options);

$err = [];
$registrationSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = filter_input(INPUT_POST, 'user_id');
    $password = filter_input(INPUT_POST, 'password');
    $password_conf = filter_input(INPUT_POST, 'password_conf');
    $mail_address = filter_input(INPUT_POST, 'mail_address');
    $address = filter_input(INPUT_POST, 'address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');

    if ($user_id === '') {
        $err['user_id'] = 'ユーザーIDは入力必須です。';
    }
    if ($password === '') {
        $err['password'] = 'パスワードは入力必須です。';
    }
    if ($password !== $password_conf) {
        $err['password_conf'] = 'パスワードが一致しません。';
    }
    if ($mail_address === '') {
        $err['mail_address'] = 'メールアドレスは入力必須です。';
    }
    if ($address === '') {
        $err['address'] = '住所は入力必須です。';
    }
    if ($phone_number === '') {
        $err['phone_number'] = '電話番号は入力必須です。';
    }

    if (empty($err)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        if ($stmt->fetch()) {
            $err['user_id'] = 'このユーザーIDは既に使用されています。';
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (user_id, password, mail_address, address, phone_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, password_hash($password, PASSWORD_DEFAULT), $mail_address, $address, $phone_number]);
            $registrationSuccess = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録フォーム</title>
</head>
<body>
    <h2>ユーザー登録フォーム</h2>
    <?php if ($registrationSuccess): ?>
        <p>登録が完了しました。</p>
    <?php else: ?>
        <?php if (!empty($err)): ?>
            <?php foreach ($err as $e): ?>
                <p style="color: red;"><?php echo $e; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <form action="regist.php" method="post" class="h-adr">
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
    <span class="p-country-name" style="display:none;">Japan</span>
    <div>
        <label for="user_id">ユーザーID:</label>
        <input type="text" id="user_id" name="user_id" required>
    </div>
    <div>
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="password_conf">パスワード（確認用）:</label>
        <input type="password" id="password_conf" name="password_conf" required>
    </div>
    <div>
        <label for="mail_address">メールアドレス:</label>
        <input type="email" id="mail_address" name="mail_address" required>
    </div>
    <div>
        〒<input type="text" class="p-postal-code" size="8" maxlength="8"><br>
        <label for="address">住所:</label>
        <input type="text" id="address" name="address" class="p-region p-locality p-street-address p-extended-address" required>
    </div>
    <div>
        <label for="phone_number">電話番号:</label>
        <input type="tel" id="phone_number" name="phone_number" required>
    </div>
    <div>
        <button type="submit">登録</button>
    </div>
</form>
    <?php endif; ?>
</body>
</html>