<?php

  //Params should be empty
  check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

  //Coockie not found
  check(!$_COOKIE || !$_COOKIE[RefreshCoockie::NAME], 200, false, API_MESSAGES['COOCKIE_NOT_FOUND'][get_language_code()], null);

  //Is refresh token expired
  check(is_refresh_token_expired($_COOKIE[RefreshCoockie::NAME]), 200, false, API_MESSAGES['REFRESH_TOKEN_EXPIRED'][get_language_code()], null);

  // Update session and return new tokens
  $session = update_session_by_refresh($_COOKIE[RefreshCoockie::NAME]);
  check($session, 200, true, API_MESSAGES['SESSION_UPDATED'][get_language_code()], ['access_token' => $session['tokens']['access']]);
  
  // Session not updated
  check(null, 200, false, API_MESSAGES['SESSION_NOT_UPDATED'][get_language_code()], null);

