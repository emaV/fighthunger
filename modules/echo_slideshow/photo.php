<?php

$photoID = substr($_SERVER['PATH_INFO'], 0, -4);

if (preg_match("/^tn/", $photoID))
    $src = '/var/tmp/wtw_photos/tn/' . substr($photoID, 3);
else
    $src = '/var/tmp/wtw_photos/' . $photoID;

header("Content-type: image/jpeg");

print(file_get_contents($src));

?>