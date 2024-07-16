

<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
session_start();

$dsn = 'mysql:host=localhost;dbname=auction;charset=utf8';
$user = 'user1';
$password_db = 'passwordA1!';
$stid = $_SESSION['login_user'];

try {
    $pdo = new PDO($dsn, $user, $password_db);
    $sql = "SELECT * FROM Item WHERE item_user = $stid";
    //$sql = "SELECT * FROM teachers WHERE staff_id = $stid";
    //$stmt = $pdo->prepare($sql);
    //$stmt->bindParam(":stid", $stid); // $stidの値を外部から受け取る
    //$stmt->execute();
    $rec = $pdo -> query($sql) -> fetchAll();; // 全ての行を数値インデックスの配列として取得
} catch (PDOException $e) {
    exit(mb_convert_encoding($e->getMessage(), 'UTF-8', 'auto'));
}
?>
<table border="1">
    <tr>
        <th>Staff ID</th>
        <th>Staff Name</th>
        

        <th>Password</th>
    </tr>
    <?php foreach ($rec as $row): ?>
    <tr>
        <td><?php echo $row['item_id']; ?></td>
        <td><?php echo $row['item_name']; ?></td>
        <td><?php echo $row['item_price']; ?></td>
        <td><?php echo $row['max_price']; ?></td>
        <td><?php echo $row['buy_user']; ?></td>
        <td><?php echo $row['item_user']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $row['item_name']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
