<?php
/**
 * login.php
 *
 * Display the login page
 * Allows the user to enter their username and password
 *
 * Author: Tomas Tarapavicius
 * Date: 26/12/2019
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require 'vendor/autoload.php';

$app->get('/', function(Request $request, Response $response) use ($app)
{
    // Name and path can be changed in settings
    $logs_file_path = LOGS_FILE_PATH;
    $logs_file_name = LOGS_FILE_NAME;
    $logs_file = $logs_file_path . $logs_file_name;

    // Monolog
    $log = new Logger('logger');
    $log->pushHandler(new StreamHandler($logs_file, Logger::INFO));

    // Add to the log
    $log->info("Showing the login page");

    $html_output = $this->view->render($response,
        'login.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => LANDING_PAGE,
            'login_method' => 'post',
            'login_action' => 'processlogin',
            'help_method' => 'get',
            'help_action' => 'help',
            'page_title' => APP_NAME,
            'page_heading_1' => APP_NAME,
            'page_heading_2' => 'Log in to your account',
        ]);

    $processed_output = processOutput($app, $html_output);

    return $processed_output;

})->setName('login');

function processOutput($app, $html_output)
{
    $process_output = $app->getContainer()->get('processOutput');
    $html_output = $process_output->processOutput($html_output);
    return $html_output;
}