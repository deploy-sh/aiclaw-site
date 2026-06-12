<?php
// aiclaw billing cabinet — shared lib. Secrets/config live OUTSIDE webroot.
// Config path: env CABINET_CONFIG overrides; default = host path outside webroot.

function cab_cfg(){
  static $c=null;
  if($c===null){
    $p = getenv('CABINET_CONFIG') ?: '/home/korfix/web/aiclaw.korfix.app/cabinet-config.php';
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
  [$c,$j]=cab_korfix('GET',"/$cat/?limit=200");
  $d=$j['data']??[];
  if(is_array($d) && array_key_exists(0,$d)) return $d;     // list
  return $d ? [$d] : [];                                     // single or empty
}
function cab_get($cat,$id){ [$c,$j]=cab_korfix('GET',"/$cat/$id/"); return $j['data']??null; }
function cab_update($cat,$id,$fields){ return cab_korfix('POST',"/$cat/$id/",$fields); }

// --- one-time login tokens (SQLite, outside webroot) ---
function cab_db(){
  static $db=null;
  if($db===null){
    $cfg=cab_cfg();
    $db=new PDO('sqlite:'.$cfg['tokens_db']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE TABLE IF NOT EXISTS tokens(token TEXT PRIMARY KEY, email TEXT, server_id TEXT, expires INTEGER, used INTEGER DEFAULT 0)");
  }
  return $db;
}
function cab_token_create($email,$server_id,$ttl){
  $t=cab_make_token_value();
  $st=cab_db()->prepare("INSERT INTO tokens(token,email,server_id,expires,used) VALUES(?,?,?,?,0)");
  $st->execute([$t,$email,$server_id,time()+$ttl]);
  return $t;
}
function cab_token_consume($t){
  $db=cab_db();
  $st=$db->prepare("SELECT * FROM tokens WHERE token=?"); $st->execute([$t]);
  $r=$st->fetch(PDO::FETCH_ASSOC);
  if(!$r || $r['used'] || $r['expires']<time()) return null;
  $db->prepare("UPDATE tokens SET used=1 WHERE token=?")->execute([$t]);
  return $r;
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
// owner = ['email'=>?, 'server_id'=>?]; returns owner's servers with _req attached
function cab_owner_servers(){
  $o=$_SESSION['owner'];
  $servers=cab_list('custom_cc_servers');
  $reqs=cab_list('custom_cc_requests');
  $byServer=[];
  foreach($reqs as $r){ if(isset($r['custom_server_id'])) $byServer[(string)$r['custom_server_id']]=$r; }
  $out=[];
  foreach($servers as $s){
    $req=$byServer[(string)$s['id']]??[];
    $email=$req['custom_contact_email']??'';
    $match = (!empty($o['email']) && $email===$o['email'])
          || (!empty($o['server_id']) && (string)$s['id']===(string)$o['server_id']);
    if($match){ $s['_req']=$req; $out[]=$s; }
  }
  return $out;
}
