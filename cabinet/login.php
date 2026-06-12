<?php
// Consume a one-time/reusable magic-link token and open a session.
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
echo cab_page('Вход');
echo '<div class="card"><h2 style="margin-top:0">Ссылка недействительна или истекла</h2>'
   .'<p>Откройте «Управление подпиской» в свежем сообщении от бота — там будет рабочая ссылка для входа.</p></div>';
echo cab_page_end(false);
