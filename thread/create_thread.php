<?php
$db_user = 'root';
$db_pass = 'v7hrche8';
$db_name = 'bbs';
// データベースの接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('select * from `threads`');
$result_message = '';
// データの登録
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['thread']) && !empty($_POST['passwords'])) {
    $message = htmlspecialchars($_POST['thread'],$_POST['passwords']);
    $message = $mysqli->real_escape_string($_POST['thread'],$_POST['passwords']);
    $mysqli->query("insert into `threads` (`name`,`password`)
     values ('{$_POST['thread']}','{$_POST['passwords']}')");
    $result_message = 'スレッドを登録しました！';
  } else {
    $result_message = '全ての項目に入力してください';
  }
// データの削除
  if (!empty($_POST['del']) && !empty($_POST['code'])) {
    $mysqli->query("select `password` from `threads` where `id` = ('{$_POST['del']}') ") ;
    if($_POST['passwords'] == $_POST['code']){
      $mysqli->query("delete from `threads` where `id` = {$_POST['del']}");
      $result_message = 'メッセージを削除しました;)';
    }else{
      $result_message = 'パスワードが違います。';
    }
  }
  // データの更新
  if (!empty($_POST['upd']) && !empty($_POST['code']) && !empty($_POST['upd_name'])) {
    $mysqli->query("select `password` from `threads` where `id` = ('{$_POST['upd']}') ") ;
    if($_POST['passwords'] == $_POST['code']){
      $mysqli->query("update `threads` set `name` = ('{$_POST['upd_name']}') where `id` = ('{$_POST['upd']}')");
      $result_message = 'スレッド名を更新しました;)';
    }else{
      $result_message = 'パスワードが違います。';
    }
  }
}
// 並び替え
  $result = $mysqli->query('select * from `threads` order by `id` desc');
?>

<html>
<title>スレッド一覧</title>
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <p> <?php echo $result_message; ?> </p>
    <!-- 内容,パスワードの入力フォーム -->
    <form action="" method="post">
      題目　<input type="text" name="thread" />　
      パスワード<input type="password" name="passwords">
      <input type="submit" />
    </form>
    <table border="2">
      <tr>
        <td>投稿番号</td><td>内容</td><td>投稿時間</td><td>削除</td><td>更新</td>
      </tr>
<!-- 掲示板の表示 -->
    <?php foreach ($result as $row) : ?>
      <tr>
        <td> <?php echo $row['id'] ?> </td>
        <form action="./thread.php" method="post">
        <td>  <input type="submit" name="th_name" value="<?php echo $row['name']; ?>" /></td>
              <input type="hidden" name="th_id" value="<?php echo $row['id']; ?>" />
        </form>
        <td> <?php echo $row['timestamp'] ?> </td>
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
        更新内容  <input type="text" name="upd_name"  />
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
