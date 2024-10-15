<?php

global $link;
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
check(!$link, 500, false, API_MESSAGES['DB_NOT_CONNECTED'][get_language_code()], null);
mysqli_set_charset($link, 'utf8');