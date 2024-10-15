<?php

function route_JSON_request($method, $params, $data) {
  switch ($method) {
    case Methods::POST:
      // Login user
      require_once('./router-processes/auth-process/login-process-post/login-process-post.php');
      break;
    
    default:
      check(null, 200, false, API_MESSAGES['METHOD_NOT_SUPPORTED'][get_language_code()], null);
  }
}



