<?php

function route_JSON_request($method, $params, $data) {
  switch ($method) {
    case Methods::GET:
      // Refresh token
      require_once('./router-processes/auth-process/refresh-process-get/refresh-process-get.php');
      break;

    case Methods::POST:
      // Refresh token
      require_once('./router-processes/auth-process/refresh-process-post/refresh-process-post.php');
      break;
    
    default:
      check(null, 200, false, API_MESSAGES['METHOD_NOT_SUPPORTED'][get_language_code()], null);
  }
}


