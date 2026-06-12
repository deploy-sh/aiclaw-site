<?php
// Mint a one-time magic-link. Called by the CLIENT bot with {key, secret}.
require __DIR__.'/lib.php';
header('Content-Type: application/json; charset=utf-8');
$cfg=cab_cfg();
$in=json_decode(file_get_contents('php://input'),true);
if(!is_array($in)) $in=$_POST;
if(($in['secret']??'')!==$cfg['issue_secret']){ http_response_code(403); echo json_encode(['ok'=>false,'error'=>'forbidden']); exit; }
$key=trim($in['key']??'');
if($key===''){ http_response_code(400); echo json_encode(['ok'=>false,'error'=>'no key']); exit; }
$srv=null;
foreach(cab_list('custom_cc_servers') as $s){ if(($s['custom_key']??'')===$key){ $srv=$s; break; } }
if(!$srv){ http_response_code(404); echo json_encode(['ok'=>false,'error'=>'server not found']); exit; }
$email='';
foreach(cab_list('custom_cc_requests') as $r){ if((string)($r['custom_server_id']??'')===(string)$srv['id']){ $email=$r['custom_contact_email']??''; break; } }
// optional longer-lived, reusable link for embedding in billing messages
$reuse = !empty($in['reuse']);
$ttl = isset($in['ttl']) ? max(60, min((int)$in['ttl'], 2592000)) : $cfg['token_ttl']; // cap 30d
$t=cab_token_create($email,$srv['id'],$ttl,$reuse);
echo json_encode(['ok'=>true,'url'=>$cfg['base_url'].'/cabinet/login.php?t='.$t]);
