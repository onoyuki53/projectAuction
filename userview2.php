<?php
$servername = "localhost";
$username = "user1";
$password = "passwordA1!";
$dbname = "auction";

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ユーザー入力を受け取る
    $user_input = $conn->real_escape_string($_POST['user_input']);

    // ユーザー入力がSQL文かユーザーIDかを判定
    if (stripos($user_input, "SELECT") === 0 || stripos($user_input, "select") === 0 || stripos($user_input, "UNION") === 0 || stripos($user_input, "union") === 0) {
        // ユーザー入力がSQL文の場合
        $sql = $user_input;
    } else {
        // ユーザー入力がユーザーIDの場合
        $sql = "SELECT Item.item_id AS id, Item.item_name AS name, Item.item_price AS price, Item.category AS category 
                FROM User 
                JOIN Item ON User.user_id = Item.item_user 
                WHERE User.user_id = '$user_input'";
    }

    // クエリを実行
    if ($result = $conn->query($sql)) {
        // 結果の表示
        if ($result->num_rows > 0) {
            echo "<table border='1'><tr>";
            // フィールド名を表示
            while ($fieldinfo = $result->fetch_field()) {
                echo "<th>{$fieldinfo->name}</th>";
            }
            echo "</tr>";

            // データを表示
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    if ($key == 'item_id') {
                        echo '<td><a href="itemdetails.php?item_id=' . $row['item_id'] . '">' . $row['item_id'] . '</a></td>';
                    } else {
                        echo "<td>{$value}</td>";
                    }
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No results found.";
        }
    } else {
        // エラーメッセージを表示
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SQL Query Executor</title>
</head>
<body>
    <form method="post">
        <label for="user_input">ユーザーIDまたはSQL文を入力してください:</label><br>
        <textarea id="user_input" name="user_input" rows="4" cols="50"></textarea><br>
        <input type="submit" value="実行">
    </form>
</body>
</html>