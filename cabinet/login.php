<?php
// Cabinet login: (1) magic-link ?t=token, (2) classic email+password form.
require __DIR__.'/lib.php';
cab_session_start();

// --- magic-link ---
$t=$_GET['t']??'';
if($t!==''){
  $row=cab_token_consume($t);
  if($row){
    $_SESSION['owner']=['server_id'=>$row['server_id']];  // email resolved live
    $_SESSION['ts']=time();
    header('Location: /cabinet/'); exit;
  }
}

// --- email + password ---
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=strtolower(trim($_POST['email']??''));
  $pw=(string)($_POST['password']??'');
  if(cab_valid_email($email) && cab_pw_check($email,$pw)){
    $_SESSION['owner']=['email'=>$email];
    $_SESSION['ts']=time();
    header('Location: /cabinet/'); exit;
  }
  usleep(400000); // throttle brute force
  $err='Неверный email или пароль.';
}

http_response_code($err?401:200);
echo cab_page('Вход');
echo '<div class="card">';
echo '<h2 style="margin-top:0;font-size:18px">Вход в личный кабинет</h2>';
if($err) echo '<div style="background:#fde8e8;color:#b91c1c;padding:8px 10px;border-radius:8px;margin-bottom:10px">'.cab_h($err).'</div>';
echo '<form method=post>'
   .'<div style="margin:8px 0"><input name=email type=email required placeholder="Email" style="padding:10px;border:1px solid #ccc;border-radius:8px;width:100%;box-sizing:border-box"></div>'
   .'<div style="margin:8px 0"><input name=password type=password required placeholder="Пароль" style="padding:10px;border:1px solid #ccc;border-radius:8px;width:100%;box-sizing:border-box"></div>'
   .'<button class="btn" style="width:100%;margin-top:6px">Войти</button>'
   .'</form>';
echo '</div>';
echo '<p class="muted">Нет пароля? Откройте «🔐 Управление подпиской» в сообщении от бота — по ссылке вы войдёте без пароля, а затем сможете задать пароль в кабинете.</p>';
echo cab_page_end(false);
