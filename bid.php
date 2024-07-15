bid.php

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
    header("Location: ./regist/login.php");
    exit();
}

// クッキーからユーザー名を取得
$user_id = $_COOKIE['user_name'];

// POSTデータの取得
$item_id = $_POST['item_id'];
$bid_amount = (float)$_POST['bid_amount']; // 入札額を浮動小数点数にキャスト

// 現在の入札額とmax_priceを取得
$sql = "SELECT item_price, max_price FROM Item WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $item_id);
$stmt->execute();
$stmt->bind_result($current_price, $max_price);
$stmt->fetch();
$stmt->close();

if ($bid_amount < $current_price) {
    // 入札額が現在の入札額より小さい場合
    header("Location: itemdetails.php?item_id=$item_id&status=error&message=入札額が低いため入札できません");
} elseif ($bid_amount > $max_price) {
    // 入札額が即決価格を超えている場合
    header("Location: itemdetails.php?item_id=$item_id&status=error&message=入札額が即決価格を超えています。");
    exit();
}elseif ($bid_amount >= $max_price) {
    // 商品の購入が完了したことを表示し、is_soldを1に更新
    $sql = "UPDATE Item SET item_price = ?, buy_user = ?, is_sold = 1 WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dss", $bid_amount, $user_id, $item_id);
    $stmt->execute();
    $stmt->close();

    // ユーザー情報を取得
    $sql = "SELECT u.mail, ua.address, ua.phone FROM User u JOIN UserAdd ua ON u.user_id = ua.user_id WHERE u.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($mail_address, $address, $phone);
    $stmt->fetch();
    $stmt->close();

    // 商品情報を取得
    $sql = "SELECT item_name FROM Item WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $item_id);
    $stmt->execute();
    $stmt->bind_result($item_name);
    $stmt->fetch();
    $stmt->close();

    // メール送信
    $to = $mail_address;
    $subject = '商品の購入が完了しました';
    $message = '<!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>商品の購入が完了しました</title>
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
            <h1>商品の購入が完了しました。</h1>
            <div class="info-box">
                <p>ユーザー名: <strong>' . htmlspecialchars($user_id) . '</strong></p>
                <p>メールアドレス: <strong>' . htmlspecialchars($mail_address) . '</strong></p>
                <p>電話番号: <strong>' . htmlspecialchars($phone) . '</strong></p>
                <p>住所: <strong>' . htmlspecialchars($address) . '</strong></p>
                <p>購入した商品: <strong>' . htmlspecialchars($item_name) . '</strong></p>
                <p>決済方法: <strong>クレジットカード</strong></p>
            </div>
        </div>
    </body>
    </html>';
    
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: no-reply@auction-site.com' . "\r\n";

    mail($to, $subject, $message, $headers);

    // リダイレクト
    header("Location: index.php?status=success&message=商品の購入が完了しました");
} else {
    // 入札額が現在の入札額より大きく、即決価格より小さい場合は入札を更新
    $sql = "UPDATE Item SET item_price = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ds", $bid_amount, $item_id);
    $stmt->execute();
    $stmt->close();

    // リダイレクト
    header("Location: itemdetails.php?item_id=$item_id&status=success&message=入札が成功しました");
}

$conn->close();
?>