<?php
$db_user = 'root';
$db_pass = 'v7hrche8';
$db_name = 'bbs';
// データベースの接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `messages`');
$result_message = '';
// データの登録
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message']) && !empty($_POST['contributor']) && !empty($_POST['passwords'])) {
    $message = htmlspecialchars($_POST['message'],$_POST['contributor'],$_POST['passwords']);
    $mysqli->query("insert into `messages` (`body`,`name`,`password`)
     values ('{$_POST['message']}','{$_POST['contributor']}','{$_POST['passwords']}')");

    $result_message = 'データベースに登録しました！XD';
  } else {
    $result_message = 'すべての項目に記入してください...XO';
  }
}

// データの削除
  if (!empty($_POST['del']) && !empty($_POST['code'])) {
    $mysqli->query("select `password` from `messages` where `id` = ('{$_POST['del']}') ") ;
    if($_POST['passwords'] == $_POST['code']){
      $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
      $result_message = 'メッセージを削除しました;)';
    }else{
      $result_message = 'パスワードが違います。';
      echo $_POST['code'];
    }
  }
  // データの更新
  if (!empty($_POST['upd']) && !empty($_POST['code'])) {
    $mysqli->query("select `password` from `messages` where `id` = ('{$_POST['upd']}') ") ;
    if($_POST['passwords'] == $_POST['code']){
      $mysqli->query("update `messages` set `body` = ('{$_POST['upd_body']}') where `id` = ('{$_POST['upd']}')");
      echo $_POST['upd_body'].$_POST['upd'];
      $result_message = 'メッセージを更新しました;)';
    }else{
      echo $_POST['code'];
      $result_message = 'パスワードが違います。';
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
      投稿者名　<input type="text" name="contributor" />
      パスワード<input type="password" name="passwords">
      <input type="submit" />
    </form>
    <table border="2">
      <tr>
        <td>投稿番号</td><td>内容</td><td>投稿時間</td><td>投稿者名</td><td>削除</td><td>更新</td>
      </tr>
<!-- 掲示板の表示 -->
    <?php foreach ($result as $row) : ?>
      <tr>
        <td> <?php echo $row['id'] ?> </td>
        <td> <?PHP echo $row['body'] ?> </td>
        <td> <?php echo $row['timestamp'] ?> </td>
        <td> <?php echo $row['name'] ?> </td>
        <td>
          <!-- 削除フォーム -->
        <form action="" method="post">
        　<input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
        　<input type="hidden" name="passwords" value="<?php echo $row['password']; ?>" />
        パスワード<input type="password" name="code" />
        <input type="submit" value="削除" />
      　</td>
    </form>
      <td>
        <!-- 内容更新フォーム -->
    <form action="" method="post">
      　<input type="hidden" name="upd" value="<?php echo $row['id']; ?>" />
      　<input type="hidden" name="passwords" value="<?php echo $row['password']; ?>" />
        更新内容  <input type="text" name="upd_body"  />
        <br>
      　パスワード<input type="password" name="code" />
      <input type="submit" value="更新" />
    　</td>
    </form>
      </tr>
    <?php endforeach ; ?>
   </table>
  </body>
</html>
