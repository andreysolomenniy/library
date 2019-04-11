<?php
function process_template($tmpl, $data) {
  if (! is_array($data)) {
      echo $tmpl;
  } else {
    $off = 0;
    $len = strlen($tmpl);
    while (true) {
      $level = 0;
      $pos_open = strpos($tmpl, '<r', $off);
      if ($pos_open === false)
        break;
      if (substr($tmpl, $pos_open, 3) == '<r1') $level = 1;
      else {
        if (substr($tmpl, $pos_open, 3)=='<r2') $level = 2;
        else
          if (substr($tmpl, $pos_open, 3)=='<rs') $level = 3;
          else break;
      }
      if ($level == 0)
        break;
 
      echo substr($tmpl, $off, $pos_open - $off);
      $off = $pos_open;
      $pos_close = strpos($tmpl, '>', $pos_open+4);
      if ($pos_close === false) {
         $off = $pos_open+3;
        continue;
      }
      if ($pos_close - $pos_open < 5) {
        $off = $pos_close+1;
        continue;
      }
      $var = substr($tmpl, $pos_open+4, $pos_close-$pos_open-4);
      if (($level == 1 || $level == 2)) {
        if ($level == 1)
          $pos_slash = strpos($tmpl, '</r1>', $pos_close+1);
        else
        if ($level == 2)
          $pos_slash = strpos($tmpl, '</r2>', $pos_close+1);
        else $pos_slash = $pos_open;
        if ($pos_slash === false) {
          $off = $pos_close+1;
          continue;
        }
        if ($pos_slash - $pos_close <= 1) {
          $off = $pos_close+1;
          continue;
        }
        $off = $pos_slash + 5;
        if (isset($data[$var])) {
          $d = $data[$var];
          if (is_array($d)) {
            foreach ($d as $val) {
              process_template(substr($tmpl, $pos_close+1, $pos_slash-$pos_close-1), $val);
            }
          }
        }
      }
      if ($level == 3 && isset($data[$var])) {
        $d = $data[$var];
        if (is_string($d)) {
          echo $data[$var];
          $off = $pos_close+1;
        }
      }
    }
    echo substr($tmpl, $off, $len - $off);
  }
}
?>
