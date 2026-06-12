<?php
// Create a YooKassa payment for renewal; recurring-fallback if shop has no recurring.
require __DIR__.'/lib.php';
cab_require_login();
$cfg=cab_cfg();
$sid=$_POST['server_id']??'';
$srv=null;
foreach(cab_owner_servers() as $s){ if((string)$s['id']===(string)$sid){ $srv=$s; break; } }
if(!$srv){ http_response_code(403); echo 'forbidden'; exit; }
$email=cab_owner_email();
if(!cab_valid_email($email)){ header('Location: /cabinet/?err=email'); exit; }
$total=cab_total($srv['custom_price_server']??0,$srv['custom_price_subscription']??0,$srv['custom_discount']??'');
$val=number_format($total,2,'.','');
$desc='aiclaw: '.($srv['custom_name']??'?');

$mk=function($save) use($cfg,$val,$desc,$srv,$email){
  $body=[
    'amount'=>['value'=>$val,'currency'=>'RUB'],
    'confirmation'=>['type'=>'redirect','return_url'=>$cfg['base_url'].'/cabinet/'],
    'capture'=>true,
    'description'=>$desc,
    'metadata'=>['server_id'=>$srv['id']],
    'receipt'=>['customer'=>['email'=>$email],'items'=>[[
      'description'=>$desc,'quantity'=>'1.00',
      'amount'=>['value'=>$val,'currency'=>'RUB'],
      'vat_code'=>2,'payment_subject'=>'service','payment_mode'=>'full_payment',
    ]]],
  ];
  if($save) $body['save_payment_method']=true;
  $ch=curl_init('https://api.yookassa.ru/v3/payments');
  curl_setopt_array($ch,[
    CURLOPT_RETURNTRANSFER=>true, CURLOPT_POST=>true, CURLOPT_TIMEOUT=>25,
    CURLOPT_USERPWD=>$cfg['yk_shop'].':'.$cfg['yk_key'],
    CURLOPT_HTTPHEADER=>['Content-Type: application/json','Idempotence-Key: cab-'.$srv['id'].'-'.bin2hex(random_bytes(6))],
    CURLOPT_POSTFIELDS=>json_encode($body),
  ]);
  $r=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  return [$code, json_decode($r,true)];
};

[$code,$resp]=$mk(true);
if($code>=400){
  $bs=json_encode($resp);
  if(stripos($bs,'recurring')!==false || stripos($bs,'forbidden')!==false){ [$code,$resp]=$mk(false); }
}
$url=$resp['confirmation']['confirmation_url']??'';
if($url){ header('Location: '.$url); exit; }
http_response_code(502);
echo 'Не удалось создать платёж. <a href="/cabinet/">Назад в кабинет</a>';
