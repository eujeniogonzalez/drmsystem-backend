<?php

function create_mail_info($mailSubject, $mailTemplate) {
  return [
    'subject' => $mailSubject,
    'html' => $mailTemplate
  ];
}

