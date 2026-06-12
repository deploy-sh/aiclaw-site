<?php require_once __DIR__.'/cabinet/lib.php'; $L=cab_cfg()['legal'];
echo cab_page('Политика возврата');
echo '<div class="card" style="line-height:1.6">';
echo '<h1 style="font-size:20px;margin-top:0">Политика возврата</h1>';
echo '<p>Услуга является цифровой (доступ к сервису aiclaw по подписке).</p>';
echo '<ul style="line-height:1.7">'
   .'<li>Возврат за неиспользованный оплаченный период возможен по запросу на '
   .'<a href="mailto:'.cab_h($L['email']).'">'.cab_h($L['email']).'</a> пропорционально остатку периода, '
   .'если услуга не была оказана в полном объёме.</li>'
   .'<li>Срок рассмотрения запроса — до 10 рабочих дней; возврат — на ту же карту/способ оплаты.</li>'
   .'<li>Отключение автопродления не является возвратом — оно лишь прекращает будущие списания '
   .'(см. <a href="/cabinet/autopay.php">условия автоплатежа</a>).</li>'
   .'</ul>';
echo '<p>Оператор: <b>'.cab_h($L['name']).'</b>. Реквизиты — на <a href="/contacts.php">странице контактов</a>.</p>';
echo '<p class="muted"><a href="/">← На главную</a></p>';
echo '</div>';
echo cab_page_end(false);
