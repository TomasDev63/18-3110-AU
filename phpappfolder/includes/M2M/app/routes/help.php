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

$app->get('/help', function(Request $request, Response $response) use ($app)
{
    $html_output = $this->view->render($response,
    'help.html.twig',
    [
      'css_path' => CSS_PATH,
      'landing_page' => LANDING_PAGE,
      'method' => 'post',
      'action' => 'help',
      'initial_input_box_value' => null,
      'page_title' => APP_NAME,
      'page_heading_1' => APP_NAME,
      'page_heading_2' => 'Help Page',
    ]);

    $processed_output = processOutput($app, $html_output);

    return $processed_output;

})->setName('help');