<?php
require __DIR__.'/lib.php';
cab_require_login();
$servers=cab_owner_servers();
$email=$_SESSION['owner']['email']??'';
$need_email=($email==='');
echo cab_page('Личный кабинет');
if($need_email){
  echo '<div class="card"><p style="margin:0 0 8px">Укажите email — он нужен для чеков и управления подпиской:</p>'
     .'<form method=post action="set-email.php"><input name=email type=email required placeholder="you@mail.ru" '
     .'style="padding:9px;border:1px solid #ccc;border-radius:8px;width:60%"> <button class="btn">Сохранить</button></form></div>';
}
if(!$servers){ echo '<p class="muted">Инстансы не найдены для вашего аккаунта.</p>'; }
foreach($servers as $s){
  $exp=$s['custom_expires_at']??'';
  $days = $exp ? (int)floor((strtotime($exp)-time())/86400) : null;
  $total=cab_total($s['custom_price_server']??0,$s['custom_price_subscription']??0,$s['custom_discount']??'');
  $hasCard=!empty($s['custom_payment_method_id']);
  echo '<div class="card">';
  echo '<div style="font-weight:600;font-size:16px">'.cab_h($s['custom_name']??'?').' <span class="muted" style="font-weight:400">'.cab_h($s['custom_host']??'').'</span></div>';
  echo '<div style="margin:8px 0;color:#333">Действует до: <b>'.cab_h($exp?substr($exp,0,10):'—').'</b>'.($days!==null?' <span class="muted">('.$days.' дн.)</span>':'').'</div>';
  echo '<div style="margin:8px 0;color:#333">Сумма продления: <b>'.$total.' ₽</b></div>';
  echo '<form method=post action="pay.php" style="margin:6px 0"><input type=hidden name=server_id value="'.cab_h($s['id']).'"><button class="btn">Оплатить / продлить</button></form>';

  // --- payment method / autopay block (ALWAYS shown) ---
  echo '<div style="border-top:1px solid #eef0f3;margin-top:12px;padding-top:12px">';
  echo '<div style="font-weight:600;margin-bottom:6px">Способ оплаты и автосписание</div>';
  if($hasCard){
    echo '<div style="margin:4px 0">💳 Карта привязана · автосписание <b style="color:#16a34a">включено</b></div>';
    echo '<div class="muted" style="margin:2px 0 10px">Подписка продлевается автоматически с привязанной карты.</div>';
    echo '<form method=post action="unbind.php" onsubmit="return confirm(\'Отвязать карту и отключить автосписание?\')">'
       .'<input type=hidden name=server_id value="'.cab_h($s['id']).'">'
       .'<button class="btn sec">Отвязать карту · отключить автосписание</button></form>';
  } else {
    echo '<div style="margin:4px 0">💳 Карта не привязана · автосписание <b style="color:#8a93a0">выключено</b></div>';
    echo '<div class="muted" style="margin:2px 0 10px">Карта появится здесь после оплаты с включённым автопродлением. Тогда тут будет кнопка отвязки.</div>';
    echo '<button class="btn off" disabled title="Нет привязанной карты">Отвязать карту · отключить автосписание</button>';
  }
  echo '</div>';
  echo '</div>';
}
echo cab_page_end();
