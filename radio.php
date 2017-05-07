<?php
if (isset($_POST["answer"])) {
  if ($_POST['answer'] !== NULL && $_POST['answer'] === '超人ネイガー') {
    echo "正解です！, {$_POST['answer']}!";
  }else{
    echo "残念！　間違いです。もう一度!!　<br/>{$_POST['answer']}じゃないよ!";
  }
}
?>
<html>
  <head>
    <h2>問題　秋田県のご当地ヒーローは？</h2>
  </head>
  <body>
    <form action="radio.php" method="POST">
      <input type="radio" name="answer" value="超人ネイガー"/>超人ネイガー
      <input type="radio" name="answer" value="鉄神ガンライザー"/>鉄神ガンライザー
      <input type="radio" name="answer" value="琉神マブヤー"/>琉神マブヤー
      <input type="radio" name="answer" value="仮面ライダークウガ"/>仮面ライダークウガ　
      <input type="submit" />
    </form>
  </body>
</html>
