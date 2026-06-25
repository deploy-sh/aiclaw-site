<?php
require __DIR__.'/lib.php';
cab_require_login();
$servers=cab_owner_servers();
$email=cab_owner_email();
$need_email=($email==='');
echo cab_page('Личный кабинет');
$notice = ($_GET['ok']??'')==='pw' ? ['ok','Пароль сохранён. Теперь можно входить по email и паролю.']
        : (($_GET['err']??'')==='pwshort' ? ['err','Пароль слишком короткий (минимум 6 символов).']
        : (($_GET['err']??'')==='email' ? ['err','Сначала укажите email.'] : null));
if($notice){
  $bg = $notice[0]==='ok' ? 'background:#e7f7ec;color:#15803d' : 'background:#fde8e8;color:#b91c1c';
  echo '<div style="'.$bg.';padding:9px 12px;border-radius:8px;margin:10px 0">'.cab_h($notice[1]).'</div>';
}
if($need_email){
  echo '<div class="card"><p style="margin:0 0 8px">Укажите email — он нужен для чеков и управления подпиской:</p>'
     .'<form method=post action="set-email.php"><input name=email type=email required placeholder="you@mail.ru" '
     .'style="padding:9px;border:1px solid #ccc;border-radius:8px;width:60%"> <button class="btn">Сохранить</button></form></div>';
}
if(!$servers){ echo '<p class="muted">Инстансы не найдены для вашего аккаунта.</p>'; }
foreach($servers as $s){
  $exp=$s['custom_expires_at']??'';
  $days = $exp ? (int)floor((strtotime($exp)-time())/86400) : null;
  $ps=(int)($s['custom_price_server']??0);
  $sub=(int)($s['custom_price_subscription']??0);
  $disc=(int)($s['custom_discount']??0); if($disc<0)$disc=0; if($disc>100)$disc=100;
  $base=$ps+$sub;
  $total=cab_total($ps,$sub,$disc);
  $saved=$base-$total;
  $comment=trim($s['custom_payment_comment']??'');
  $hasCard=!empty($s['custom_payment_method_id']);
  echo '<div class="card">';
  echo '<div style="font-weight:600;font-size:16px">'.cab_h($s['custom_name']??'?').' <span class="muted" style="font-weight:400">'.cab_h($s['custom_host']??'').'</span></div>';
  echo '<div style="margin:8px 0;color:#333">Действует до: <b>'.cab_h($exp?substr($exp,0,10):'—').'</b>'.($days!==null?' <span class="muted">('.$days.' дн.)</span>':'').'</div>';
  // --- price breakdown from cc_servers ---
  echo '<table style="border-collapse:collapse;margin:8px 0;color:#333;font-size:14px">';
  echo '<tr><td style="padding:2px 14px 2px 0">Аренда сервера</td><td style="text-align:right">'.$ps.' ₽</td></tr>';
  echo '<tr><td style="padding:2px 14px 2px 0">Подписка (LLM)</td><td style="text-align:right">'.$sub.' ₽</td></tr>';
  if($disc>0) echo '<tr><td style="padding:2px 14px 2px 0">Скидка</td><td style="text-align:right;color:#16a34a">'.$disc.'% (−'.$saved.' ₽)</td></tr>';
  echo '<tr><td style="padding:6px 14px 2px 0;border-top:1px solid #eef0f3;font-weight:600">Итого к оплате</td><td style="text-align:right;border-top:1px solid #eef0f3;font-weight:600">'.$total.' ₽</td></tr>';
  echo '</table>';
  if($comment!=='') echo '<div class="muted" style="margin:4px 0 8px">💬 '.cab_h($comment).'</div>';
  echo '<form method=post action="pay.php" style="margin:6px 0"><input type=hidden name=server_id value="'.cab_h($s['id']).'"><button class="btn">Оплатить / продлить</button></form>';

  // --- счёт на оплату (PDF) ---
  $monthly=cab_monthly($s);
  echo '<div style="border-top:1px solid #eef0f3;margin-top:12px;padding-top:12px">';
  echo '<div style="font-weight:600;margin-bottom:6px">Счёт на оплату (PDF)</div>';
  echo '<div class="muted" style="margin-bottom:8px">Для оплаты по реквизитам от '.cab_h(cab_cfg()['legal']['name']??'').'. Выберите период:</div>';
  echo '<form method=get action="schet.php" target=_blank style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">';
  echo '<input type=hidden name=server_id value="'.cab_h($s['id']).'">';
  echo '<select name=months onchange="this.form.querySelector(\'.schet-sum\').textContent=(this.value*'.$monthly.')+\' ₽\'" style="padding:8px;border:1px solid #ccc;border-radius:8px">';
  foreach(cab_schet_months() as $m){ echo '<option value="'.$m.'">'.$m.' мес.</option>'; }
  echo '</select>';
  echo '<span class="schet-sum" style="font-weight:600">'.$monthly.' ₽</span>';
  echo '<button class="btn">Открыть счёт</button>';
  echo '</form>';
  echo '</div>';

  // --- payment history (YooKassa + manual server-less matches), collapsible + paginated ---
  echo cab_render_history($s);

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
// --- security: email + password for site login ---
if($email!==''){
  $hasPw=cab_pw_exists($email);
  echo '<div class="card">';
  echo '<div style="font-weight:600;margin-bottom:6px">Вход на сайт по паролю</div>';
  echo '<div class="muted" style="margin-bottom:10px">Email: <b>'.cab_h($email).'</b> · пароль '.($hasPw?'задан':'не задан').'. '
     .'Пароль позволяет входить в кабинет с сайта без ссылки из бота.</div>';
  echo '<form method=post action="set-password.php">'
     .'<input name=password type=password required minlength=6 placeholder="'.($hasPw?'Новый пароль':'Задать пароль').' (мин. 6)" '
     .'style="padding:9px;border:1px solid #ccc;border-radius:8px;width:60%"> '
     .'<button class="btn">'.($hasPw?'Изменить пароль':'Задать пароль').'</button></form>';
  echo '</div>';
}
echo cab_page_end();
