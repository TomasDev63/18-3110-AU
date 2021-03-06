<?php
/**
 * Created by PhpStorm.
 * User: slim
 * Date: 13/10/17
 * Time: 12:33
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'M2M.%t');

define('DIRSEP', DIRECTORY_SEPARATOR);

$url_root = $_SERVER['SCRIPT_NAME'];
$url_root = implode('/', explode('/', $url_root, -1));
$css_path = $url_root . '/css/standard.css';
define('CSS_PATH', $css_path);
define('APP_NAME', 'M2M');
define('LANDING_PAGE', $_SERVER['SCRIPT_NAME']);

$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?WSDL';
define('WSDL', $wsdl);

$logs_file_path = '/p3t/phpappfolder/logs/';
define('LOGS_FILE_PATH', $logs_file_path);

$logs_file_name = 'M2MLogs.log';
define('LOGS_FILE_NAME', $logs_file_name);

$settings = [
  "settings" => [
    'displayErrorDetails' => true,
    'addContentLengthHeader' => false,
    'mode' => 'development',
    'debug' => true,
    'class_path' => __DIR__ . '/src/',
    'view' => [
      'template_path' => __DIR__ . '/templates/',
      'twig' => [
        'cache' => false,
        'auto_reload' => true,
      ]],
  ],
];

return $settings;
