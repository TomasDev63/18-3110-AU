<?php
/**
 * Created by PhpStorm.
 * User: slim
 * Date: 24/10/17
 * Time: 10:01
 */

namespace M2M;

class CountryDetailsModel
{
    private $username;
    private $password;
    private $result;
    private $xml_parser;
    private $soap_wrapper;

    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->xml_parser = null;
        $this->username = '';
        $this->password = '';
        $this->result = [];
    }

    public function __destruct(){}

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function setParameters($cleaned_parameters)
    {
        $this->username = $cleaned_parameters['username'];
        $this->password = $cleaned_parameters['password'];
    }


    public function getResult()
    {
        return $this->result;
    }

    public function retrieveMessages()
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false)
        {
            $webservice_function = 'peekMessages';

            $username = $this->username;
            $password = $this->password;
            $message_count = 10;
            $device_msisdn = '';
            $country_code = 44;

            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $username, $password, $message_count, $device_msisdn, $country_code);

            $this->result = $soapcall_result;
        }
    }
}