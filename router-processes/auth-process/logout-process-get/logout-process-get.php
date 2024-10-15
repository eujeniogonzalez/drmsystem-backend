<?php

//Params should be empty
check(count($params), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

// Try to logout
$isSessionDropped = drop_session();
check($isSessionDropped, 200, true, API_MESSAGES['USER_LOGGED_OUT'][get_language_code()], null);

// User not logged out
check(null, 200, false, API_MESSAGES['USER_ALREADY_LOGGED_OUT'][get_language_code()], null);
