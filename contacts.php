<?php require_once __DIR__.'/cabinet/lib.php'; $L=cab_cfg()['legal'];
?><!doctype html><html lang=ru><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1">
<title>Контакты и реквизиты — aiclaw</title>
<body style="font-family:system-ui,sans-serif;max-width:720px;margin:0 auto;padding:24px;line-height:1.6;color:#1a1a1a">
<h1 style="font-size:22px">Контакты и реквизиты</h1>
<p><b><?=cab_h($L['name'])?></b></p>
<ul style="list-style:none;padding:0">
  <li>ИНН: <?=cab_h($L['inn'])?></li>
  <li>ОГРН: <?=cab_h($L['ogrn'])?></li>
  <li>Адрес: <?=cab_h($L['address'])?></li>
  <li>Телефон: <?=cab_h($L['phone'])?></li>
  <li>Email: <a href="mailto:<?=cab_h($L['email'])?>"><?=cab_h($L['email'])?></a></li>
</ul>
<p style="color:#888;font-size:13px"><a href="/">← На главную</a></p>
</body></html>
