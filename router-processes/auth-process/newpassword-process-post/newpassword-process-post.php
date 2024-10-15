<?php

  //Params should be empty
  check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

  // Check is the request body exists
  check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

  // Check the structure of the request body
  check(!is_array_correct($data, ['repass_id', 'new_password', 'new_repeat_password']), 200, false, API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()], null);
  
  // Check characters, allowed only numbers
  check(!ctype_digit($data['repass_id']), 200, false, API_MESSAGES['REPASS_ID_TYPE_NOT_CORRECT'][get_language_code()], null);

  // Check length of repass_id
  check(strlen($data['repass_id']) !== CONFIRM_REPASS_ID_LENGTH, 200, false, API_MESSAGES['CONFIRM_ID_LENGTH_NOT_CORRECT'][get_language_code()], null);

  // Check that the password contains the minimum number of characters
  check(strlen($data['new_password']) < MIN_PASSWORD_LENGTH, 200, false, API_MESSAGES['TOO_SHORT_PASSWORD'][get_language_code()], null);

  // Check that the password contains no more than the maximum number of characters
  check(strlen($data['new_password']) > MAX_PASSWORD_LENGTH, 200, false, API_MESSAGES['TOO_LONG_PASSWORD'][get_language_code()], null);

  // Check passwords match
  check($data['new_password'] !== $data['new_repeat_password'], 200, false, API_MESSAGES['PASSWORDS_NOT_MATCH'][get_language_code()], null);

  // Find repass_id in DB and set new password
  check(set_new_password($data['repass_id'], $data['new_password']), 200, true, API_MESSAGES['PASSWORD_CHANGED'][get_language_code()], null);

  // Password not changed
  check(null, 200, false, API_MESSAGES['PASSWORD_NOT_CHANGED'][get_language_code()], null);

