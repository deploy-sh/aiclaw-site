<?php
require __DIR__.'/lib.php';
cab_require_login();
$servers=cab_owner_servers();
$email=$_SESSION['owner']['email']??'';
$need_email=($email==='');
?><!doctype html><html lang=ru><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1">
<title>aiclaw — личный кабинет</title>
<body style="font-family:system-ui,sans-serif;max-width:720px;margin:0 auto;padding:24px;color:#1a1a1a">
<h1 style="font-size:22px">Личный кабинет aiclaw</h1>
<?php if($need_email): ?>
<form method=post action="set-email.php" style="background:#f5f7fa;padding:16px;border-radius:12px;margin:16px 0">
  <p style="margin:0 0 8px">Укажите email — он нужен для чеков и управления подпиской:</p>
  <input name=email type=email required placeholder="you@mail.ru" style="padding:9px;border:1px solid #ccc;border-radius:8px;width:60%">
  <button style="padding:9px 16px;border:0;border-radius:8px;background:#2563eb;color:#fff">Сохранить</button>
</form>
<?php endif; ?>
<?php if(!$servers): ?>
  <p>Инстансы не найдены для вашего аккаунта.</p>
<?php endif; ?>
<?php foreach($servers as $s):
  $exp=$s['custom_expires_at']??'';
  $days = $exp ? (int)floor((strtotime($exp)-time())/86400) : null;
  $total=cab_total($s['custom_price_server']??0,$s['custom_price_subscription']??0,$s['custom_discount']??'');
  $autopay=!empty($s['custom_payment_method_id']);
?>
<div style="border:1px solid #e3e3e3;border-radius:12px;padding:16px;margin:12px 0">
  <div style="font-weight:600"><?=cab_h($s['custom_name']??'?')?> <span style="color:#999;font-weight:400"><?=cab_h($s['custom_host']??'')?></span></div>
  <div style="margin:6px 0;color:#333">Действует до: <b><?=cab_h($exp?substr($exp,0,10):'—')?></b><?= $days!==null ? ' ('.$days.' дн.)' : '' ?></div>
  <div style="margin:6px 0;color:#333">Сумма продления: <b><?=$total?> ₽</b></div>
  <div style="margin:6px 0">Автосписание: <?= $autopay ? '<span style="color:#16a34a">включено</span>' : '<span style="color:#888">выключено</span>' ?></div>
  <form method=post action="pay.php" style="display:inline">
    <input type=hidden name=server_id value="<?=cab_h($s['id'])?>">
    <button style="padding:9px 16px;border:0;border-radius:8px;background:#2563eb;color:#fff;margin-top:8px">Оплатить / продлить</button>
  </form>
  <?php if($autopay): ?>
  <form method=post action="unbind.php" style="display:inline" onsubmit="return confirm('Отключить автосписание и отвязать карту?')">
    <input type=hidden name=server_id value="<?=cab_h($s['id'])?>">
    <button style="padding:9px 16px;border:1px solid #d33;border-radius:8px;background:#fff;color:#d33;margin-top:8px">Отключить автосписание</button>
  </form>
  <?php endif; ?>
</div>
<?php endforeach; ?>
<p style="margin-top:28px;color:#888;font-size:13px">
  <a href="autopay.php">Условия автоплатежа</a> ·
  <a href="/oferta.php">Оферта</a> ·
  <a href="/privacy.php">Конфиденциальность</a> ·
  <a href="/refund.php">Возврат</a> ·
  <a href="/contacts.php">Контакты</a> ·
  <a href="logout.php">Выйти</a>
</p>
</body></html>
