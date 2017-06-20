<?php
$db_user = 'root';
$db_pass = 'v7hrche8';
$db_name = 'bbs';
// データベースの接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query('SELECT * FROM `threads`');
$result_message = '';

// ユーザ認証
if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) ) {
  // ログイン時にキャンセルを押した場合
    echo '<a href="thread_list.php">再ログイン</a>';
    header('WWW-Authenticate: Basic realm="Private Page"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
} else {
  foreach($result as $row){
    if($row['user'] == $_SERVER['PHP_AUTH_USER'] && $row['password'] == $_SERVER['PHP_AUTH_PW']){
      // ログイン成功
    } else if($row['user'] == $_SERVER['PHP_AUTH_USER'] && $row['password'] !== $_SERVER['PHP_AUTH_PW']){
      echo 'すでに同じユーザ名が使われています。<a href="thread_list.php">再ログイン</a>';
      header('HTTP/1.0 401 Unauthorized');
      exit;
    }
  }
}


// データの登録
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['thread'])  && !empty($_POST['user_name'])) {
    // SQLインジェクション対策
    $thread = htmlspecialchars($_POST['thread']);
    $user_name = htmlspecialchars($_POST['user_name']);
    $user_password = htmlspecialchars($_POST['user_password']);
    $message = $mysqli->real_escape_string($thread,$user_name,$user_password);
    $mysqli->query("INSERT INTO `threads` (`name`,`user`,password)
     VALUES ('{$thread}','{$user_name}','{$user_password}')");
    $result_message = 'スレッドを登録しました！';
  } else {
    $result_message = '全ての項目に入力してください';
  }
// データの削除
  if (!empty($_POST['delete_num']) && !empty($_POST['users'])) {
    $mysqli->query("SELECT `user` FROM `threads` WHERE `id` = ('{$_POST['delete_num']}') ") ;
    if($_POST['users'] == $_SERVER['PHP_AUTH_USER'] ){
      $mysqli->query("DELETE FROM `threads` WHERE `id` = {$_POST['delete_num']}");
      $result_message = 'メッセージを削除しました;)';
    }else{
      $result_message = '権限がありません';
    }
  }
  // データの更新
  if (!empty($_POST['update']) && !empty($_POST['update_name'])) {
    // SQLインジェクション対策
    $update_name = htmlspecialchars($_POST['update_name']);
    $message = $mysqli->real_escape_string($update_name);
    $mysqli->query("SELECT `password` FROM `threads` WHERE `id` = ('{$_POST['update']}') ") ;
    if($_POST['users'] == $_SERVER['PHP_AUTH_USER'] ){
      $mysqli->query("UPDATE `threads` SET `name` = ('{$update_name}') WHERE `id` = ('{$_POST['update']}')");
      $result_message = 'スレッド名を更新しました;)';
    }else{
      $result_message = '権限がありません';
    }
  }
}
// 並び替え
  $result = $mysqli->query('SELECT * FROM `threads` ORDER BY `id` DESC');
?>
<html>
<title>スレッド一覧</title>
  <head>
    <p>Hello  <?php echo $_SERVER['PHP_AUTH_USER']; ?></p>
    <meta charset="UTF-8">
  </head>
  <body>
    <p> <?php echo $result_message; ?> </p>
    <!-- 内容,パスワードの入力フォーム -->
    <form action="" method="post">
      題目　<input type="text" name="thread" />　
      <input type="hidden" name="user_name" value="<?PHP echo $_SERVER['PHP_AUTH_USER'] ; ?>">
      <input type="hidden" name="user_password" value="<?PHP echo $_SERVER['PHP_AUTH_PW'] ; ?>">
      <input type="submit" />
    </form>
    <table border="2">
      <tr>
        <td>投稿番号</td><td>内容</td><td>投稿時間</td><td>削除</td><td>更新</td>
      </tr>
<!-- 掲示板の表示 -->
    <?php foreach ($result as $row) : ?>
      <tr>
        <td>
          <?php echo $row['id'] ?>
        </td>
        <form action="./thread.php" method="post">
        <td>
          <!-- XSS対策 -->
          <?php $name = htmlspecialchars($row['name']); ?>
          <input type="submit" name="thread_name" value="<?php echo $name; ?>" />
        </td>
        <input type="hidden" name="thread_id" value="<?php echo $row['id']; ?>" />
        </form>
        <td>
          <?php echo $row['timestamp'] ?>
        </td>
        <!-- 削除フォーム -->
        <td>
        <form action="" method="post">
        　<input type="hidden" name="delete_num" value="<?php echo $row['id']; ?>" />
        　<input type="hidden" name="users" value="<?php echo $row['user']; ?>" />
        <input type="submit" value="削除" />
      　</td>
    </form>
      <td>
        <!-- 内容更新フォーム -->
    <form action="" method="post">
      　<input type="hidden" name="update" value="<?php echo $row['id']; ?>" />
      　<input type="hidden" name="users" value="<?php echo $row['user']; ?>" />
        更新内容  <input type="text" name="update_name"  />
        <br>
      <input type="submit" value="更新" />
    　</td>
    </form>
      </tr>
    <?php endforeach ; ?>
   </table>
  </body>
</html>
