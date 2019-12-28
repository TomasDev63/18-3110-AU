<?php
/**
 * homepage.php
 *
 * display the check primes application homepage
 *
 * allows the user to enter a value for testing if prime
 *
 * Author: CF Ingrams
 * Email: <cfi@dmu.ac.uk>
 * Date: 18/10/2015
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/processlogin', function(Request $request, Response $response) use ($app)
{
    $tainted_parameters = $request->getParsedBody();
    $cleaned_parameters = cleanupParameters($app, $tainted_parameters);
    $messages = getMessages($app, $cleaned_parameters);

    if (is_soap_fault($messages))
    {
        $html_output = $this->view->render($response,
            'login.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'login_method' => 'post',
                'login_action' => 'processlogin',
                'help_method' => 'get',
                'help_action' => 'help',
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Log in to your account',
                'error_message' => 'Incorrect Username or Password entered.',
            ]);
    }

    else
    {
        $validated_messages = validateDownloadedData($app, $messages);
//  var_dump($validated_messages);

        $html_output = $this->view->render($response,
            'display_result.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Log in to your account',
                'messages' => $messages
            ]);

    }

    $processed_output = processOutput($app, $html_output);

    return $processed_output;
});

function cleanupParameters($app, $tainted_parameters)
{
    $cleaned_parameters = [];
    $validated_country_code = false;
    $validated_detail = false;

    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_parameters['username']))
    {
        $tainted_username = $tainted_parameters['username'];
//        $validated_country_code = $validator->validateCountryCode($tainted_country);
    }
    if (isset($tainted_parameters['password']))
    {
        $tainted_password = $tainted_parameters['password'];
//        $validated_detail = $validator->validateDetailType($tainted_detail);
    }

    if (($tainted_username != false) && ($tainted_password != false))
    {
        $cleaned_parameters['username'] = $tainted_username;
        $cleaned_parameters['password'] = $tainted_password;
    }

    return $cleaned_parameters;
}

function validateDownloadedData($app, $tainted_data)
{
    $cleaned_data = '';

    if (is_string($tainted_data) == true)
    {
        $validator = $app->getContainer()->get('validator');
        $cleaned_data = $validator->validateDownloadedData($tainted_data);
    }
    else
    {
        $cleaned_data = $tainted_data;
    }

    return $cleaned_data;
}

function getMessages($app, $cleaned_parameters)
{
//    $message_detail_result = [];
    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    $messagedetails_model = $app->getContainer()->get('messageDetailsModel');
    $messagedetails_model->setSoapWrapper($soap_wrapper);

    $messagedetails_model->setParameters($cleaned_parameters);
    $messagedetails_model->retrieveMessages();
    $message_detail_result = $messagedetails_model->getResult();

    //   var_dump($message_detail_result);
    return $message_detail_result;
}