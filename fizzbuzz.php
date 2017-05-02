<?php
for ($i=1; $i<=50; $i++){
  if($i%15==0){
    print 'FizzBuzz ';
  }
  if($i%3==0){
    print 'Fizz ';
  }
  if($i%5==0){
    print 'Buzz ';
  }
  if($i%3!=0 && $i%5!=0 && $i%15!=0){
    print $i;
    print ' ';
  }
}
?>
