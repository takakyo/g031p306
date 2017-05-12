<?php
$answer3= "";
foreach ($_POST['answer3'] as $key => $value) {
  $answer3 = $answer3."　".$value;
}

echo "好きなスポーツは?　　　　 回答-->";
echo $_POST['answer'];
echo "<br />";
echo "趣味は何ですか？　　　　　回答-->";
echo $_POST['answer2'];

echo "<br />";
echo "興味のある研究分野は？　　回答-->";
echo $answer3;
?>
