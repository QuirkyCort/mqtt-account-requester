<?php

$SAFE_CHAR = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';

$name = '';
for ($x = 0; $x < 8; $x++) {
  $name .= $SAFE_CHAR[random_int(0, strlen($SAFE_CHAR)-1)];
}
file_put_contents('../protected/accounts', $name."\n", FILE_APPEND);
print(json_encode($name));
