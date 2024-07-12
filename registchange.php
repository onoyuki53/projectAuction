<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録フォーム - ステップ1</title>
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
</div>
<div class="container">
    <h2>ユーザー登録フォーム - ステップ1</h2>
    <?php if (!empty($err)): ?>
        <?php foreach ($err as $e): ?>
            <p style="color: red;"><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
    <form action="" method="post">
        <div>
            <label for="user_id">ユーザーID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
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
            <input type="email" id="mail_address" name="mail_address" value="<?php echo htmlspecialchars($mail_address ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">次へ</button>
        </div>
    </form>
</div>
</body>
</html>
