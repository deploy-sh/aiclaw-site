<?php
// aiclaw billing cabinet — shared lib. Secrets/config live OUTSIDE webroot.
// Config path: env CABINET_CONFIG overrides; default = host path outside webroot.

function cab_cfg(){
  static $c=null;
  if($c===null){
    $p = getenv('CABINET_CONFIG') ?: '/home/korfix/web/aiclaw.korfix.app/private/cabinet-config.php';
    $c = require $p;
  }
  return $c;
}

function cab_valid_email($e){ return (bool)filter_var($e, FILTER_VALIDATE_EMAIL); }

// discount is a PERCENT (0..100); total = round(base * (1 - d/100)), min 1
function cab_total($price_server,$price_sub,$discount){
  $base = (int)$price_server + (int)$price_sub;
  $d = (int)$discount; if($d<0)$d=0; if($d>100)$d=100;
  return max(1, (int)round($base*(1-$d/100)));
}

function cab_make_token_value(){ return bin2hex(random_bytes(24)); }

function cab_h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// --- branded page chrome (shared header/footer for all cabinet pages) ---
function cab_page($title='Личный кабинет'){
  $t=cab_h($title);
  return '<!doctype html><html lang=ru><head><meta charset=utf-8>'
    .'<meta name=viewport content="width=device-width,initial-scale=1"><title>aiclaw — '.$t.'</title>'
    .'<link rel="icon" type="image/svg+xml" href="/favicon.svg"><link rel="alternate icon" href="/favicon.ico">'
    .'<style>'
    .'body{font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;margin:0;color:#1a1a1a;background:#f6f7f9}'
    .'.cab-hd{background:#0f172a;color:#fff;padding:14px 20px;display:flex;align-items:center;gap:12px}'
    .'.cab-hd .lg{font-size:19px;font-weight:700;letter-spacing:.3px}'
    .'.cab-hd .lg b{color:#60a5fa}.cab-hd .sub{color:#94a3b8;font-size:13px;margin-left:auto}'
    .'.cab-wrap{max-width:720px;margin:0 auto;padding:24px}'
    .'.card{border:1px solid #e6e8ec;border-radius:12px;padding:16px;margin:12px 0;background:#fff}'
    .'a{color:#2563eb}button{font:inherit;cursor:pointer}'
    .'.btn{padding:9px 16px;border:0;border-radius:8px;background:#2563eb;color:#fff}'
    .'.btn.sec{background:#fff;color:#d33;border:1px solid #d33}'
    .'.btn.off{background:#eef1f5;color:#9aa3af;border:1px solid #e1e5ea;cursor:not-allowed}'
    .'.muted{color:#8a93a0;font-size:13px}'
    .'.cab-ft{max-width:720px;margin:8px auto 28px;padding:0 24px;color:#94a3b8;font-size:13px}'
    .'</style>'
    .'<script>'
    .'function cabHistToggle(id){var b=document.getElementById("hist-"+id),a=document.getElementById("histarrow-"+id);if(!b)return;var open=b.style.display==="none";b.style.display=open?"block":"none";if(a)a.textContent=open?"▾":"▸";}'
    .'function cabHistMore(id){var hid=document.querySelectorAll("#hist-"+id+" tr.hx-hidden");for(var i=0;i<10&&i<hid.length;i++){hid[i].classList.remove("hx-hidden");hid[i].style.display="";}var rem=document.querySelectorAll("#hist-"+id+" tr.hx-hidden").length;var btn=document.getElementById("histmore-"+id);if(btn){if(rem===0){btn.style.display="none";}else{btn.textContent="Показать ещё "+Math.min(10,rem)+" из "+rem;}}}'
    .'</script>'
    .'</head><body>'
    .'<div class="cab-hd"><a href="/" class="lg" style="color:#fff;text-decoration:none">ai<b>claw</b></a><span class="sub">'.$t.'</span></div>'
    .'<div class="cab-wrap">';
}
function cab_page_end($links=true){
  $f = $links
    ? '<div class="cab-ft"><a href="/cabinet/autopay.php">Условия автоплатежа</a> · '
      .'<a href="/oferta.php">Оферта</a> · <a href="/privacy.php">Конфиденциальность</a> · '
      .'<a href="/refund.php">Возврат</a> · <a href="/contacts.php">Контакты</a> · '
      .'<a href="/cabinet/logout.php">Выйти</a></div>'
    : '';
  return '</div>'.$f.'</body></html>';
}

// --- Korfix DB API (Bearer) ---
function cab_korfix($method,$path,$form=null){
  $cfg=cab_cfg();
  $ch=curl_init($cfg['korfix_base'].$path);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_TIMEOUT,20);
  curl_setopt($ch,CURLOPT_HTTPHEADER,['Authorization: Bearer '.$cfg['korfix_token']]);
  if($method==='POST'){
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($form?:[]));
  }
  $body=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
  return [$code, json_decode($body,true)];
}
function cab_list($cat){
  static $cache=[];
  if(isset($cache[$cat])) return $cache[$cat];
  [$c,$j]=cab_korfix('GET',"/$cat/?limit=200");
  $d=$j['data']??[];
  $out = (is_array($d) && array_key_exists(0,$d)) ? $d : ($d ? [$d] : []);
  $cache[$cat]=$out;
  return $out;
}
function cab_get($cat,$id){ [$c,$j]=cab_korfix('GET',"/$cat/$id/"); return $j['data']??null; }
function cab_update($cat,$id,$fields){ return cab_korfix('POST',"/$cat/$id/",$fields); }

// --- one-time login tokens (flat files, outside webroot; no DB extension needed) ---
// consume = read + unlink (atomic one-time). token is hex (path-safe).
function cab_tokens_dir(){
  $cfg=cab_cfg(); $d=$cfg['tokens_dir'];
  if(!is_dir($d)) @mkdir($d,0700,true);
  return rtrim($d,'/');
}
function cab_token_create($email,$server_id,$ttl,$reuse=false,$extra=null){
  $t=cab_make_token_value();
  $row=['email'=>$email,'server_id'=>$server_id,'expires'=>time()+$ttl,'reuse'=>$reuse?1:0];
  if(is_array($extra) && $extra) $row['x']=$extra;   // optional intent payload (e.g. schet params)
  file_put_contents(cab_tokens_dir().'/'.$t, json_encode($row), LOCK_EX);
  return $t;
}
function cab_token_consume($t){
  if(!preg_match('/^[a-f0-9]{16,}$/',$t)) return null;
  $f=cab_tokens_dir().'/'.$t;
  if(!is_file($f)) return null;
  $raw=@file_get_contents($f);
  $r=json_decode($raw,true);
  if(!is_array($r) || ($r['expires']??0) < time()){ @unlink($f); return null; }
  if(empty($r['reuse'])) @unlink($f);  // one-time links consumed; reusable links kept until expiry
  return $r;
}
// --- password accounts (bcrypt, files in private/, keyed by email hash; no DB) ---
function cab_users_dir(){
  $cfg=cab_cfg(); $d=$cfg['users_dir'];
  if(!is_dir($d)) @mkdir($d,0700,true);
  return rtrim($d,'/');
}
function cab_user_file($email){ return cab_users_dir().'/'.hash('sha256', strtolower(trim($email))); }
function cab_pw_set($email,$pw){ file_put_contents(cab_user_file($email), password_hash($pw, PASSWORD_DEFAULT), LOCK_EX); }
function cab_pw_exists($email){ return is_file(cab_user_file($email)); }
function cab_pw_check($email,$pw){
  $f=cab_user_file($email); if(!is_file($f)) return false;
  return password_verify($pw, trim((string)@file_get_contents($f)));
}

// opportunistic GC of expired token files
function cab_tokens_gc(){
  foreach(glob(cab_tokens_dir().'/*') as $f){
    $r=json_decode(@file_get_contents($f),true);
    if(!is_array($r) || ($r['expires']??0) < time()) @unlink($f);
  }
}

// --- session ---
function cab_session_start(){
  if(session_status()===PHP_SESSION_ACTIVE) return;
  session_name('aiclaw_cab');
  session_set_cookie_params(['lifetime'=>0,'path'=>'/cabinet','secure'=>true,'httponly'=>true,'samesite'=>'Lax']);
  session_start();
}
function cab_require_login(){
  cab_session_start(); $cfg=cab_cfg();
  if(empty($_SESSION['owner']) || ($_SESSION['ts']??0) < time()-$cfg['session_ttl']){
    header('Location: /cabinet/login.php?expired=1'); exit;
  }
  $_SESSION['ts']=time();
}
// session anchors only the LOGIN server_id; email is resolved LIVE from its request each time.
function cab_login_server_id(){ return (string)($_SESSION['owner']['server_id']??''); }
function cab_owner_email(){
  // password-session: email is the anchor
  if(!empty($_SESSION['owner']['email'])) return strtolower(trim($_SESSION['owner']['email']));
  // magic-link session: resolve live from the login server's request
  $sid=cab_login_server_id();
  foreach(cab_list('custom_cc_requests') as $r){
    if((string)($r['custom_server_id']??'')===$sid) return trim($r['custom_contact_email']??'');
  }
  return '';
}
// returns owner's servers with _req attached: the login server + any server sharing the same contact email
function cab_owner_servers(){
  $sid=cab_login_server_id();
  $email=cab_owner_email();
  $servers=cab_list('custom_cc_servers');
  $reqs=cab_list('custom_cc_requests');
  $byServer=[];
  foreach($reqs as $r){ if(isset($r['custom_server_id'])) $byServer[(string)$r['custom_server_id']]=$r; }
  $out=[];
  foreach($servers as $s){
    $req=$byServer[(string)$s['id']]??[];
    $em=trim($req['custom_contact_email']??'');
    $match = ((string)$s['id']===$sid) || ($email!=='' && $em===$email);
    if($match){ $s['_req']=$req; $out[]=$s; }
  }
  return $out;
}

// =====================================================================
// Счёт на оплату (invoice) — pure helpers + HTML render + PDF via wkhtmltopdf
// =====================================================================

// allowed month presets for the cabinet UI
function cab_schet_months(){ return [1,3,6,12]; }

// monthly amount for a server = the renewal total (price_server+price_subscription-discount%)
function cab_monthly($srv){
  return cab_total($srv['custom_price_server']??0, $srv['custom_price_subscription']??0, $srv['custom_discount']??'');
}

// VAT "начислением" (added on top). vat is a percent (0 => Без НДС).
// returns [net, vat_rate, vat_sum, total] all ints (rubles)
function cab_nds_calc($net, $vat){
  $net=max(1,(int)round($net));
  $vat=(int)$vat; if($vat<0)$vat=0; if($vat>100)$vat=100;
  $vsum=(int)round($net*$vat/100);
  return [$net, $vat, $vsum, $net+$vsum];
}

// invoice number: AIC-{server_id}-{YYYYMMDD}
function cab_schet_no($server_id,$ymd=null){
  $ymd = $ymd ?: date('Ymd');
  return 'AIC-'.preg_replace('/[^0-9a-zA-Z]/','',(string)$server_id).'-'.$ymd;
}

// amount (rubles int) -> Russian words, e.g. 4000 => "Четыре тысячи рублей 00 копеек"
function cab_amount_words($rub){
  $rub=(int)$rub;
  $ones=['','один','два','три','четыре','пять','шесть','семь','восемь','девять','десять',
    'одиннадцать','двенадцать','тринадцать','четырнадцать','пятнадцать','шестнадцать',
    'семнадцать','восемнадцать','девятнадцать'];
  $onesF=['','одна','две','три','четыре','пять','шесть','семь','восемь','девять'];
  $tens=['','','двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят','восемьдесят','девяносто'];
  $hund=['','сто','двести','триста','четыреста','пятьсот','шестьсот','семьсот','восемьсот','девятьсот'];
  $triplet=function($n,$fem) use($ones,$onesF,$tens,$hund){
    $n=(int)$n; $w=[];
    $h=intdiv($n,100); $r=$n%100; $t=intdiv($r,10); $u=$r%10;
    if($h) $w[]=$hund[$h];
    if($t>=2){ $w[]=$tens[$t]; if($u) $w[]=($fem?$onesF[$u]:$ones[$u]); }
    else { if($r) $w[]=($fem?($r<10?$onesF[$r]:$ones[$r]):$ones[$r]); }
    return implode(' ',$w);
  };
  $form=function($n,$f1,$f2,$f5){ $n=abs($n)%100; $n1=$n%10; if($n>10&&$n<20)return $f5; if($n1>1&&$n1<5)return $f2; if($n1==1)return $f1; return $f5; };
  if($rub===0){ $words='ноль'; }
  else {
    $mln=intdiv($rub,1000000); $ths=intdiv($rub%1000000,1000); $rest=$rub%1000;
    $parts=[];
    if($mln){ $parts[]=trim($triplet($mln,false).' '.$form($mln,'миллион','миллиона','миллионов')); }
    if($ths){ $parts[]=trim($triplet($ths,true).' '.$form($ths,'тысяча','тысячи','тысяч')); }
    if($rest){ $parts[]=$triplet($rest,false); }
    $words=trim(implode(' ',array_filter($parts)));
  }
  $words=preg_replace('/\s+/',' ',$words);
  $words=mb_strtoupper(mb_substr($words,0,1)).mb_substr($words,1);
  $rubWord=$form($rub,'рубль','рубля','рублей');
  return $words.' '.$rubWord.' 00 копеек';
}

// Build the A4 invoice HTML. $o = ['server','net','vat','purpose','date']
function cab_render_schet_html($o){
  $cfg=cab_cfg();
  $L=$cfg['legal']; $B=$cfg['bank'] ?? [];
  $srv=$o['server']; $req=$srv['_req']??[];
  [$net,$vat,$vsum,$total]=cab_nds_calc($o['net'],$o['vat']);
  $ymd=$o['date'] ?? date('Ymd');
  $no=cab_schet_no($srv['id'],$ymd);
  $dateHuman=date('d.m.Y', strtotime(substr($ymd,0,4).'-'.substr($ymd,4,2).'-'.substr($ymd,6,2)));
  $buyerName=trim($req['custom_user_name']??'') ?: trim($req['custom_contact_email']??'') ?: 'Покупатель';
  $buyerEmail=trim($req['custom_contact_email']??'');
  $purpose = trim($o['purpose']??'') !== '' ? $o['purpose'] : ('Оплата подписки aiclaw (сервер '.($srv['custom_name']??'').')');
  $vatCell = $vat>0 ? ($vat.'% ('.$vsum.' ₽)') : 'Без НДС';
  $h='cab_h';
  $rows='<tr>'
      .'<td style="border:1px solid #333;padding:6px;text-align:center">1</td>'
      .'<td style="border:1px solid #333;padding:6px">'.$h($purpose).'</td>'
      .'<td style="border:1px solid #333;padding:6px;text-align:center">усл.</td>'
      .'<td style="border:1px solid #333;padding:6px;text-align:center">1</td>'
      .'<td style="border:1px solid #333;padding:6px;text-align:right">'.$net.'</td>'
      .'<td style="border:1px solid #333;padding:6px;text-align:right">'.$vatCell.'</td>'
      .'<td style="border:1px solid #333;padding:6px;text-align:right">'.$total.'</td>'
      .'</tr>';
  ob_start(); ?>
<!doctype html><html lang=ru><head><meta charset=utf-8>
<style>
@page{ size:A4; margin:14mm; }
body{ font-family:'DejaVu Sans',sans-serif; color:#000; font-size:12px; }
h1{ font-size:17px; margin:0 0 2px; }
table{ border-collapse:collapse; width:100%; }
.bank td{ border:1px solid #333; padding:5px 7px; vertical-align:top; }
.muted{ color:#444; }
.tot td{ padding:3px 0; }
</style></head><body>

<table class="bank" style="margin-bottom:10px">
  <tr>
    <td rowspan="2" style="width:62%">
      <b><?=$h($B['name']??'')?></b><br>Банк получателя
    </td>
    <td style="width:14%">БИК</td>
    <td style="width:24%"><?=$h($B['bik']??'')?></td>
  </tr>
  <tr>
    <td>Сч. №</td>
    <td><?=$h($B['ks']??'')?></td>
  </tr>
  <tr>
    <td>
      ИНН <?=$h($L['inn']??'')?> &nbsp; КПП <?=$h($L['kpp']??'')?><br>
      <b><?=$h($L['name']??'')?></b>
    </td>
    <td>Сч. №</td>
    <td><?=$h($B['rs']??'')?></td>
  </tr>
</table>

<h1>Счёт на оплату № <?=$h($no)?> от <?=$h($dateHuman)?></h1>
<hr style="border:0;border-top:2px solid #000;margin:4px 0 12px">

<div style="margin:6px 0"><b>Поставщик (Исполнитель):</b><br>
<?=$h($L['name']??'')?>, ИНН <?=$h($L['inn']??'')?>, КПП <?=$h($L['kpp']??'')?>, ОГРН <?=$h($L['ogrn']??'')?>,
<?=$h($L['address']??'')?></div>

<div style="margin:6px 0"><b>Покупатель (Заказчик):</b><br>
<?=$h($buyerName)?><?=$buyerEmail?', '.$h($buyerEmail):''?></div>

<table style="margin-top:10px">
  <thead><tr style="background:#f0f0f0">
    <td style="border:1px solid #333;padding:6px;text-align:center">№</td>
    <td style="border:1px solid #333;padding:6px">Наименование услуги</td>
    <td style="border:1px solid #333;padding:6px">Ед.</td>
    <td style="border:1px solid #333;padding:6px">Кол-во</td>
    <td style="border:1px solid #333;padding:6px;text-align:right">Цена, ₽</td>
    <td style="border:1px solid #333;padding:6px;text-align:right">НДС</td>
    <td style="border:1px solid #333;padding:6px;text-align:right">Сумма, ₽</td>
  </tr></thead>
  <tbody><?=$rows?></tbody>
</table>

<table style="margin-top:10px;width:auto;margin-left:auto" class="tot">
  <tr><td style="text-align:right;padding-right:18px">Итого:</td><td style="text-align:right"><b><?=$net?> ₽</b></td></tr>
  <tr><td style="text-align:right;padding-right:18px">НДС:</td><td style="text-align:right"><?=$vat>0?($vsum.' ₽'):'Без НДС'?></td></tr>
  <tr><td style="text-align:right;padding-right:18px">Всего к оплате:</td><td style="text-align:right"><b><?=$total?> ₽</b></td></tr>
</table>

<div style="margin-top:10px">Всего наименований 1, на сумму <b><?=$total?> ₽</b>.<br>
<b><?=$h(cab_amount_words($total))?></b></div>

<div style="margin-top:16px" class="muted">Назначение платежа: <?=$h($purpose)?>. Счёт № <?=$h($no)?>.</div>

<div style="margin-top:26px">
  Руководитель / Гл. бухгалтер ________________________ &nbsp; <?=$h($L['director']??'')?>
</div>

</body></html>
<?php
  return ob_get_clean();
}

// Render HTML -> PDF bytes via wkhtmltopdf. Returns string (pdf) or null on failure.
function cab_pdf_from_html($html){
  $tmp = sys_get_temp_dir();
  $in  = tempnam($tmp,'sch_'); $inHtml=$in.'.html'; @rename($in,$inHtml);
  $out = tempnam($tmp,'sch_'); $outPdf=$out.'.pdf'; @rename($out,$outPdf);
  file_put_contents($inHtml,$html);
  $bin = (is_file('/usr/bin/wkhtmltopdf')?'/usr/bin/wkhtmltopdf':'wkhtmltopdf');
  $cmd = escapeshellarg($bin).' -q --encoding utf-8 --enable-local-file-access '
       .'--page-size A4 --margin-top 12 --margin-bottom 12 --margin-left 12 --margin-right 12 '
       .escapeshellarg($inHtml).' '.escapeshellarg($outPdf).' 2>/dev/null';
  @exec($cmd,$o,$rc);
  $pdf = is_file($outPdf) ? file_get_contents($outPdf) : '';
  @unlink($inHtml); @unlink($outPdf);
  return ($rc===0 && strlen($pdf)>500) ? $pdf : null;
}

// =====================================================================
// Payment history (YooKassa source of truth — incl. failed/canceled attempts)
// =====================================================================

// Map a YooKassa status to a RU label + badge color.
function cab_yk_status($st){
  switch($st){
    case 'succeeded':           return ['✅ Оплачено',        '#15803d', '#e7f7ec'];
    case 'canceled':            return ['❌ Не завершено',     '#b91c1c', '#fde8e8'];
    case 'pending':             return ['⏳ Ожидает оплаты',  '#92600a', '#fff3df'];
    case 'waiting_for_capture': return ['⏳ В обработке',      '#92600a', '#fff3df'];
    default:                    return [cab_h($st),           '#475569', '#eef1f5'];
  }
}

// Fetch payment attempts for a server from YooKassa, filtered by metadata.server_id.
// Returns list (newest first) of [id,date,amount,status,description,advanced].
// `advanced` = succeeded AND carried metadata.server_id (i.e. it actually extended the sub).
// On any API error returns null (caller shows a graceful fallback).
function cab_yk_payments($server_id,$months=12,$max_pages=12){
  $cfg=cab_cfg();
  if(empty($cfg['yk_shop'])||empty($cfg['yk_key'])) return null;
  $sid=(string)$server_id;
  $since=gmdate('Y-m-d\TH:i:s.000\Z', time()-$months*30*86400);
  $out=[]; $cursor=null; $pages=0;
  do{
    $q=['limit'=>100,'created_at.gte'=>$since];
    if($cursor) $q['cursor']=$cursor;
    $url='https://api.yookassa.ru/v3/payments?'.http_build_query($q);
    $ch=curl_init($url);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>20,
      CURLOPT_USERPWD=>$cfg['yk_shop'].':'.$cfg['yk_key'],
      CURLOPT_HTTPHEADER=>['Content-Type: application/json']]);
    $r=curl_exec($ch); $code=curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch);
    if($code>=400 || $r===false) return ($pages===0)? null : $out; // hard fail on first page only
    $j=json_decode($r,true);
    foreach(($j['items']??[]) as $it){
      $mid=(string)($it['metadata']['server_id']??$it['metadata']['sub_id']??'');
      if($mid!==$sid) continue;
      $st=$it['status']??'';
      $out[]=[
        'id'=>$it['id']??'',
        'date'=>$it['created_at']??'',
        'amount'=>$it['amount']['value']??'',
        'status'=>$st,
        'description'=>$it['description']??'',
        'advanced'=>($st==='succeeded' && $mid!==''),
      ];
    }
    $cursor=$j['next_cursor']??null; $pages++;
  } while($cursor && $pages<$max_pages);
  usort($out,function($a,$b){ return strcmp($b['date'],$a['date']); });
  return $out;
}

// Manually-attributed server-less payments: cc_charges rows whose custom_request_id == this
// server's request AND whose name carries the UNMATCHED marker (server-less payment we matched by hand).
// These never have YooKassa metadata, so they don't duplicate the YooKassa list.
function cab_manual_charges($request_id){
  $out=[];
  if($request_id==='') return $out;
  foreach(cab_list('custom_cc_charges') as $c){
    if((string)($c['custom_request_id']??'')!==(string)$request_id) continue;
    $nm=$c['name']??'';
    if(strpos($nm,'UNMATCHED')===false) continue;
    $desc=preg_replace('/^.*?UNMATCHED\s*\[[^\]]*\]:\s*/u','',$nm);
    $desc=preg_replace('/\s*\|\s*pid=.*$/u','',$desc);
    $out[]=['id'=>'','date'=>($c['custom_created_at']??''),'amount'=>($c['custom_amount']??''),
            'status'=>'succeeded','description'=>$desc,'advanced'=>false,'manual'=>true];
  }
  return $out;
}

// Render the payment-history block (HTML) for one server. Collapsible + paginated (10/page).
// $srv = server row (from cab_owner_servers, carries '_req'). Merges YooKassa attempts +
// manually-attributed server-less payments (cc_charges).
function cab_render_history($srv){
  $sid=(string)($srv['id']??'');
  $reqId=(string)($srv['_req']['id']??'');
  $yk=cab_yk_payments($sid);
  $ykFailed=($yk===null);
  $rows=is_array($yk)?$yk:[];
  foreach(cab_manual_charges($reqId) as $m){ $rows[]=$m; }
  usort($rows,function($a,$b){ return strcmp(($b['date']??''),($a['date']??'')); });
  $cnt=count($rows);

  $h ='<div style="border-top:1px solid #eef0f3;margin-top:12px;padding-top:12px">';
  $h.='<div onclick="cabHistToggle(\''.cab_h($sid).'\')" style="font-weight:600;cursor:pointer;user-select:none">'
    .'<span id="histarrow-'.cab_h($sid).'">▸</span> История платежей'
    .($cnt?' <span class="muted" style="font-weight:400">('.$cnt.')</span>':'').'</div>';
  $h.='<div id="hist-'.cab_h($sid).'" style="display:none;margin-top:8px">';
  if($ykFailed && !$rows){
    $h.='<div class="muted">История временно недоступна. Попробуйте обновить страницу позже.</div>';
  } elseif(!$rows){
    $h.='<div class="muted">Платежей пока нет.</div>';
  } else {
    if($ykFailed) $h.='<div class="muted" style="margin-bottom:6px">⚠️ Данные YooKassa временно недоступны, показаны только ручные сверки.</div>';
    $h.='<table style="border-collapse:collapse;width:100%;font-size:13px">';
    $i=0;
    foreach($rows as $r){
      $hide=$i>=10;
      if(!empty($r['manual'])){ $lbl='🔧 Зачтено вручную'; $fg='#475569'; $bg='#eef1f5'; }
      else { [$lbl,$fg,$bg]=cab_yk_status($r['status']); }
      $d=!empty($r['date'])?date('d.m.Y', strtotime($r['date'])):'—';
      $amt=($r['amount']!==''&&$r['amount']!==null)?cab_h(rtrim(rtrim((string)$r['amount'],'0'),'.')).' ₽':'—';
      $note=!empty($r['advanced'])?' <span class="muted" style="font-size:11px">· продлило подписку</span>':'';
      $h.='<tr'.($hide?' class="hx-hidden"':'').' style="'.($hide?'display:none;':'').'border-top:1px solid #f1f3f6">';
      $h.='<td style="padding:6px 8px 6px 0;white-space:nowrap;color:#475569">'.$d.'</td>';
      $h.='<td style="padding:6px 8px;white-space:nowrap;font-weight:600">'.$amt.'</td>';
      $h.='<td style="padding:6px 8px"><span style="background:'.$bg.';color:'.$fg.';padding:2px 8px;border-radius:999px;white-space:nowrap">'.cab_h($lbl).'</span>'.$note.'</td>';
      $h.='<td style="padding:6px 0 6px 8px;color:#64748b">'.cab_h($r['description']).'</td>';
      $h.='</tr>';
      $i++;
    }
    $h.='</table>';
    if($cnt>10){
      $rem=$cnt-10;
      $h.='<button type=button id="histmore-'.cab_h($sid).'" onclick="cabHistMore(\''.cab_h($sid).'\')" class="btn sec" '
        .'style="margin-top:8px;font-size:13px;padding:6px 12px">Показать ещё '.min(10,$rem).' из '.$rem.'</button>';
    }
    $h.='<div class="muted" style="margin-top:8px;font-size:11px">Источник: YooKassa (попытки за 12 мес) + ручные сверки.</div>';
  }
  $h.='</div></div>';
  return $h;
}
