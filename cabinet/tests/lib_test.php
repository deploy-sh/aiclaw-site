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

echo "ALL OK\n";
