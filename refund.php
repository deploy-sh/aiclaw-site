<?php require_once __DIR__.'/cabinet/lib.php'; $L=cab_cfg()['legal'];
?><!doctype html><html lang=ru><meta charset=utf-8><meta name=viewport content="width=device-width,initial-scale=1">
<title>Политика возврата — aiclaw</title>
<body style="font-family:system-ui,sans-serif;max-width:760px;margin:0 auto;padding:24px;line-height:1.6;color:#1a1a1a">
<h1 style="font-size:22px">Политика возврата</h1>
<p>Услуга является цифровой (доступ к сервису aiclaw по подписке).</p>
<ul>
  <li>Возврат за неиспользованный оплаченный период возможен по запросу на
      <a href="mailto:<?=cab_h($L['email'])?>"><?=cab_h($L['email'])?></a> пропорционально остатку периода,
      если услуга не была оказана в полном объёме.</li>
  <li>Срок рассмотрения запроса — до 10 рабочих дней; возврат — на ту же карту/способ оплаты.</li>
  <li>Отключение автопродления не является возвратом — оно лишь прекращает будущие списания
      (см. <a href="/cabinet/autopay.php">условия автоплатежа</a>).</li>
</ul>
<p>Оператор: <b><?=cab_h($L['name'])?></b>. Реквизиты — на <a href="/contacts.php">странице контактов</a>.</p>
<p style="color:#888;font-size:13px"><a href="/">← На главную</a></p>
</body></html>
