<?php
class PluginWfPhpmailer {
  public static function widget_send($data){
    wfHelp::yml_dump(self::send(wfArray::get($data, 'data')));
  }
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
