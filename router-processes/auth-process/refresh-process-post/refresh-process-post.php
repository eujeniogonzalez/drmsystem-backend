<?php

//Params should be empty
check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

// Check is the request body exists
check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

// Check the structure of the request body
check(!is_array_correct($data, ['refresh_token']), 200, false, API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()], null);

//Is refresh token expired
check(is_refresh_token_expired($data['refresh_token']), 200, false, API_MESSAGES['REFRESH_TOKEN_EXPIRED'][get_language_code()], null);

// Update session and return new tokens
$session = update_session_by_refresh($data['refresh_token']);
$tokens = ['access_token' => $session['tokens']['access']];
if (is_client_domain_localhost()) {
  $tokens['refresh_token'] = $session['tokens']['refresh'];
}
check($session, 200, true, API_MESSAGES['SESSION_UPDATED'][get_language_code()], $tokens);

// Session not updated
check(null, 200, false, API_MESSAGES['SESSION_NOT_UPDATED'][get_language_code()], null);

