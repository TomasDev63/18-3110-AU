<?php

namespace M2M;

use mysql_xdevapi\Exception;

class Validator
{
    private $message;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function setMessageToValidate($message_to_validate)
    {
        $this->message = $message_to_validate;
    }

    private function getValue($field)
    {
        if (!isset($this->message[$field])) {
            $this->message[$field] = 'Incorrect format.';
        }

        return $this->message[$field];
    }

    public function validateCountryCode($country_code_to_check)
    {
        $checked_country = false;
        if (isset($country_code_to_check)) {
            if (!empty($country_code_to_check)) {
                if (strlen($country_code_to_check) == 2) {
                    $checked_country = $country_code_to_check;
                }
            } else {
                $checked_country = 'none selected';
            }
        }
        return $checked_country;
    }

    public function validateSourceMSISDN()
    {
        $field = 'SOURCEMSISDN';
        $source_msidn = $this->getValue($field);

        $checked_MSISDN = false;
        if (isset($source_msidn)) {
            if (!empty($source_msidn)) {
                if (strlen($source_msidn) == 12) {
                    $checked_MSISDN = filter_var($source_msidn, FILTER_SANITIZE_NUMBER_INT);
                } else {
                    $checked_MSISDN = 'Incorrect source MSISDN.';
                }
            } else {
                $checked_MSISDN = 'No source MSISDN.';
            }
        }
        return $checked_MSISDN;
    }

    public function validateDestinationMSISDN()
    {
        $field = 'DESTINATIONMSISDN';
        $destination_msidn = $this->getValue($field);

        $checked_MSISDN = false;
        if (isset($destination_msidn)) {
            if (!empty($destination_msidn)) {
                if (strlen($destination_msidn) == 12) {
                    $checked_MSISDN = filter_var($destination_msidn, FILTER_SANITIZE_NUMBER_INT);
                } else {
                    $checked_MSISDN = 'Incorrect destination MSISDN.';
                }
            } else {
                $checked_MSISDN = 'No destination MSISDN.';
            }
        }
        return $checked_MSISDN;
    }

    public function validateReceivedTime()
    {
        $field = 'RECEIVEDTIME';
        $received_time = $this->getValue($field);

        $checked_time = false;
        if (isset($received_time)) {
            if (!empty($received_time)) {
                if (strlen($received_time) == 19) {
                    $checked_time = $received_time;
                } else {
                    $checked_time = 'Incorrect received time.';
                }
            } else {
                $checked_time = 'No received time.';
            }
        }
        return $checked_time;
    }

    public function validateBearer()
    {
        $field = 'BEARER';
        $bearer = $this->getValue($field);

        $checked_bearer = false;
        if (isset($bearer)) {
            if (!empty($bearer)) {
                if (strlen($bearer) == 3) {
                    $checked_bearer = filter_var($bearer, FILTER_SANITIZE_STRING);
                } else {
                    $checked_bearer = 'Incorrect bearer.';
                }
            } else {
                $checked_bearer = 'No bearer.';
            }
        }
        return $checked_bearer;
    }

    public function validateMessageRef()
    {
        $field = 'MESSAGEREF';
        $message_ref = $this->getValue($field);

        $checked_message_ref = false;
        if (isset($message_ref)) {
            $checked_message_ref = filter_var($message_ref, FILTER_SANITIZE_NUMBER_INT);
        }
        return $checked_message_ref;
    }

    public function validateMessage()
    {
        $field = 'MESSAGE';
        $checked_message = false;

        $received_message = $this->getValue($field);

        if (isset($received_message)) {
            if (!empty($received_message)) {
                $checked_message = $received_message;
            } else {
                $checked_message = 'No message.';
            }
        }

        return $checked_message;
    }

    public function validateSwitch($switch_status)
    {
        $checked_switch = false;

        if (isset($switch_status)) {
            if ($switch_status == 'true;' || $switch_status == 'false;') {
                $checked_switch = true;
            }
        }

        return $checked_switch;
    }

    public function validateFan($fan_status)
    {
        $checked_fan = false;

        if (isset($fan_status)) {
            if ($fan_status == 'true;' || $fan_status == 'false;') {
                $checked_fan = true;
            }
        }

        return $checked_fan;
    }

    public function validateHeater($heater_status)
    {
        $checked_heater = false;

        if (isset($heater_status)) {
            $heater_status = str_replace(';', '', $heater_status);
            $checked_heater = filter_var($heater_status, FILTER_SANITIZE_NUMBER_FLOAT);
        }

        return $checked_heater;
    }

    public function validateKeypad($keypad_status)
    {
        $checked_keypad = false;

        if (isset($keypad_status)) {
            if (strlen($keypad_status) == 2) {
                $keypad_status = str_replace(';', '', $keypad_status);
                $checked_keypad = filter_var($keypad_status, FILTER_SANITIZE_NUMBER_INT);
            }
        }

        return $checked_keypad;
    }

    public function validateDetailType($type_to_check)
    {
        $checked_detail_type = false;
        $detail_types = DETAIL_TYPES;

        if (in_array($type_to_check, $detail_types) === true) {
            $checked_detail_type = $type_to_check;
        }

        return $checked_detail_type;
    }

    public function validateDownloadedData($tainted_data)
    {
        $validated_string_data = '';

        $validated_string_data = filter_var($tainted_data, FILTER_SANITIZE_STRING);

        return $validated_string_data;
    }
}