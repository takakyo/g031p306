<?php
// var_dump($_POST);
$answer= "";
foreach ($_POST['answer'] as $key => $value) {
  $answer = $answer."　".$value;
}

?>

<html>
  <head>
    <h2>趣味は何ですか？</h2>
  </head>
  <body>
    <form action="form3.php" method="POST">
      <input type="hidden" name="answer" value="<?php echo $answer ?>"/>
      <input type="checkbox" name="answer2[]" value="スポーツ"/>スポーツ
      <input type="checkbox" name="answer2[]" value="読書"/>読書
      <input type="checkbox" name="answer2[]" value="料理"/>料理
      <input type="checkbox" name="answer2[]" value="ゲーム"/>ゲーム　
      <input type="submit" />
    </form>
  </body>
</html>
