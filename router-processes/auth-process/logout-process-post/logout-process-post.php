<?php

//Params should be empty
check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

// Check is the request body exists
check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

// Check the structure of the request body
check(!is_array_correct($data, ['refresh_token']), 200, false, API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()], null);

// Try to logout
$isSessionDropped = drop_session($data['refresh_token']);
check($isSessionDropped, 200, true, API_MESSAGES['USER_LOGGED_OUT'][get_language_code()], null);

// User not logged out
check(null, 200, false, API_MESSAGES['USER_ALREADY_LOGGED_OUT'][get_language_code()], null);
