<?php
// Disable autopay / unbind card (the YooKassa-required action). Scope-checked by owner.
require __DIR__.'/lib.php';
cab_require_login();
$sid=$_POST['server_id']??'';
$owned=false;
foreach(cab_owner_servers() as $s){ if((string)$s['id']===(string)$sid){ $owned=true; break; } }
if(!$owned){ http_response_code(403); echo 'forbidden'; exit; }
cab_update('custom_cc_servers',$sid,['custom_payment_method_id'=>'']);
?><!doctype html><html lang=ru><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1">
<title>Автосписание отключено</title>
<body style="font-family:system-ui,sans-serif;max-width:560px;margin:0 auto;padding:48px 24px">
<h2>Автосписание отключено</h2>
<p>Карта отвязана. Повторные автоматические списания производиться не будут.</p>
<p><a href="/cabinet/">← Назад в кабинет</a></p>
</body></html>
