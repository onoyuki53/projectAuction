<?php
// PHPコード開始
header('Content-Type: text/html; charset=utf-8');

// ここにPHPのコードを書く
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>PHP Test</title>\n";
echo "</head>\n";
echo "<body>\n";
echo "<div class=\"product-list\">\n";
echo "  <div class=\"product-item\"><a href=\"itemdetails.php?item_id=f3jwk5gu\">\n";
echo "    <div class=\"product-image\"><img src=\"./images/f3jwk5gu_bag5.png\" alt=\"f3jwk5gu\"></div>\n";
echo "    <div class=\"product-info\"><h3>Ⓩ</h3><p>¥1</p><p>現在の入札額: ¥1</p><p style=\"color: red;\">即決価格: ¥11</p></div>\n";
echo "  </a></div>\n";
echo "</div>\n";
echo "<script>\n";
echo "document.addEventListener('DOMContentLoaded', function() {\n";
echo "    var links = document.querySelectorAll('.product-item a');\n";
echo "    links.forEach(function(link) {\n";
echo "        link.href = '新しいURLの場所';\n";
echo "        console.log('リンクのhref属性を変更しました:', link.href);\n";
echo "    });\n";
echo "});\n";
echo "</script>\n";
echo "</body>\n";
echo "</html>\n";
// PHPコード終了
?>
