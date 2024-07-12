<?php
// Include database connection and other necessary files
include 'db_connect.php'; // Make sure to adjust this to your actual database connection file

// Assuming $bid_price, $max_price, and other necessary variables are already set

if ($bid_price > $max_price) {
    // Set the item as sold
    $stmtUpdateSold = $pdo->prepare("UPDATE Product SET is_sold = 1 WHERE product_id = :product_id");
    $stmtUpdateSold->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmtUpdateSold->execute();

    // Get user details from the database
    $stmtUser = $pdo->prepare("SELECT u.mail, ua.address, ua.phone FROM User u JOIN UserAdd ua ON u.user_id = ua.user_id WHERE u.user_id = :user_id");
    $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmtUser->execute();
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $mail_address = $user['mail'];
        $address = $user['address'];
        $phone = $user['phone'];

        // Prepare the email message
        $to = $mail_address;
        $subject = '商品購入完了のお知らせ';
        $message = '<!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>商品の購入完了</title>
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
                    <p>クレジットカードで決済しました。</p>
                    <p>メールアドレス: <strong>' . htmlspecialchars($mail_address) . '</strong></p>
                    <p>電話番号: <strong>' . htmlspecialchars($phone) . '</strong></p>
                    <p>住所: <strong>' . htmlspecialchars($address) . '</strong></p>
                    <p>購入した商品: <strong>' . htmlspecialchars($product_name) . '</strong></p>
                    <img src="' . htmlspecialchars($product_image_url) . '" alt="商品画像" style="width: 100%; max-width: 300px;">
                </div>
            </div>
        </body>
        </html>';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: no-reply@auction-site.com' . "\r\n";

        // Send the email
        mail($to, $subject, $message, $headers);
        
        echo '商品の購入が完了しました。';
    } else {
        echo 'ユーザー情報が見つかりません。';
    }
} else {
    echo '入札価格が最大価格を超えていません。';
}
?>
