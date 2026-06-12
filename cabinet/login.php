<?php
// Consume a one-time magic-link token and open a session.
require __DIR__.'/lib.php';
cab_session_start();
$t=$_GET['t']??'';
if($t!==''){
  $row=cab_token_consume($t);
  if($row){
    $_SESSION['owner']=['email'=>$row['email']?:'','server_id'=>$row['server_id']];
    $_SESSION['ts']=time();
    header('Location: /cabinet/'); exit;
  }
}
http_response_code(401);
?><!doctype html><html lang=ru><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1">
<title>aiclaw — вход</title>
<body style="font-family:system-ui,sans-serif;max-width:560px;margin:0 auto;padding:48px 24px">
<h2>Ссылка недействительна или истекла</h2>
<p>Откройте «Личный кабинет» в вашем боте ещё раз — он пришлёт свежую ссылку для входа.</p>
</body></html>
