<?php

//Params should be empty
check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

// Check is the request body exists
check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

// Check the structure of the request body
check(!is_array_correct($data, ['confirm_id']), 200, false, API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()], null);

// Check characters, allowed only numbers
check(!ctype_digit($data['confirm_id']), 200, false, API_MESSAGES['CONFIRM_ID_TYPE_NOT_CORRECT'][get_language_code()], null);

// Check length of confirmID
check(strlen($data['confirm_id']) !== CONFIRM_EMAIL_ID_LENGTH, 200, false, API_MESSAGES['CONFIRM_ID_LENGTH_NOT_CORRECT'][get_language_code()], null);

// Find confirm_id in DB and confirm user
check(confirm_user($data['confirm_id']), 200, true, API_MESSAGES['USER_CONFIRMED'][get_language_code()], null);

// User not confirmed
check(null, 200, false, API_MESSAGES['CONFIRM_ID_IS_EXPIRED'][get_language_code()], null);


