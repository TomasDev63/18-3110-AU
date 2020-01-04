<?php
/**
 * processlogin.php
 *
 * Display the messages page
 * Allows the user to view messages
 *
 * Author: Tomas Tarapavicius
 * Date: 26/12/2019
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/processlogin', function(Request $request, Response $response) use ($app)
{
    // Get the details
    $tainted_details = $request->getParsedBody();

    // Check the details
    $cleaned_details = cleanupDetails($app, $tainted_details);

    // Try to get the messages
    $messages = getMessages($app, $cleaned_details);

    // If there is a problem with the details or the connection
    if (is_soap_fault($messages))
    {
        // Show the login page with an error
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
                'error_message' => 'Incorrect Username or Password entered.',
            ]);
    }

    // If there are no problems connecting
    else
    {
        // Show the result page
        $html_output = $this->view->render($response,
            'display_result.html.twig',
            [
                'css_path' => CSS_PATH,
                'landing_page' => LANDING_PAGE,
                'page_title' => APP_NAME,
                'page_heading_1' => APP_NAME,
                'page_heading_2' => 'Log in to your account',
                'messages' => $messages
            ]);

    }

    $processed_output = processOutput($app, $html_output);

    return $processed_output;
});

function cleanupDetails($app, $tainted_details)
{
    $cleaned_details = [];
    $validated_username = false;
    $validated_password = false;

    // Get the container for validation
    $validator = $app->getContainer()->get('validator');

    if (isset($tainted_details['username']))
    {
        $tainted_username = $tainted_details['username'];
        // Validate Username
        $validated_username = $validator->validateUsername($tainted_username);
    }
    if (isset($tainted_details['password']))
    {
        $tainted_password = $tainted_details['password'];
        // Validate Password
        $validated_password = $validator->validatePassword($tainted_password);
    }

    // If details are valid make them available
    if (($validated_username != false) && ($validated_password != false))
    {
        $cleaned_details['username'] = $validated_username;
        $cleaned_details['password'] = $validated_password;
    }

    // Return the details
    return $cleaned_details;
}

function getMessages($app, $cleaned_details)
{
    $message_detail_result = [];

    // Get the container for Soap
    $soap_wrapper = $app->getContainer()->get('soapWrapper');

    // Get the container for messageDetailsModel
    $messagedetails_model = $app->getContainer()->get('messageDetailsModel');

    // Set the SoapWrapper and Details
    $messagedetails_model->setSoapWrapper($soap_wrapper);
    $messagedetails_model->setDetails($cleaned_details);

    // Retrieve the messages
    $messagedetails_model->retrieveMessages();
    // Get the result
    $message_detail_result = $messagedetails_model->getResult();

    // Return messages
    return $message_detail_result;
}