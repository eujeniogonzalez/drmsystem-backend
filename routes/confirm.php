<?php

function route_JSON_request($method, $params, $data) {
  switch ($method) {
    case Methods::POST:
      // Confirm user
      require_once('./router-processes/auth-process/confirm-process-post/confirm-process-post.php');
      break;

    default:
      check(null, 200, false, API_MESSAGES['METHOD_NOT_SUPPORTED'][get_language_code()], null);
  }
}

