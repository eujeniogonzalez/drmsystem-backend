<?php

require_once('const.php');

function create_JSON_API_response($success, $message, $data) {
  $response = Array(
    'success' => $success,
    'message' => $message,
    'payload' => $data
  );
  return json_encode($response);
}

function parse_URL_params() {
  if (!isset($_GET['q'])) {
    return [];
  }
  $url = rtrim($_GET['q'], Symbols::SLASH);
  $params = explode(Symbols::SLASH, $url);
  return $params;
}

function get_route_info($params) {
  $route = $params[0];
  $route_params = array_slice($params, 1);
  return [
    'name' => $route,
    'params' => $route_params
  ];
}

function is_route_exist($route_name) {
  return file_exists(Paths::ROUTES_DIR.$route_name.'.php');
}

function get_query_body() {
  return json_decode(file_get_contents('php://input'), true);
}

function process_JSON_API_route($params, $method) {
  $route = get_route_info($params);
  $data = get_query_body();

  check(count($params) === 0, 200, false, API_MESSAGES['NO_ROUTES_IN_URL'][get_language_code()], null);
  check(!is_route_exist($route['name']), 200, false, API_MESSAGES['ROUTE_NOT_EXIST'][get_language_code()], null);

  require_once Paths::ROUTES_DIR.$route['name'].'.php';

  route_JSON_request($method, $route['params'], $data);
}

function is_email_valid($email) {
  return preg_match(EMAIL_REGEXP, strtolower($email));
}

function is_first_last_name_valid($name) {
  return preg_match(FIRST_LAST_NAME_REGEXP, strtolower($name));
}

function is_array_correct($array, $fields) {
  foreach($fields as $field) {
    if (!array_key_exists($field, $array)) {
      return false;
    }
  }
  return count($array) === count($fields);
}

function get_random_number($length) {
  $result = Symbols::EMPTY_STRING;
  for($i = 0; $i < $length; $i++) {
      $result .= mt_rand(0, 9);
  }
  return $result;
}

function generate_random_string($length) {
  $characters = ALL_NUMBERS.ALL_LETTERS;
  $characters_length = strlen($characters);
  $random_string = '';
  for ($i = 0; $i < $length; $i++) {
    $random_string .= $characters[random_int(0, $characters_length - 1)];
  }
  return $random_string;
}

function set_headers() {
  header('Access-Control-Allow-Origin: '.get_client_domain());
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Allow-Headers: Authorization, Content-Type, Language-Code');
  header('Access-Control-Allow-Methods: GET, POST, PATCH, UPDATE, DELETE, OPTIONS');
}

function check($condition, $code, $status, $message, $payload) {
  if ($condition || $condition === null) {
    http_response_code($code);
    echo create_JSON_API_response($status, $message, $payload);
    exit();
  }
}

function get_current_protocol() {
  return (!empty($_SERVER['HTTP_HTTPS']) && $_SERVER['HTTP_HTTPS'] === 'on') ? 'https://' : 'http://';
}

function get_current_URL() {
  return get_current_protocol().$_SERVER['HTTP_HOST'];
}

function is_API_domain_prod() {
  return get_current_URL() === API_DOMAIN_PROD;
}

function is_client_domain_localhost() {
  return (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === CLIENT_DOMAIN_LOCALHOST.Symbols::SLASH);
}

function get_client_domain() {
  if (is_API_domain_prod()) return CLIENT_DOMAIN_PROD;

  return is_client_domain_localhost() ? CLIENT_DOMAIN_LOCALHOST : CLIENT_DOMAIN_DEV;
}

function is_client_domain_prod() {
  return get_client_domain() === CLIENT_DOMAIN_PROD;
}

function get_coockie_domain() {
  return is_API_domain_prod() ? COOCKIE_DOMAIN_PROD : COOCKIE_DOMAIN_DEV;
}

function handle_options_method($method) {
  if ($method === Methods::OPTIONS) {
    http_response_code(200);
    exit();
  }
}

function get_all_headers() {
  if (!function_exists('getallheaders')) return;

  return getallheaders();
}

function get_language_code() {
  $headers = get_all_headers();

  return isset($headers['Language-Code']) ? $headers['Language-Code'] : LanguageCodes::ENG;
}
