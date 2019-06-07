<?php

namespace Slixa\Validator;

class FormFieldValidator {
    const PASSWORD_MINIMUM_LENGHT = 8;
    const ERROR_MESSAGE_VALID_PASSWORD = "Password should be at least " . self::PASSWORD_MINIMUM_LENGHT . " characters, contain at least one number, contain at least one lowercase letter, contain at least one uppercase letter, and contain at least one special character.";
    const ERROR_MESSAGE_VALID_EMAIL = "Email should be a valid email address.";
    const ERROR_MESSAGE_DUPLICATED_EMAIL = "Email address is already registered, do not change it, choose another one or login with it.";

    function validEmail($email) {
        return !!filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function validPassword($password) {
        if (is_string($password) && strlen($password) >= $this::PASSWORD_MINIMUM_LENGHT ) {
            $cleanPassword = $result = preg_replace("/[^a-zA-Z0-9!@#$%^&*(),.?\":{}|<>]+/", "", $password);
            $containsSpecial = preg_match('/[^a-zA-Z0-9]/', $cleanPassword);
            if (($password === $cleanPassword) && ($containsSpecial)) {
                return true;
            }
        }
        return false;
    }
}