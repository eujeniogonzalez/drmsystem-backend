<?php

require_once('config.php');
require_once('const.php');
require_once('utils.php');
require_once('db.php');
require_once('model/auth-model.php');
require_once('mailer/send-email.php');
require_once('mailer/create-confirm-email-template.php');
require_once('mailer/create-confirm-repass-template.php');
require_once('mailer/create-mail-info.php');

$method = $_SERVER['REQUEST_METHOD'];
$params = parse_URL_params();

set_headers();
handle_options_method($method);

process_JSON_API_route($params, $method);

