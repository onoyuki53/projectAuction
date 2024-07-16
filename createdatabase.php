<?php
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "auction";

try {
    // MySQLデータベースに接続
    $pdo = new PDO("mysql:host=$servername", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データベースを作成
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // データベースを選択
    $pdo->exec("USE $dbname");

    // Userテーブルを作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS User (
        user_id VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
        mail VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'メールアドレス',
        password VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'パスワード',
        PRIMARY KEY (user_id)
    ) ENGINE=INNODB DEFAULT CHARSET=utf8mb4");

    // UserAddテーブルを作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS UserAdd (
        user_id VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
        address VARCHAR(255) NOT NULL DEFAULT '' COMMENT '住所',
        phone VARCHAR(11) NOT NULL DEFAULT '0' COMMENT '電話番号',
        PRIMARY KEY (user_id)
    ) ENGINE=INNODB DEFAULT CHARSET=utf8mb3");

    // User_Creditテーブルを作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS User_Credit (
        user_id VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
        credit VARCHAR(16) DEFAULT NULL COMMENT 'クレジット番号'
    ) ENGINE=INNODB DEFAULT CHARSET=utf8mb4");

    // Itemテーブルを作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS Item (
        item_id VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
        item_name TEXT COMMENT 'アイテム名',
        item_price INT(7) NOT NULL DEFAULT 0 COMMENT '金額',
        max_price INT(7) NOT NULL DEFAULT 0 COMMENT '最高金額',
        buy_user VARCHAR(32) NOT NULL DEFAULT '' COMMENT '入札者',
        item_user VARCHAR(32) NOT NULL DEFAULT '' COMMENT '出品者ID',
        is_sold TINYINT(1) NOT NULL DEFAULT 0 COMMENT '売れたか売れてないかのフラグ',
        category VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'カテゴリー',
        PRIMARY KEY (item_id)
    ) ENGINE=INNODB DEFAULT CHARSET=utf8mb4");

    // Item_Imageテーブルを作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS Item_Image (
        item_id VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
        image_path VARCHAR(255) NOT NULL DEFAULT ''
    ) ENGINE=INNODB DEFAULT CHARSET=utf8mb4");

    echo "データベースとテーブルの作成が完了しました。";

    // index.phpにリダイレクト
    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
}

