<?php
class Caller
{
   private $x = array(1, 2, 3);

   public function __call($m, $a)
   {
       print "Method $m called:\n";
       var_dump($a);
       return $this->x;
   }

   public function test($i) {
		echo "Am intrat aici: $i";
   }

   public function test($i,$j) {
		echo "Metoda 2: $i $j";
   }
}

$foo = new Caller();
$a = $foo->test(1, "2", 3.4, true);
var_dump($a);
?> 