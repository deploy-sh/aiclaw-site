<?php
// Счёт на оплату (PDF) от поставщика из конфига. Требует cab-сессию (та же авторизация,
// что «Управление подпиской»). Сумма: либо по числу месяцев (UI кабинета), либо запечённая
// в magic-link intent (рассылка / произвольная сумма + НДС + назначение).
require __DIR__.'/lib.php';
cab_require_login();
$cfg=cab_cfg();

$servers=cab_owner_servers();
$byId=[]; foreach($servers as $s){ $byId[(string)$s['id']]=$s; }

$intent = $_SESSION['schet_intent'] ?? null;
$getSid = (string)($_POST['server_id'] ?? $_GET['server_id'] ?? '');
$getMonths = isset($_POST['months']) ? (int)$_POST['months'] : (isset($_GET['months'])?(int)$_GET['months']:0);

// Decide source: explicit UI request (server_id+months) overrides any stale intent.
if($getSid!=='' && $getMonths>0){
  $intent=null;
}

if($intent && !empty($intent['server_id']) && isset($byId[(string)$intent['server_id']])){
  $srv=$byId[(string)$intent['server_id']];
  $net=(int)$intent['net'];
  $vat=(int)($intent['vat'] ?? ($cfg['vat_default'] ?? 0));
  $purpose=(string)($intent['purpose'] ?? '');
} else {
  // manual mode from cabinet UI
  $srv = $byId[$getSid] ?? null;
  if(!$srv){ http_response_code(403); echo cab_page('Счёт').'<div class="card">Сервер не найден в вашем аккаунте. <a href="/cabinet/">Назад</a></div>'.cab_page_end(false); exit; }
  $months=$getMonths>0?$getMonths:1;
  $allowed=cab_schet_months(); if(!in_array($months,$allowed,true)) $months=1;
  $net=cab_monthly($srv)*$months;
  $vat=(int)($cfg['vat_default'] ?? 0);
  $purpose='Оплата подписки aiclaw (сервер '.($srv['custom_name']??'').'), '.$months.' мес.';
}

$opts=['server'=>$srv,'net'=>$net,'vat'=>$vat,'purpose'=>$purpose,'date'=>date('Ymd')];
$html=cab_render_schet_html($opts);

// debug/preview
if(($_GET['format']??'')==='html'){ header('Content-Type: text/html; charset=utf-8'); echo $html; exit; }

$pdf=cab_pdf_from_html($html);
if($pdf===null){
  // graceful fallback: show the printable HTML if PDF backend failed
  header('Content-Type: text/html; charset=utf-8');
  echo '<!doctype html><meta charset=utf-8><div style="font-family:sans-serif;padding:10px;background:#fde8e8;color:#b91c1c">PDF-движок недоступен, показана печатная версия. Ctrl+P → «Сохранить в PDF».</div>';
  echo $html;
  exit;
}
$fname='schet-'.cab_schet_no($srv['id']).'.pdf';
$disp = (($_GET['dl']??'')==='1') ? 'attachment' : 'inline';
header('Content-Type: application/pdf');
header('Content-Disposition: '.$disp.'; filename="'.$fname.'"');
header('Content-Length: '.strlen($pdf));
header('Cache-Control: private, max-age=0, no-store');
echo $pdf;
