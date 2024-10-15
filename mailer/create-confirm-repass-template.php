<?php

function create_confirm_repass_template($repass_id) {
  $main_domain = get_client_domain();
  $main_email = MAIN_EMAIL;

  return <<<HTML
    Hi, you asked to change the password.<br/><br/>
    To set a new password, follow the link below.<br/><br/><br/>
    <a href="{$main_domain}/newpassword/{$repass_id}" style="padding: 20px 40px; background-color: salmon; border-radius: 10px; text-decoration: none; color: #ffffff;" target="_blank">Set new password</a><br/><br/><br/><br/>

    After setting new password this letter can be deleted.<br/><br/><br/>
    --<br/>
    Best wishes<br/>
    DRM Team<br/>
    <a href="{$main_domain}" style="text-decoration: none; color: blueviolet;" target="_blank">{$main_domain}</a><br/>
    <a href="mailto:{$main_email}" style="text-decoration: none; color: blueviolet;">{$main_email}</a><br/>
  HTML;
}

