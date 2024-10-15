<?php

function route_JSON_request($method, $params, $data) {
  switch ($method) {
    case Methods::GET:
      // Logout user
      require_once('./router-processes/auth-process/logout-process-get/logout-process-get.php');
      break;

    case Methods::POST:
      // Logout user
      require_once('./router-processes/auth-process/logout-process-post/logout-process-post.php');
      break;
    
    default:
      check(null, 200, false, API_MESSAGES['METHOD_NOT_SUPPORTED'][get_language_code()], null);
  }
}