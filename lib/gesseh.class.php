<?php

class gesseh
{
  public function showColoredScore($score, $total)
  {
    $colors = array('white', 'red', 'orange', 'yellow', 'green');
    
    $ratio = round($score / $total * (count($colors) - 1));
    return $colors[$ratio];
  }

}

?>
