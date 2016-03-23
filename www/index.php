<?php
define('ROOT_DIR', dirname(__FILE__));
require ROOT_DIR.'/include/S3Browser.php';

// Load config
$configFile = ROOT_DIR.'/config.php';
if (!file_exists($configFile)) {
  die('config.php is missing. See config-sample.php');
}
$config = include($configFile);

if (!$config['bucket-name'] || !$config['s3-access-key'] ||
    !$config['s3-secret-key']) {
  die('Please set bucket-name, s3-access-key, and s3-secret-key in'.
      ' config.php');
}
if (!$config['endPoint']){
    $config['endPoint'] = 's3.amazonaws.com';
}
if (!$config['endPoint-ssl']){
    $config['endPoint-ssl'] = false;
}

$s3b = new S3Browser($config['bucket-name'], $config['s3-access-key'],
                     $config['s3-secret-key'], $config['endPoint-ssl'], $config['endPoint']);
$s3b->enableCaching($config['cache-dir'], $config['cache-time']);

// Get current directory from URL
$dir = str_replace($config['base-path'], '', $_SERVER['REQUEST_URI']);
$dir = urldecode($dir);

$files = $s3b->getFiles($dir);
if ($files === null) {
  die('Unable to load bucket: '.$config['bucket-name']);
}

include ROOT_DIR.'/themes/'.$config['theme'].'/index.tpl.php';

?>
