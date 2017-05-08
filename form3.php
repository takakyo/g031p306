<?php
$answer2= "";
foreach ($_POST['answer2'] as $key => $value) {
  $answer2 = $answer2."　".$value;
}


?>

<html>
  <head>
    <h2>興味のある研究分野は？</h2>
  </head>
  <body>
    <form action="result.php" method="POST">
      <input type="hidden" name="answer" value="<?php echo $_POST['answer'] ?>" />
      <input type="hidden" name="answer2" value="<?php echo $answer2 ?>"/>
      <input type="checkbox" name="answer3[]" value="教育"/>教育
      <input type="checkbox" name="answer3[]" value="観光"/>観光
      <input type="checkbox" name="answer3[]" value="農業"/>農業
      <input type="checkbox" name="answer3[]" value="医療"/>医療　
      <input type="submit" />
    </form>
  </body>
</html>
