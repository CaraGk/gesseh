<?php

class gesseh
{
  public static function showColoredScore($score, $total)
  {
    $colors = array('white', 'red', 'orange', 'yellow', 'green');
    
    $ratio = round($score / $total * (count($colors) - 1));
    return $colors[$ratio];
  }

  public static function getProperDate($date)
  {
    $i = explode('/', $date);
    return '19'.$i[2].'-'.$i[1].'-'.$i[0];
  }


}

?>
