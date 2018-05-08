<?php
/**
<p>
Plugin to send mail from other plugins.
</p>
#code-php#
wfPlugin::includeonce('wf/phpmailer');
$wf_phpmailer = new PluginWfPhpmailer();
$wf_phpmailer->send(array('some data...'));
#code#
<p>
<strong>Gmail</strong>
</p>
<p>
If using Gmail maybe one should login and change application security settings (https://www.google.com/settings/security).
</p>
<p>
<strong>SMTPSecure</strong>
</p>
<p>
DLL php_openssl.dll must be enabled (uncomment) in php.ini if using SMTPSecure (extension=php_openssl.dll).
</p>
 */
class PluginWfPhpmailer {
  /**
  <p>
  Use this widget just for testing purpose.
  </p>
  #code-yml#
  type: widget
  data:
    plugin: 'wf/phpmailer'
    method: 'send'
    data: 'yml:/theme/[theme]/data/phpmailer.yml:gmail'
  #code#
   */
  public static function widget_send($data){
    // Send email and output result.
    wfHelp::yml_dump(self::send(wfArray::get($data, 'data')));
  }
  /**
  <p>
  Call this function to send email.
  </p>
  <p>
  Example of data when using smtp.fsdata.se mailserver.
  </p>
  #code-yml#
  #load:[app_dir]/plugin/[plugin]/data/example_fsdata.yml:load#
  #code#
  <p>
  Example of data when using smtp.gmail.com mailserver.
  </p>
  #code-yml#
  #load:[app_dir]/plugin/[plugin]/data/example_gmail.yml:load#
  #code#
   */
  public static function send($smtp){
    /**
     * Check if path string.
     */
    $smtp = wfSettings::getSettingsFromYmlString($smtp);
    /**
     * Crypt.
     */
    foreach ($smtp as $key => $value){
      $smtp[$key] = wfCrypt::decryptFromString($value);
    }
    /**
     * Defaults.
     */
    $default = wfFilesystem::loadYml(__DIR__.'/data/default.yml');
    /**
     * Merge.
     */
    $smtp = array_merge($default, $smtp);
    /**
     * Check params.
     */
    if(
      !wfArray::get($smtp, 'From') || 
      !wfArray::get($smtp, 'FromName') || 
      !wfArray::get($smtp, 'Host') || 
      !wfArray::get($smtp, 'Port') || 
      !wfArray::get($smtp, 'To') || 
      !wfArray::get($smtp, 'Subject') || 
      !wfArray::get($smtp, 'Body')
      )
    {
      return array('success' => false, 'alert' => array('Some critical data is not set when using PluginWfPhpmailer send method.'), 'smtp' => $smtp);
    }
    /**
     * Check params if SMTPAuth.
     */
    if($smtp['SMTPAuth'] && (!wfArray::get($smtp, 'Username') || !wfArray::get($smtp, 'Password'))){
      return array('success' => false, 'alert' => array('Some critical data is not set when using PluginWfPhpmailer send method and SMTPAuth is true.'), 'smtp' => $smtp);
    }
    /**
     * Including files.
     */
    require_once(__DIR__."/lib/class.phpmailer.php");
    require_once(__DIR__."/lib/class.smtp.php");
    /**
     * Send email.
     */
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet     = $smtp['CharSet'];
    $mail->SMTPAuth    = $smtp['SMTPAuth'];
    $mail->SMTPSecure  = $smtp['SMTPSecure'];
    $mail->SMTPDebug   = $smtp['SMTPDebug'];
    $mail->Username    = $smtp['Username'];
    $mail->Password    = $smtp['Password'];
    $mail->From        = $smtp['From'];
    $mail->FromName    = $smtp['FromName'];
    $mail->Host        = $smtp['Host'];
    $mail->Port        = $smtp['Port'];
    if(!isset($smtp['ReplyTo'])){
      $mail->addReplyTo($smtp['From'], $smtp['FromName']);
    }else{
      $mail->addReplyTo($smtp['ReplyTo']);
    }
    $mail->AddAddress($smtp['To']);
    $mail->Subject     = $smtp['Subject'];
    $mail->isHTML($smtp['isHTML']);
    $mail->Body        = $smtp['Body'];
    $mail->WordWrap    = $smtp['WordWrap'];
    if(!$mail->Send()) {
      return array('success' => false, 'alert' => array($mail->ErrorInfo), 'smtp' => $smtp);
    } else {
      return array('success' => true, 'smtp' => $smtp);
    }
  }
}
