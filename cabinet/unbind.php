<?php
// Disable autopay / unbind card (the YooKassa-required action). Scope-checked by owner.
require __DIR__.'/lib.php';
cab_require_login();
$sid=$_POST['server_id']??'';
$owned=false;
foreach(cab_owner_servers() as $s){ if((string)$s['id']===(string)$sid){ $owned=true; break; } }
if(!$owned){ http_response_code(403); echo cab_page('Ошибка').'<div class="card">Действие недоступно.</div>'.cab_page_end(false); exit; }
cab_update('custom_cc_servers',$sid,['custom_payment_method_id'=>'']);
echo cab_page('Автосписание отключено');
echo '<div class="card"><h2 style="margin-top:0">Автосписание отключено</h2>'
   .'<p>Карта отвязана. Повторные автоматические списания производиться не будут.</p>'
   .'<p><a href="/cabinet/">← Назад в кабинет</a></p></div>';
echo cab_page_end(false);
