<?php
if (isset($_POST["answer"])) {
  if ($_POST['answer'] !== NULL && $_POST['answer'] === 'チャーハン' ||
      $_POST['answer'] === '炒飯' || $_POST['answer'] === 'ちゃーはん' ||
      $_POST['answer'] === 'tyahan') {
    echo "正解です！, {$_POST['answer']}!";
  }else{
    echo "残念！　間違いです。もう一度!!　{$_POST['answer']}じゃないよ!";
  }
}
?>
<html>
  <head>
    <h2>問題　僕の好きな食べ物は？</h2>
  </head>

  <body>
    <form action="quiz.php" method="POST">
      <input type="text"     name="answer" />
      <input type="submit" />
    </form>
  </body>
</html>
