<?php
// Save contact email to the request tied to the login server.
require __DIR__.'/lib.php';
cab_require_login();
$email=trim($_POST['email']??'');
if(!cab_valid_email($email)){ header('Location: /cabinet/?err=email'); exit; }
$sid=cab_login_server_id();
foreach(cab_list('custom_cc_requests') as $r){
  if((string)($r['custom_server_id']??'')===$sid){
    cab_update('custom_cc_requests',$r['id'],['custom_contact_email'=>$email]);
    break;
  }
}
header('Location: /cabinet/');  // email read live from request on next load
