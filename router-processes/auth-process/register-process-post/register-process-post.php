<?php

  //Params should be empty
  check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

  // Check is the request body exists
  check(empty($data), 200, false, API_MESSAGES['REQUEST_BODY_EMPTY'][get_language_code()], null);

  // Check the structure of the request body
  check(
    !is_array_correct(
      $data,
      ['first_name', 'last_name', 'email', 'password', 'repeat_password']
    ),
    200,
    false,
    API_MESSAGES['REQUEST_BODY_IS_WRONG'][get_language_code()],
    null
  );

  // Validating first name
  check(!is_first_last_name_valid($data['first_name']), 200, false, API_MESSAGES['FIRST_NAME_NOT_VALID'][get_language_code()], null);

  // Validating last name
  check(!is_first_last_name_valid($data['last_name']), 200, false, API_MESSAGES['LAST_NAME_NOT_VALID'][get_language_code()], null);

  // Check first name length
  check(strlen($data['first_name']) > MAX_LAST_NAME_LENGTH, 200, false, API_MESSAGES['TOO_LONG_FIRST_NAME'][get_language_code()], null);

  // Check last name length
  check(strlen($data['last_name']) > MAX_LAST_NAME_LENGTH, 200, false, API_MESSAGES['TOO_LONG_LAST_NAME'][get_language_code()], null);

  // Validating email
  check(!is_email_valid($data['email']), 200, false, API_MESSAGES['EMAIL_NOT_VALID'][get_language_code()], null);

  // Check length of email
  check(strlen($data['email']) > MAX_EMAIL_LENGTH, 200, false, API_MESSAGES['TOO_LONG_EMAIL'][get_language_code()], null);

  // Check password contains one or more characters
  check(strlen($data['password']) < MIN_PASSWORD_LENGTH, 200, false, API_MESSAGES['TOO_SHORT_PASSWORD'][get_language_code()], null);

  // Check passwords match
  check($data['password'] !== $data['repeat_password'], 200, false, API_MESSAGES['PASSWORDS_NOT_MATCH'][get_language_code()], null);

  // Check is the email already exists in DB
  check(is_user_exists_by_email($data['email']), 200, false, API_MESSAGES['USER_ALREADY_EXIST'][get_language_code()], null);

  // Creatin new user
  $confirm_id = get_random_number(CONFIRM_EMAIL_ID_LENGTH);
  $isUserCreated = create_user($data['first_name'], $data['last_name'], $data['email'], $data['password'], $confirm_id);
  check($isUserCreated, 200, true, API_MESSAGES['USER_CREATED'][get_language_code()], null);
  
  // User not created
  check(null, 200, false, API_MESSAGES['USER_NOT_CREATED'][get_language_code()], null);