<?php

require_once(dirname(__FILE__).'/../../../../test/bootstrap/unit.php');
require_once(sfConfig::get('sf_symfony_lib_dir').'/../test/unit/cache/sfCacheDriverTests.class.php');
//require_once(dirname(__FILE__).'/../../../lib/cache/sfMongoDBCache.class.php');

$plan = 61;
$t = new lime_test($plan, new lime_output_color());
$database = 'sf_test';

if (!extension_loaded('Mongo')) 
{
  $t->skip('Mongo extension not loaded, skipping tests', $plan);
  exit(0);
}

try
{
  new sfMongoDBCache(array('database' => $database));
}
catch (sfInitializationException $e)
{
  $t->skip($e->getMessage(), $plan);
  return;
}

// ->initialize()
$t->diag('->initialize()');
try
{
  $cache = new sfMongoDBCache();
  $t->fail('->initialize() throws an sfInitializationException exception if you don\'t pass a "database" parameter');
}
catch (sfInitializationException $e)
{
  $t->pass('->initialize() throws an sfInitializationException exception if you don\'t pass a "database" parameter');
}

$cache = new sfMongoDBCache(array('database' => $database));
sfCacheDriverTests::launch($t, $cache);
$cache->getBackend()->dropDB($database);
