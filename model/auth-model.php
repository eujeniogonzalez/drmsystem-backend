<?php

function is_user_exists_by_email($email) {
  global $link;
  $sql = "SELECT `email` FROM `users` WHERE `email` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_num_rows($stmt) !== 0;
}

function create_user($first_name, $last_name, $email, $password, $confirm_id) {
  global $link;
  $password_hash = password_hash($password, PASSWORD_DEFAULT);
  $date = date(DATE_FORMAT);
  $role = UserRoles::OWNER;
  $sql = "INSERT INTO `users` (`email`, `password_hash`, `date`, `confirmid`, `role`, `first_name`, `last_name`) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 'sssssss', $email, $password_hash, $date, $confirm_id, $role, $first_name, $last_name);
  mysqli_stmt_execute($stmt);
  $mailInfo = create_mail_info(MailSubjects::CONFIRM_EMAIL, create_confirm_email_template($confirm_id));
  send_email($email, $mailInfo);
  return mysqli_stmt_affected_rows($stmt) !== 0;
}

function confirm_user($confirm_id) {
  global $link;
  $sql = "UPDATE `users` SET `is_confirmed` = 'true', `confirmid` = '' WHERE `confirmid` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $confirm_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_affected_rows($stmt) !== 0;
}

function is_user_confirmed($email) {
  global $link;
  $sql = "SELECT `is_confirmed` FROM `users` WHERE `email` = ? AND `is_confirmed` = 'true'";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_num_rows($stmt) !== 0;
}

function is_password_correct($email, $password) {
  global $link;
  $sql = "SELECT `password_hash` FROM `users` WHERE `email` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $password_hash);
  mysqli_stmt_fetch($stmt);
  return password_verify($password, $password_hash);
}

function get_password_hash($email) {
  global $link;
  $sql = "SELECT `password_hash` FROM `users` WHERE `email` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $password_hash);
  mysqli_stmt_fetch($stmt);
  return $password_hash;
}

function create_tokens($email) {
  $token_header_base64 = base64_encode(json_encode([
    'typ' => 'JWT',
    'alg' => 'HS256'
  ]));
  $token_payload_base64 = base64_encode(json_encode([
    'exp' => strtotime(StrToTime::ACCESS_TOKEN_EXPIRE),
    'role' => get_user_role_by_email($email),
    'user_id' => get_user_id_by_email($email)
  ]));
  $token_signature = hash_hmac('sha256', $token_header_base64.'.'.$token_payload_base64, get_password_hash($email), false);
  $access_token = $token_header_base64.'.'.$token_payload_base64.'.'.$token_signature;
  $refresh_token = generate_random_string(REFRESH_TOKEN_LENGTH);
  return ['access' => $access_token, 'refresh' => $refresh_token];
}

function get_user_role_by_email($email) {
  global $link;
  $sql = "SELECT `role` FROM `users` WHERE `email` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $user_role);
  mysqli_stmt_fetch($stmt);
  return $user_role;
}

function get_user_id_by_email($email) {
  global $link;
  $sql = "SELECT `id` FROM `users` WHERE `email` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $user_id);
  mysqli_stmt_fetch($stmt);
  return $user_id;
}

function get_user_sessions_count($user_id) {
  global $link;
  $sql = "SELECT `id` FROM `user_sessions` WHERE `user_id` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_num_rows($stmt);
}

function clear_sessions($user_id, $limit = 0) {
  $user_sessions_count = get_user_sessions_count($user_id);
  if ($user_sessions_count < $limit) return;
  global $link;
  $sql = "DELETE FROM `user_sessions` WHERE `user_id` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $user_id);
  mysqli_stmt_execute($stmt);
}

function reset_repass($user_id) {
  global $link;
  $sql = "UPDATE `users` SET `is_repass` = 'false', `repassid` = '' WHERE `id` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $user_id);
  mysqli_stmt_execute($stmt);
}

function create_session($email) {
  global $link;
  $tokens = create_tokens($email);
  $user_id = (int)get_user_id_by_email($email);
  clear_sessions($user_id, USER_SESSIONS_MAX_COUNT);
  reset_repass($user_id);
  $refresh_expire = strtotime(StrToTime::REFRESH_TOKEN_EXPIRE);
  $sql = "INSERT INTO `user_sessions` (`user_id`, `access_token`, `refresh_token`, `refresh_expire`) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 'ssss', $user_id, $tokens['access'], $tokens['refresh'], $refresh_expire);
  mysqli_stmt_execute($stmt);
  if (!is_client_domain_localhost()) {
    setcookie(
      RefreshCoockie::NAME,
      $tokens['refresh'],
      [
        'expires' => $refresh_expire,
        'path' => RefreshCoockie::PATH,
        'domain' => get_coockie_domain(),
        'secure' => RefreshCoockie::SECURE,
        'httponly' => RefreshCoockie::HTTPONLY,
        'samesite' => RefreshCoockie::SAMESITE
    ]);
  }
  return mysqli_stmt_affected_rows($stmt) !== 0 ? ['tokens' => $tokens] : false;
}

function is_refresh_token_expired($refresh_token) {
  global $link;
  $sql = "SELECT `refresh_expire` FROM `user_sessions` WHERE `refresh_token` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $refresh_token);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $refresh_expire);
  mysqli_stmt_fetch($stmt);
  return strtotime(StrToTime::NOW) > $refresh_expire;
}

function get_session_id_by_refresh($refresh_token) {
  global $link;
  $sql = "SELECT `id` FROM `user_sessions` WHERE `refresh_token` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $refresh_token);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $session_id);
  mysqli_stmt_fetch($stmt);
  return $session_id;
}

function get_user_email_by_refresh($refresh_token) {
  global $link;
  $sql = "SELECT `users`.`email` FROM `user_sessions` JOIN `users` ON `user_sessions`.`user_id` = `users`.`id` WHERE `user_sessions`.`refresh_token` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $refresh_token);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $email);
  mysqli_stmt_fetch($stmt);
  return $email;
}

function clear_session_by_id($session_id) {
  global $link;
  $sql = "DELETE FROM `user_sessions` WHERE `id` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $session_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_affected_rows($stmt) !== 0;
}

function update_session_by_refresh($refresh_token) {
  $session_id = get_session_id_by_refresh($refresh_token);
  $email = get_user_email_by_refresh($refresh_token);
  clear_session_by_id($session_id);
  return create_session($email);
}

function start_user_repass($email, $repass_id) {
  global $link;
  $sql = "UPDATE `users` SET `is_repass` = 'true', `repassid` = ? WHERE `email` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 'ss', $repass_id, $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  $mail_info = create_mail_info(MailSubjects::REPASS_EMAIL, create_confirm_repass_template($repass_id));
  send_email($email, $mail_info);
  return mysqli_stmt_affected_rows($stmt) !== 0;
}

function get_user_id_by_repass_id($repass_id) {
  global $link;
  $sql = "SELECT `id` FROM `users` WHERE `repassid` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $repass_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $id);
  mysqli_stmt_fetch($stmt);
  return $id;
}

function set_new_password($repass_id, $new_password) {
  $user_id = get_user_id_by_repass_id($repass_id);
  global $link;
  $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
  $sql = "UPDATE `users` SET `is_repass` = 'false', `repassid` = '', `password_hash` = ? WHERE `repassid` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 'ss', $new_password_hash, $repass_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_affected_rows($stmt) !== 0;
}

function is_user_logged_in($access_token) {
  global $link;
  $sql = "SELECT `access_token` FROM `user_sessions` WHERE `access_token` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $access_token);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  return mysqli_stmt_num_rows($stmt) !== 0;
}

function get_access_expire_time($payload) {
  return json_decode(base64_decode($payload), true)['exp'];
}

function is_access_token_expired($access_token) {
  $payload = explode(Symbols::DOT, $access_token)[1];
  $access_expire_time = get_access_expire_time($payload);
  return ($access_expire_time < strtotime(StrToTime::NOW));
}

function is_access_allowed() {
  $access_token = get_access_token_from_headers();
  if (!$access_token) return false;
  $is_access_token_expired = is_access_token_expired($access_token);
  $is_user_logged_in = is_user_logged_in($access_token);
  return !$is_access_token_expired && $is_user_logged_in;
}

function get_user_id_by_access_token($access_token) {
  global $link;
  $sql = "SELECT `user_id` FROM `user_sessions` WHERE `access_token` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $access_token);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $user_id);
  mysqli_stmt_fetch($stmt);
  return $user_id;
}

function get_user_role_by_access_token($access_token) {
  global $link;
  $userid = get_user_id_by_access_token($access_token);
  $sql = "SELECT `role` FROM `users` WHERE `id` = ?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $role);
  mysqli_stmt_fetch($stmt);
  return $role;
}

function is_access_allowed_by_user_role($user_role) {
  $access_token = get_access_token_from_headers();
  if (!$access_token) return false;
  $is_access_token_expired = is_access_token_expired($access_token);
  $is_user_logged_in = is_user_logged_in($access_token);
  $is_access_allowed = get_user_role_by_access_token($access_token) === $user_role;
  return !$is_access_token_expired && $is_user_logged_in && $is_access_allowed;
}

function get_access_token_from_headers() {
  $headers = get_all_headers();
  if (!isset($headers['Authorization']) && !isset($headers['authorization'])) return false;
  $headers_authorization = isset($headers['Authorization']) ? $headers['Authorization'] : $headers['authorization'];
  $access_token = explode(Symbols::SPACE, $headers_authorization)[1];
  return $access_token;
}

function drop_session($refresh_token) {
  $token = is_client_domain_localhost() ? $refresh_token : $_COOKIE[RefreshCoockie::NAME];

  $session_id = get_session_id_by_refresh($token);
  return clear_session_by_id($session_id);
}

function get_user_id() {
  $access_token = get_access_token_from_headers();
  $user_id = get_user_id_by_access_token($access_token);

  return $user_id;
}
