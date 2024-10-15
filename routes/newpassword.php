<?php

function route_JSON_request($method, $params, $data) {
  switch ($method) {
    case Methods::POST:
      // Set new password
      require_once('./router-processes/auth-process/newpassword-process-post/newpassword-process-post.php');
      break;
    
    default:
      check(null, 200, false, API_MESSAGES['METHOD_NOT_SUPPORTED'][get_language_code()], null);
  }
}


