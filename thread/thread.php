<?php
$db_user = 'root';
$db_pass = 'v7hrche8';
$db_name = 'bbs';
// データベースの接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query("select * from `messages`");
// スレッドID,スレッド名の取得
$th_name=$_POST['th_name'];
$th_id=$_POST['th_id'];
if(!empty($_POST['threads_id']) && !empty($_POST['threads_name'])){
  $th_name=$_POST['threads_name'];
  $th_id = $_POST['threads_id'];
}
$result_message = '';
// データの登録
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message']) && !empty($_POST['contributor']) && !empty($_POST['passwords'])) {
    $message = htmlspecialchars($_POST['message'],$_POST['contributor'],$_POST['passwords']);
    $message = $mysqli->real_escape_string($_POST['message'],$_POST['contributor'],$_POST['passwords']);
    $mysqli->query("insert into `messages` (`body`,`name`,`password`,`thread_id`)
     values ('{$_POST['message']}','{$_POST['contributor']}','{$_POST['passwords']}','{$_POST['threads_id']}')");
    $result_message = 'コメントを登録しました！XD';
  } else {
    $result_message = 'すべての項目に記入してください...XO';
  }
}
// 初回ページ遷移時のコメント
if(empty($_POST['threads_id'])){
  $result_message = 'ようこそ！　コメントを入力してください';
}
// データの削除
  if (!empty($_POST['del']) && !empty($_POST['code'])) {
    $mysqli->query("select `password` from `messages` where `id` = ('{$_POST['del']}') ") ;
    if($_POST['passwords'] == $_POST['code']){
      $mysqli->query("delete from `messages` where `id` = {$_POST['del']}");
      $result_message = 'メッセージを削除しました;)';
    }else{
      $result_message = 'パスワードが違います。';
    }
  }
  // データの更新
  if (!empty($_POST['upd']) && !empty($_POST['code']) && !empty($_POST['upd_body'])) {
    $mysqli->query("select `password` from `messages` where `id` = ('{$_POST['upd']}') ") ;
    if($_POST['passwords'] == $_POST['code']){
      $mysqli->query("update `messages` set `body` = ('{$_POST['upd_body']}') where `id` = ('{$_POST['upd']}')");
      $result_message = 'メッセージを更新しました;)';
    }else{
      $result_message = 'パスワードが違います。';
    }
  }
// 並び替え
  $result = $mysqli->query('select * from `messages` order by `id` desc');
?>

<html>
<title> <?PHP echo $th_name; ?> </title>
  <head>
    <meta charset="UTF-8">
    <h1> <?PHP echo $th_name; ?> </h1>
  </head>
  <body>
    <p> <?php echo $result_message; ?> </p>
    <!-- 内容,投稿者,パスワードの入力フォーム -->
    <form action="" method="post">
      題目　<input type="text" name="message" />　
      投稿者名　<input type="text" name="contributor" />
      パスワード<input type="password" name="passwords">
      <input type="hidden" name="threads_id" value="<?php echo $th_id; ?>" />
      <input type="hidden" name="threads_name" value="<?php echo $th_name; ?>" />
      <input type="submit" />
    </form>
    <table border="2">
      <tr>
        <td>投稿番号</td><td>内容</td><td>投稿時間</td><td>投稿者名</td><td>スレッドid</td><td>削除</td><td>更新</td>
      </tr>
<!-- 掲示板の表示 -->
    <?php foreach ($result as $row) : ?>
      <?PHP if($row['thread_id'] == $th_id ) : ?>
      <tr>
        <td> <?php echo $row['id'] ?> </td>
        <td> <?PHP echo $row['body'] ?> </td>
        <td> <?php echo $row['timestamp'] ?> </td>
        <td> <?php echo $row['name'] ?> </td>
        <td> <?php echo $row['thread_id'] ?> </td>
        <td>
          <!-- 削除フォーム -->
        <form action="" method="post">
        　<input type="hidden" name="del" value="<?php echo $row['id']; ?>" />
        　<input type="hidden" name="passwords" value="<?php echo $row['password']; ?>" />
        <input type="hidden" name="threads_id" value="<?php echo $th_id; ?>" />
        <input type="hidden" name="threads_name" value="<?php echo $th_name; ?>" />
        パスワード<input type="password" name="code" />
        <input type="submit" value="削除" />
      　</td>
    </form>
      <td>
        <!-- 内容更新フォーム -->
    <form action="" method="post">
      　<input type="hidden" name="upd" value="<?php echo $row['id']; ?>" />
      　<input type="hidden" name="passwords" value="<?php echo $row['password']; ?>" />
      <input type="hidden" name="threads_id" value="<?php echo $th_id; ?>" />
      <input type="hidden" name="threads_name" value="<?php echo $th_name; ?>" />
        更新内容  <input type="text" name="upd_body"  />
        <br>
      　パスワード<input type="password" name="code" />
      <input type="submit" value="更新" />
    　</td>
    </form>
      </tr>
    <?php endif ; ?>
    <?php endforeach ; ?>
   </table>
  </body>
</html>
