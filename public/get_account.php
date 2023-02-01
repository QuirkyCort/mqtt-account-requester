<?php

$SAFE_CHAR = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';

// Retrieve existing names
$filename = '/etc/mosquitto/pwfile';
$handle = fopen($filename, "r");
$usernames = [];

while (true) {
  $content = fgets($handle);
  if ($content === false) {
    break;
  }
  $usernames[] = explode(':', $content)[0];
}

// Generate random name
while (true) {
  $name = '';
  for ($x = 0; $x < 6; $x++) {
    $name .= $SAFE_CHAR[random_int(0, strlen($SAFE_CHAR)-1)];
  }
  if (in_array($name, $usernames) === false) {
    exec('mosquitto_passwd -b /etc/mosquitto/pwfile '.$name.' '.$name);
    exec('sudo /usr/bin/systemctl reload mosquitto');
    print(json_encode($name));
    break;
  }
}

fclose($handle);
