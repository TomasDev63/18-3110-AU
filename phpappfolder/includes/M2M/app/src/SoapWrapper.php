<?php

namespace M2M;

class SoapWrapper
{

    public function __construct(){}
    public function __destruct(){}

    public function createSoapClient()
    {
        $soap_client_handle = false;
        $soap_client_parameters = array();
        $exception = '';
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try
        {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
//            var_dump($soap_client_handle->__getFunctions());
//            var_dump($soap_client_handle->__getTypes());
        }
        catch (\SoapFault $exception)
        {
            $soap_client_handle = 'Ooops - something went wrong when connecting to the data supplier.  Please try again later';
        }
        return $soap_client_handle;
    }

    public function performSoapCall($soap_client, $webservice_function, $username, $password, $message_count, $device_msisdn, $country_code)
    {
        $soap_call_result = null;
        $raw_xml = '';

        if ($soap_client)
        {
            try
            {
                $webservice_call_result = $soap_client->{$webservice_function}($username, $password, $message_count, $device_msisdn, $country_code);
            }
            catch (\SoapFault $exception)
            {
                $webservice_call_result = $exception;
            }
        }
        return $webservice_call_result;
    }
}