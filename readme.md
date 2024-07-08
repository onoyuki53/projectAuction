## index.php
商品項目の表示、メインページ
## login.php
会員ログインページ
## registst1.php
会員情報登録ステップ1ページ
## registst2.php
会員情報登録ステップ2ページ
## register_complete
会員情報登録完了ページ
## mypage.php
現時点で登録されている情報を表示するページ
## registchange.php
現時点で登録されている情報（ユーザー名以外）を変更できるページ
## itemdatails.php
アイテムの詳細情報を表示するページ?item001等で指定するとその情報を表示
## test.php
送っていただいたデザインにデータベースの情報を表示できるように変更  


```sql:User Table
CREATE TABLE `User`(
	`user_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
	`mail` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'メールアドレス',
	`password` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'パスワード',
	PRIMARY KEY (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```

```sql:UserAdd Table
CREATE TABLE `UserAdd` (
	`user_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
	`address` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '住所',
	`phone` VARCHAR(11) NOT NULL DEFAULT 0 COMMENT '電話番号',
	PRIMARY KEY (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb3;
```

```sql:User_Credit Table
CREATE TABLE `User_Credit` (
    `user_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
    `credit` INT(16) DEFAULT NULL COMMENT 'クレジット番号'
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```

```sql:Item Table
CREATE TABLE `Item` (
	`item_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
	`item_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'アイテム名',
  `item_price` INT(7) NOT NULL DEFAULT 0 COMMENT '金額',
  `buy_user` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '入札者',
`item_user` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '出品者ID',
  PRIMARY KEY (`item_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```

```sql:Item_Image Table
CREATE TABLE `Item_Image` (
    `item_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
    `image_path` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```