<?php return array (
  'test' => false,
  'require_authentication' => true,
  'registration' => true,
  'allow_user_registration' => true,
  'jobKey' => 98,
  'scrapers' => 
  array (
    0 => 'App\\Scrapers\\LocalScraper',
    1 => 'App\\Scrapers\\NameScraper',
    2 => 'App\\Scrapers\\LastHope',
  ),
  'comics_dir' => '/home/riccardo/comics',
  'use_online_data' => true,
  'admin_credentials' => 
  array (
    'username' => 'admin',
    'password' => 'adminadmin',
  ),
  'scan_frequency' => 
  array (
    'value' => 'monthly',
    'values' => 
    array (
      0 => 'monthly',
      1 => 'weekly',
      2 => 'daily',
    ),
  ),
  'security' => 
  array (
    'require_authentication' => 
    array (
      'value' => false,
      'description' => 'è richiesta l\'autenticazione',
    ),
    'registration' => 
    array (
      'value' => true,
      'description' => 'permette la regisrtazione di nuovi utenti',
    ),
    'allow_user_registration' => 
    array (
      'value' => false,
      'description' => 'permette la registrazione da parte di utenti esterti',
    ),
    'default_rule' => 
    array (
      'value' => true,
      'description' => 'comportamento in caso di assenza di regola specifica per una certa risorsa',
    ),
    'recursive_rule' => 
    array (
      'value' => true,
      'secription' => 'in assenza di specifiche rules per oggetto, cerca rules sugli oggetti che lo contengono',
    ),
  ),
);