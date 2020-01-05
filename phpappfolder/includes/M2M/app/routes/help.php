<?php
/**
 * help.php
 *
 * Display the help page
 * Shows user the help page
 *
 * Author: Tomas Tarapavicius
 * Date: 28/12/2019
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require 'vendor/autoload.php';

$app->get('/help', function(Request $request, Response $response) use ($app)
{
    // Name and path can be changed in settings
    $logs_file_path = LOGS_FILE_PATH;
    $logs_file_name = LOGS_FILE_NAME;
    $logs_file = $logs_file_path . $logs_file_name;

    // Monolog
    $log = new Logger('logger');
    $log->pushHandler(new StreamHandler($logs_file, Logger::INFO));

    // Add to the log
    $log->info('Showing the help page');

    $html_output = $this->view->render($response,
    'help.html.twig',
    [
      'css_path' => CSS_PATH,
      'landing_page' => LANDING_PAGE,
      'method' => 'post',
      'action' => 'help',
      'page_title' => APP_NAME,
      'page_heading_1' => APP_NAME,
      'page_heading_2' => 'Help Page',
    ]);

    $processed_output = processOutput($app, $html_output);

    return $processed_output;

})->setName('help');