<?php
/**
 * Created by PhpStorm.
 * User: slim
 * Date: 24/10/17
 * Time: 10:01
 */

namespace M2M;

use mysql_xdevapi\Exception;

class MessageDetailsModel
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

    public function __destruct()
    {
    }

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function setParameters($cleaned_parameters)
    {
        if ($cleaned_parameters != null)
        {
            $this->username = $cleaned_parameters['username'];
            $this->password = $cleaned_parameters['password'];
        }
    }


    public function getResult()
    {
        return $this->result;
    }

    // Checks if string ends with a specific sub-string
    private function endsWith($currentString, $target)
    {
        $length = strlen($target);
        if ($length == 0) {
            return true;
        }

        return (substr($currentString, -$length) === $target);
    }

    private function getValidControls($validated_message)
    {
        $validator = new Validator();

        // Switch 1
        preg_match('#switch_1:([^\s]+)#', $validated_message, $switch_1);
        if (isset($switch_1[1])) {
            $switch_1_valid = $validator->validateSwitch($switch_1[1]);
            {
                if ($switch_1_valid == true) {
                    $switch_1[1] = str_replace(';', '', $switch_1[1]);
                    $controls['SWITCH_1'] = $switch_1[1];
                } else {
                    $controls['SWITCH_1'] = null;
                }
            }
        } else {
            $controls['SWITCH_1'] = null;
        }

        // Switch 2
        preg_match('#switch_2:([^\s]+)#', $validated_message, $switch_2);
        if (isset($switch_2[1])) {
            $switch_2_valid = $validator->validateSwitch($switch_2[1]);
            {
                if ($switch_2_valid == true) {
                    $switch_2[1] = str_replace(';', '', $switch_2[1]);
                    $controls['SWITCH_2'] = $switch_2[1];
                } else {
                    $controls['SWITCH_2'] = null;
                }
            }
        } else {
            $controls['SWITCH_2'] = null;
        }

        // Switch 3
        preg_match('#switch_3:([^\s]+)#', $validated_message, $switch_3);
        if (isset($switch_3[1])) {
            $switch_3_valid = $validator->validateSwitch($switch_3[1]);
            {
                if ($switch_3_valid == true) {
                    $switch_3[1] = str_replace(';', '', $switch_3[1]);
                    $controls['SWITCH_3'] = $switch_3[1];
                } else {
                    $controls['SWITCH_3'] = null;
                }
            }
        } else {
            $controls['SWITCH_3'] = null;
        }

        // Switch 4
        preg_match('#switch_4:([^\s]+)#', $validated_message, $switch_4);
        if (isset($switch_4[1])) {
            $switch_4_valid = $validator->validateSwitch($switch_4[1]);
            {
                if ($switch_4_valid == true) {
                    $switch_4[1] = str_replace(';', '', $switch_4[1]);
                    $controls['SWITCH_4'] = $switch_4[1];
                } else {
                    $controls['SWITCH_4'] = null;
                }
            }
        } else {
            $controls['SWITCH_4'] = null;
        }

        // Fan
        preg_match('#fan:([^\s]+)#', $validated_message, $fan);
        if (isset($fan[1])) {
            $fan_valid = $validator->validateFan($fan[1]);
            {
                if ($fan_valid == true) {
                    $fan[1] = str_replace(';', '', $fan[1]);
                    $controls['FAN'] = $fan[1];
                } else {
                    $controls['FAN'] = null;
                }
            }
        } else {
            $controls['FAN'] = null;
        }

        // Heater
        preg_match('#heater:([^\s]+)#', $validated_message, $heater);
        if (isset($heater[1])) {
            $heater_valid = $validator->validateHeater($heater[1]);
            {
                if ($heater_valid != false) {
                    $controls['HEATER'] = $heater_valid;
                } else {
                    $controls['HEATER'] = null;
                }
            }
        } else {
            $controls['HEATER'] = null;
        }

        // Keypad
        preg_match('#keypad:([^\s]+)#', $validated_message, $keypad);
        if (isset($keypad[1])) {
            $keypad_valid = $validator->validateKeypad($keypad[1]);
            {
                if ($keypad_valid != false) {
                    $controls['KEYPAD'] = $keypad_valid;
                } else {
                    $controls['KEYPAD'] = null;
                }
            }
        } else {
            $controls['KEYPAD'] = null;
        }

        return ($controls);
    }

    // Controls format example: {switch_1:true; switch_2:false; switch_3:false; switch_4:true; fan:true; heater:20; keypad:5; id:18-3110-AU}
    public function retrieveMessages()
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false) {
            $webservice_function = 'peekMessages';

            $username = $this->username;
            $password = $this->password;
            $message_count = '';
            $device_msisdn = '';
            $country_code = 44;

            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $username, $password, $message_count, $device_msisdn, $country_code);

            foreach ($soapcall_result as $message) {
                // Parse message
                $xml_parser = new XmlParser();
                $xml_parser->setXmlStringToParse($message);
                $xml_parser->parseTheXmlString();
                $parsed_messages = $xml_parser->getParsedData();

                // Validate Message
                $validator = new Validator();
                $validator->setMessageToValidate($parsed_messages);
                $validated_source_msisdn = $validator->validateSourceMSISDN();
                $validated_messages['SOURCEMSISDN'] = $validated_source_msisdn;
                $validated_destination_msisdn = $validator->validateDestinationMSISDN();
                $validated_messages['DESTINATIONMSISDN'] = $validated_destination_msisdn;
                $validated_received_time = $validator->validateReceivedTime();
                $validated_messages['RECEIVEDTIME'] = $validated_received_time;
                $validated_bearer = $validator->validateBearer();
                $validated_messages['BEARER'] = $validated_bearer;
                $validated_message_ref = $validator->validateMessageRef();
                $validated_messages['MESSAGEREF'] = $validated_message_ref;
                $validated_message = $validator->validateMessage();
                $validated_messages['MESSAGE'] = $validated_message;

                // Messages only for 18-3110-AU
                if ($this->endsWith($validated_message, "id:18-3110-AU}")) {
                    $controls = $this->getValidControls($validated_message);

                    // Add validated controls and the rest of the message together
                    $result = array_merge($validated_messages, $controls);
                    var_dump($result);
                }
            }
        }
        // Temporary (Database)
        $this->result = $soapcall_result;
    }
}