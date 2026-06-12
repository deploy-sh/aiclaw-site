<?php
// SAMPLE config for the aiclaw billing cabinet.
// Real file lives OUTSIDE webroot on host: /home/korfix/web/aiclaw.korfix.app/cabinet-config.php
// chmod 600. Never commit real secrets.
return [
  'korfix_base'  => 'https://vibe.korfix.app/api/db',
  'korfix_token' => 'PUT_KORFIX_BEARER_TOKEN',     // ac82265c... — on host only
  'issue_secret' => 'PUT_LONG_RANDOM_SECRET',       // shared with client bots (issue.php)
  'yk_shop'      => '1371190',
  'yk_key'       => 'PUT_YOOKASSA_LIVE_KEY',
  'tokens_dir'   => '/home/korfix/web/aiclaw.korfix.app/cabinet-tokens',  // dir OUTSIDE webroot
  'token_ttl'    => 900,    // magic-link TTL, seconds (15 min)
  'session_ttl'  => 1800,   // idle session TTL, seconds (30 min)
  'base_url'     => 'https://aiclaw.korfix.app',
  'brand'        => 'aiclaw',
  'legal' => [
    'name'    => 'ООО «Платформа СтаффХаб»',  // TODO: rename pending — edit here only
    'inn'     => 'TBD',
    'ogrn'    => 'TBD',
    'address' => 'TBD',
    'phone'   => 'TBD',
    'email'   => 'billing@korfix.app',
  ],
];
