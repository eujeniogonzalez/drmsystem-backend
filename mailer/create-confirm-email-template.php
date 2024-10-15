<?php

function create_confirm_email_template($confirm_id) {
  $main_domain = get_client_domain();
  $main_email = MAIN_EMAIL;

  return <<<HTML
    Hi, welcome to the DRM development Team!<br/><br/>
    Your registration is successful. It remains to confirm the email. To do this, click on the button below.<br/><br/><br/>
    <a href="{$main_domain}/confirm/{$confirm_id}" style="padding: 20px 40px; background-color: salmon; border-radius: 10px; text-decoration: none; color: #ffffff;" target="_blank">Confirm email</a><br/><br/><br/><br/>

    After confirmation email this letter can be deleted.<br/><br/><br/>
    --<br/>
    Best wishes<br/>
    DRM Team<br/>
    <a href="{$main_domain}" style="text-decoration: none; color: blueviolet;" target="_blank">{$main_domain}</a><br/>
    <a href="mailto:{$main_email}" style="text-decoration: none; color: blueviolet;">{$main_email}</a><br/>
  HTML;
}

