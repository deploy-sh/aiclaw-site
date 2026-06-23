<?php
// pure-logic tests for cabinet/lib.php (no config / network needed)
require __DIR__.'/../lib.php';
function ok($c,$m){ echo ($c?"ok  ":"FAIL")." - $m\n"; if(!$c) exit(1); }

// discount is PERCENT
ok(cab_total(100,0,'')===100, 'no discount');
ok(cab_total(100,0,'10')===90, '10% off');
ok(cab_total(2500,2500,'20')===4000, '20% off');
ok(cab_total(100,0,'200')===1, 'clamp >100 -> min 1');
ok(cab_total(0,0,'')===1, 'min 1 floor');

// email
ok(cab_valid_email('a@b.ru'), 'valid email');
ok(!cab_valid_email('nope'), 'invalid email');

// token
$t = cab_make_token_value();
ok(strlen($t) >= 32, 'token length');
ok(cab_make_token_value() !== $t, 'tokens differ');

// html escape
ok(cab_h('<b>')==='&lt;b&gt;', 'html escape');

// --- schet helpers ---
// VAT начислением (on top)
ok(cab_nds_calc(4000,0)===[4000,0,0,4000], 'vat 0 -> no nds');
ok(cab_nds_calc(1000,20)===[1000,20,200,1200], 'vat 20% on top');
ok(cab_nds_calc(0,0)===[1,0,0,1], 'net floor 1');

// invoice number
ok(cab_schet_no(3,'20260623')==='AIC-3-20260623', 'schet no format');
ok(cab_schet_no('s2-demo','20260101')==='AIC-s2demo-20260101', 'schet no sanitized');

// amount in words
ok(cab_amount_words(4000)==='Четыре тысячи рублей 00 копеек', 'words 4000: '.cab_amount_words(4000));
ok(cab_amount_words(1)==='Один рубль 00 копеек', 'words 1: '.cab_amount_words(1));
ok(cab_amount_words(2500)==='Две тысячи пятьсот рублей 00 копеек', 'words 2500: '.cab_amount_words(2500));
ok(cab_amount_words(21)==='Двадцать один рубль 00 копеек', 'words 21: '.cab_amount_words(21));
ok(cab_amount_words(0)==='Ноль рублей 00 копеек', 'words 0: '.cab_amount_words(0));
ok(cab_amount_words(1234567)==='Один миллион двести тридцать четыре тысячи пятьсот шестьдесят семь рублей 00 копеек', 'words 1234567: '.cab_amount_words(1234567));

// month presets
ok(cab_schet_months()===[1,3,6,12], 'month presets');

echo "ALL OK\n";
