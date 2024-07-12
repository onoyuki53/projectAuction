<?php
session_start();

// // ステップ2からのデータがセッションにない場合は、ステップ1のページにリダイレクト
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['password']) || !isset($_SESSION['mail_address'])) {
//      header('Location: registst1.php');
//      exit;
// }

$err = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credit_card = filter_input(INPUT_POST, 'credit_card');

    if ($credit_card === '') {
        $err['credit_card'] = 'クレジットカード番号は入力必須です。';
    } elseif (!ctype_digit($credit_card) || strlen($credit_card) !== 16) {
        $err['credit_card'] = '有効なクレジットカード番号を入力してください。';
    }

    if (empty($err)) {
        try {
            // データベース接続設定
            $pdo = new PDO('mysql:host=localhost;dbname=auction;charset=utf8mb4', 'user1', 'passwordA1!');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // セッションからユーザーIDを取得
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];

                // SQL文を準備
                $stmt = $pdo->prepare('INSERT INTO User_Credit (user_id, credit) VALUES (:user_id, :credit)');

                // パラメータをバインド
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt->bindParam(':credit', $credit_card, PDO::PARAM_INT);

                // SQL文を実行
                $stmt->execute();

                // 登録完了後、次のページにリダイレクト
                // セッションクリア
                session_destroy();
                header('Location: register_complete.php');
                exit;
            } else {
                $err['session'] = 'セッションからユーザーIDが取得できませんでした。';
            }
        } catch (PDOException $e) {
            // エラー処理
            $err['db'] = 'データベースエラー: '.$e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録フォーム - ステップ3</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../registst.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="header_logo">
        <a href="./index.php">
            <img src="../img/logo_square.png" alt="Logo">
        </a>
    </div>
</div>
<div class="container">
    <h2 class="card-title text-center">ユーザー登録フォーム - ステップ3</h2>
    <?php if (!empty($err)): ?>
        <div class="alert alert-danger">
            <?php foreach ($err as $e): ?>
                <p><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="credit_card">クレジットカード番号:</label>
            <input type="text" id="credit_card" name="credit_card" class="form-control" maxlength="16" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-block">登録</button>
        </div>
    </form>
</div>
</body>
</html>
