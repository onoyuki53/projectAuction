<?php
session_start();

// ステップ1からのデータがセッションにない場合は、ステップ1のページにリダイレクト
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['password']) || !isset($_SESSION['mail_address'])) {
//      header('Location: registst1.php');
//      exit;
// }

$err = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = filter_input(INPUT_POST, 'phone');
    $address = filter_input(INPUT_POST, 'address');
    $user_id = $_SESSION['user_id'];
    $password = $_SESSION['password'];
    $mail_address = $_SESSION['mail_address'];

    if ($phone === '') {
        $err['phone'] = '電話番号は入力必須です。';
    } else {
        // ハイフンを除去
        // ハイフンを除去して電話番号を処理
    $phoneNoHyphen = preg_replace('/[^0-9]/', '', $phone);
    // 電話番号が11文字以内であることを確認
    if (strlen($phoneNoHyphen) > 11) {
        $err['phone'] = '電話番号は11文字以内で入力してください。';
    } elseif (!ctype_digit($phoneNoHyphen)) {
        $err['phone'] = '電話番号は数字のみで入力してください。';
    }
    }

    if ($address === '') {
        $err['address'] = '住所は入力必須です。';
    }

    if (empty($err)) {
        try {
            // データベース接続設定
            $pdo = new PDO('mysql:host=localhost;dbname=auction;charset=utf8mb4', 'user1', 'passwordA1!');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            // UserAddテーブルにユーザー情報を挿入するSQL文を準備
            $stmtInsertUserAdd = $pdo->prepare("INSERT INTO UserAdd (`user_id`, `address`, `phone`) VALUES (:user_id, :address, :phone)");
            $stmtInsertUserAdd->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmtInsertUserAdd->bindParam(':address', $address, PDO::PARAM_STR);
            $stmtInsertUserAdd->bindParam(':phone', $phoneNoHyphen, PDO::PARAM_INT);
    
            // SQL文を実行
            $stmtInsertUserAdd->execute();
    
            // メール送信
            $to = $mail_address;
            $subject = 'オークションサイトへの登録完了';
            $message = '<!DOCTYPE html>
            <html lang="ja">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>予約情報の登録ありがとうございました</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f0f0f0;
                    }
                    .container {
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 5px;
                        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
                    }
                    h1 {
                        color: #333;
                        text-align: center;
                    }
                    .info-box {
                        background-color: #f9f9f9;
                        border: 1px solid #ddd;
                        padding: 10px;
                        margin: 10px 0;
                        border-radius: 5px;
                    }
                    p {
                        color: #666;
                        line-height: 1.6;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>オークションサイトへの登録ありがとうございました。</h1>
                    <div class="info-box">
                        <p>ユーザー名: <strong>' . htmlspecialchars($user_id) . '</strong></p>
                        <p>パスワード: <strong>********</strong></p>
                        <p>メールアドレス: <strong>' . htmlspecialchars($mail_address) . '</strong></p>
                        <p>電話番号: <strong>' . htmlspecialchars($phone) . '</strong></p>
                        <p>住所: <strong>' . htmlspecialchars($address) . '</strong></p>
                    </div>
                </div>
            </body>
            </html>';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            $headers .= 'From: onoyuki53@yuki-virtual-machine' . "\r\n";
    
            mail($to, $subject, $message, $headers);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['mail_address'] = $mail_address;
            $_SESSION['password'] = '********';
            $_SESSION['phone'] = $phone;
            $_SESSION['address'] = $phone;
            
            header('Location: registst3.php');
            exit;
        } catch (PDOException $e) {
            $err['db'] = 'データベースエラー: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録フォーム - ステップ2</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../registst.css" rel="stylesheet">
</head>
<body>
<div class="header">
	<div class="header_logo">
        <a href="./index.php">
            <img src="./img/logo_square.png" alt="Logo">
        </a>
        </div>
        <input type="text" id="k" name="k" class="form-control" placeholder="検索" required>
        <button type="submit" class="btn btn-primary btn-block">検索</button>
     </div>
     <div class="container">
    <h2 class="card-title text-center">ユーザー登録フォーム - ステップ2</h2>
    <?php if (!empty($err)): ?>
        <ul>
        <?php foreach ($err as $e): ?>
            <li style="color: red;"><?php echo $e; ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form action="" method="post" class="h-adr">
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <span class="p-country-name" style="display:none;">Japan</span>
        <div class="form-group">
            <label for="phone">電話番号:</label>
            <input type="text" id="phone" name="phone" size="11" maxlength="11" class="form-control" required>
        </div>
        <div class="form-group">
        <label>郵便番号:</label>
            <input type="text" class="form-control p-postal-code" size="8" maxlength="8" name="postal">
        </div>
        <div class="form-group">    
            <label for="address">住所:</label>
            <input type="text" id="address" name="address" class="form-control p-region p-locality p-street-address p-extended-address" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-block">登録</button>
        </div>
    </form>
    </div>
</body>
</html>