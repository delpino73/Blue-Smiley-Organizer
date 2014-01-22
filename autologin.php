<?php

# this file is necessary to clear cookie with an in-between page

$code=isset($_GET['code']) ? $_GET['code'] : exit;

setcookie('sid','','631213200','/'); # delete cookie

$decoded=base64_decode($code);

list($username,$password)=explode('/',$decoded);

header('Location: auth.php?username='.$username.'&secure_pw='.$password);

?>