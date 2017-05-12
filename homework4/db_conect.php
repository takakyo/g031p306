<?php
$db_user = 'root';     // ユーザー名
$db_pass = 'takakyo3'; // パスワード
$db_name = 'bbs';     // データベース名
// MySQLに接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `messages`');
$result_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message'])) {
    $mysqli->query("insert into `messages` (`body`,`name`) values ('{$_POST['message']}','{$_POST['message2']}')");
    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = 'メッセージを入力してください...XO';
  }
}
$result = $mysqli->query('select * from `messages` order by `id` desc');
?>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <p> <?php echo $result_message; ?> </p>
    <form action="" method="post">
      題目　<input type="text" name="message" />　
      登録者名<input type="text" name="message2" />
      <input type="submit" />
    </form>
    <?php foreach ($result as $row) : ?>
      <p> <?php echo $row['id']." ".$row['body']." ".$row['timestamp']." ".$row['name'];?>  </p>
    <?php endforeach ; ?>
  </body>
</html>
