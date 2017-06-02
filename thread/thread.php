<?php
$db_user = 'root';
$db_pass = 'v7hrche8';
$db_name = 'bbs';

// データベースの接続
$mysqli = new mysqli('localhost', $db_user, $db_pass, $db_name);
$result = $mysqli->query("SELECT * FROM `messages`");

// スレッドID,スレッド名の取得
// SQLインジェクション
$thread_name = htmlspecialchars($_POST['thread_name']);
$thread_id = htmlspecialchars($_POST['thread_id']);

if(!empty($_POST['threads_id']) && !empty($_POST['threads_name'])){
  $thread_name = htmlspecialchars($_POST['threads_name']);
  $thread_name=$_POST['threads_name'];
  $thread_id = $_POST['threads_id'];
}
$result_message = '';

echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}</p>";

// データの登録
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['message']) ) {

    // SQLインジェクション対策
    $body = htmlspecialchars($_POST['message']);
    $name = htmlspecialchars($_SERVER['PHP_AUTH_USER']);
    $message = $mysqli->real_escape_string($body);

    $_SERVER['PHP_AUTH_USER'] = $mysqli->real_escape_string($_SERVER['PHP_AUTH_USER']);
    $mysqli->query("INSERT INTO `messages` (`body`,`name`,`thread_id`)
     VALUES ('{$body}','{$name}','{$_POST['threads_id']}')");
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
  if (!empty($_POST['delete_num']) && !empty($_POST['code'])) {
    $mysqli->query("SELECT * FROM `messages` WHERE `id` = ('{$_POST['delete_num']}') ") ;
    if($_SERVER['PHP_AUTH_PW'] == $_POST['code']){
      $mysqli->query("DELETE FROM `messages` WHERE `id` = {$_POST['delete_num']}");
      $result_message = 'メッセージを削除しました;)';
    }else{
      $result_message = 'パスワードが違います。';
    }
  }
  // データの更新
  if (!empty($_POST['update']) && !empty($_POST['code']) && !empty($_POST['update_body'])) {

// SQLインジェクション対策
    $update_body = htmlspecialchars($_POST['update_body']);
    $message = $mysqli->real_escape_string($update_body);
    $mysqli->query("SELECT * FROM `messages` WHERE `id` = ('{$_POST['update']}') ") ;
    if($_SERVER['PHP_AUTH_PW'] == $_POST['code']){
      $mysqli->query("UPDATE `messages` SET `body` = ('{$update_body}') WHERE `id` = ('{$_POST['update']}')");
      $result_message = 'メッセージを更新しました;)';
    }else{
      $result_message = 'パスワードが違います。';
    }
  }
// 並び替え
  $result = $mysqli->query('SELECT * FROM `messages` ORDER BY `id` DESC');
?>

<html>
<title>
  <?PHP
  $thread_name = htmlspecialchars($thread_name);
  echo $thread_name;
  ?>
</title>
  <head>
    <meta charset="UTF-8">
    <h1> <?PHP echo $thread_name; ?> </h1>
  </head>
  <body>
    <p> <?php echo $result_message; ?> </p>
    <!-- 内容,投稿者,パスワードの入力フォーム -->
    <form action="" method="post">
      題目　<input type="text" name="message" />　
      <input type="hidden" name="threads_id" value="<?php echo $thread_id; ?>" />
      <input type="hidden" name="threads_name" value="<?php echo $thread_name; ?>" />
      <input type="submit" />
    </form>
    <table border="2">
      <tr>
        <td>投稿番号</td><td>内容</td><td>投稿時間</td><td>投稿者名</td><td>スレッドid</td><td>削除</td><td>更新</td>
      </tr>
<!-- 掲示板の表示 -->
    <?php foreach ($result as $row) : ?>
      <?PHP if($row['thread_id'] == $thread_id ) : ?>
      <tr>
        <td>
          <?php echo $row['id']; ?>
        </td>
        <td>
          <!-- XSS対策 -->
          <?php
          $body = htmlspecialchars($row['body']);
          echo $body;
          ?>
        </td>
        <td> <?php echo $row['timestamp'] ?> </td>
        <td>
          <!-- XSS対策 -->
          <?php
          $name = htmlspecialchars($row['name']);
          echo $name;
          ?>
        </td>
        <td> <?php echo $row['thread_id'] ?> </td>
        <td>
          <!-- 削除フォーム -->
        <form action="" method="post">
        　<input type="hidden" name="delete_num" value="<?php echo $row['id']; ?>" />

        <input type="hidden" name="threads_id" value="<?php echo $thread_id; ?>" />
        <input type="hidden" name="threads_name" value="<?php echo $thread_name; ?>" />
        パスワード<input type="password" name="code" />
        <input type="submit" value="削除" />
      　</td>
    </form>
      <td>
        <!-- 内容更新フォーム -->
    <form action="" method="post">
      　<input type="hidden" name="update" value="<?php echo $row['id']; ?>" />

      <input type="hidden" name="threads_id" value="<?php echo $thread_id; ?>" />
      <input type="hidden" name="threads_name" value="<?php echo $thread_name; ?>" />
        更新内容  <input type="text" name="update_body"  />
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
