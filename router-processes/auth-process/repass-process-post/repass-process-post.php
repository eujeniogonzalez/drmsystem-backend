<?php

  //Params should be empty
  check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

  // Check is the request body exists
  check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

  // Check the structure of the request body
  check(!is_array_correct($data, ['email']), 200, false, API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()], null);

  // Validating email
  check(!is_email_valid($data['email']), 200, false, API_MESSAGES['EMAIL_NOT_VALID'][get_language_code()], null);

  // Check length of email
  check(strlen($data['email']) > MAX_EMAIL_LENGTH, 200, false, API_MESSAGES['TOO_LONG_EMAIL'][get_language_code()], null);

  // Check is the email exists in DB
  check(!is_user_exists_by_email($data['email']), 200, false, API_MESSAGES['USER_NOT_REGISTERED'][get_language_code()], null);

  // Check confirmation of user
  check(!is_user_confirmed($data['email']), 200, false, API_MESSAGES['USER_NOT_CONFIRMED'][get_language_code()], null);

  // Remember password
  $repass_id = get_random_number(CONFIRM_REPASS_ID_LENGTH);
  $is_repass_started = start_user_repass($data['email'], $repass_id);
  check($is_repass_started, 200, true, API_MESSAGES['REPASS_STARTED'][get_language_code()], null);

  // Repass filed
  check(null, 200, false, API_MESSAGES['REPASS_NOT_STARTED'][get_language_code()], null);

