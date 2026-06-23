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
  'users_dir'    => '/home/korfix/web/aiclaw.korfix.app/private/cabinet-users',  // bcrypt password files
  'tokens_dir'   => '/home/korfix/web/aiclaw.korfix.app/private/cabinet-tokens',  // in open_basedir, not web-served
  'token_ttl'    => 900,    // magic-link TTL, seconds (15 min)
  'session_ttl'  => 1800,   // idle session TTL, seconds (30 min)
  'base_url'     => 'https://aiclaw.korfix.app',
  'brand'        => 'aiclaw',
  'vat_default'  => 0,     // НДС по умолчанию (0 = Без НДС). Если поменяется — НДС начислением (сверху).
  'legal' => [
    'name'    => 'ООО «Платформа СтаффХаб»',  // TODO: rename pending — edit here only
    'inn'     => '5260498299',
    'kpp'     => '526001001',
    'ogrn'    => '1245200033286',
    'okpo'    => '73034946',
    'address' => '603000, г. Нижний Новгород, ул. Пискунова, д. 29, помещ. П33',
    'phone'   => 'TBD',
    'email'   => 'billing@korfix.app',
    'director'=> 'Лядков Андрей Дмитриевич',
  ],
  'bank' => [
    'name' => 'Волго-Вятский Банк ПАО СБЕРБАНК',
    'bik'  => '042202603',
    'rs'   => '40702810542000112217',
    'ks'   => '30101810900000000603',
  ],
];
