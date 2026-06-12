<?php
// Set/change the cabinet password for the logged-in owner's email.
require __DIR__.'/lib.php';
cab_require_login();
$email=cab_owner_email();
if(!cab_valid_email($email)){ header('Location: /cabinet/?err=email'); exit; }
$pw=(string)($_POST['password']??'');
if(strlen($pw)<6){ header('Location: /cabinet/?err=pwshort'); exit; }
cab_pw_set($email,$pw);
header('Location: /cabinet/?ok=pw');
