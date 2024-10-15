<?php

//Params should be empty
check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

// Check is the request body exists
check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

// Check the structure of the request body
check(!is_array_correct($data, ['email', 'password']), 200, false, API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()], null);

// Validating email
check(!is_email_valid($data['email']), 200, false, API_MESSAGES['EMAIL_NOT_VALID'][get_language_code()], null);

// Check length of email
check(strlen($data['email']) > MAX_EMAIL_LENGTH, 200, false, API_MESSAGES['TOO_LONG_EMAIL'][get_language_code()], null);

// Check password contains one or more characters
check(strlen($data['password']) < MIN_PASSWORD_LENGTH, 200, false, API_MESSAGES['TOO_SHORT_PASSWORD'][get_language_code()], null);

// Check is user registered
check(!is_user_exists_by_email($data['email']), 200, false, API_MESSAGES['USER_NOT_REGISTERED'][get_language_code()], null);

// Check confirmation of user
check(!is_user_confirmed($data['email']), 200, false, API_MESSAGES['USER_NOT_CONFIRMED'][get_language_code()], null);

// Check password
check(!is_password_correct($data['email'], $data['password']), 200, false, API_MESSAGES['PASSWORD_NOT_CORRECT'][get_language_code()], null);

// Try to login
$session = create_session($data['email']);
$tokens = ['access_token' => $session['tokens']['access']];
if (is_client_domain_localhost()) {
  $tokens['refresh_token'] = $session['tokens']['refresh'];
}
check($session, 200, true, API_MESSAGES['USER_IS_LOGGED_IN'][get_language_code()], $tokens);

// User not logged in
check(null, 200, false, API_MESSAGES['USER_IS_NOT_LOGGED_IN'][get_language_code()], null);


