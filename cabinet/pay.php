<?php
// Payment / renewal with EXPLICIT consent before saving the card (YooKassa autopay rule).
require __DIR__.'/lib.php';
cab_require_login();
$cfg=cab_cfg();
$sid=$_POST['server_id']??($_GET['server_id']??'');
$srv=null;
foreach(cab_owner_servers() as $s){ if((string)$s['id']===(string)$sid){ $srv=$s; break; } }
if(!$srv){ http_response_code(403); echo cab_page('Ошибка').'<div class="card">Действие недоступно.</div>'.cab_page_end(false); exit; }
$email=cab_owner_email();
if(!cab_valid_email($email)){ header('Location: /cabinet/?err=email'); exit; }

$total=cab_total($srv['custom_price_server']??0,$srv['custom_price_subscription']??0,$srv['custom_discount']??'');

// --- step 1: confirmation page with explicit autopay consent ---
if(($_POST['confirm']??'')!=='1'){
  echo cab_page('Оплата');
  echo '<div class="card">';
  echo '<h2 style="margin-top:0;font-size:18px">Оплата подписки</h2>';
  echo '<div style="margin:8px 0">Сервер: <b>'.cab_h($srv['custom_name']??'?').'</b></div>';
  echo '<div style="margin:8px 0">Сумма к оплате: <b>'.$total.' ₽</b></div>';
  $comment=trim($srv['custom_payment_comment']??''); if($comment!=='') echo '<div class="muted" style="margin:4px 0">💬 '.cab_h($comment).'</div>';
  echo '<form method=post action="pay.php" style="margin-top:14px">';
  echo '<input type=hidden name=server_id value="'.cab_h($srv['id']).'"><input type=hidden name=confirm value="1">';
  echo '<label style="display:flex;gap:8px;align-items:flex-start;margin:10px 0;font-size:14px">'
     .'<input type=checkbox name=autopay value="1" style="margin-top:3px">'
     .'<span>Подключить автосписание: сохранить карту для автоматического продления подписки. '
     .'Я согласен с <a href="/cabinet/autopay.php" target=_blank>условиями автоплатежа</a> и '
     .'<a href="/oferta.php" target=_blank>офертой</a>. Отключить можно в любой момент в кабинете.</span></label>';
  echo '<button class="btn" style="margin-top:6px">Перейти к оплате '.$total.' ₽</button>';
  echo '</form>';
  echo '<p class="muted" style="margin-top:10px">Без галочки — разовая оплата, карта не сохраняется.</p>';
  echo '</div>';
  echo cab_page_end(false);
  exit;
}

// --- step 2: create YooKassa payment; save card only if consented ---
$consent = (($_POST['autopay']??'')==='1');
$val=number_format($total,2,'.','');
$desc='aiclaw: '.($srv['custom_name']??'?');
$mk=function($save) use($cfg,$val,$desc,$srv,$email){
  $body=[
    'amount'=>['value'=>$val,'currency'=>'RUB'],
    'confirmation'=>['type'=>'redirect','return_url'=>$cfg['base_url'].'/cabinet/'],
    'capture'=>true,'description'=>$desc,
    'metadata'=>['server_id'=>$srv['id']],
    'receipt'=>['customer'=>['email'=>$email],'items'=>[[
      'description'=>$desc,'quantity'=>'1.00','amount'=>['value'=>$val,'currency'=>'RUB'],
      'vat_code'=>2,'payment_subject'=>'service','payment_mode'=>'full_payment']]],
  ];
  if($save) $body['save_payment_method']=true;
  $ch=curl_init('https://api.yookassa.ru/v3/payments');
  curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_TIMEOUT=>25,
    CURLOPT_USERPWD=>$cfg['yk_shop'].':'.$cfg['yk_key'],
    CURLOPT_HTTPHEADER=>['Content-Type: application/json','Idempotence-Key: cab-'.$srv['id'].'-'.bin2hex(random_bytes(6))],
    CURLOPT_POSTFIELDS=>json_encode($body)]);
  $r=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  return [$code, json_decode($r,true)];
};
[$code,$resp]=$mk($consent);
if($code>=400 && $consent){ // recurring not enabled on shop -> fall back to one-time
  $bs=json_encode($resp);
  if(stripos($bs,'recurring')!==false || stripos($bs,'forbidden')!==false){ [$code,$resp]=$mk(false); }
}
$url=$resp['confirmation']['confirmation_url']??'';
if($url){ header('Location: '.$url); exit; }
http_response_code(502);
echo cab_page('Ошибка').'<div class="card">Не удалось создать платёж. <a href="/cabinet/">Назад в кабинет</a></div>'.cab_page_end(false);
