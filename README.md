# Buto-Plugin-WfPhpmailer
Send mail using PHPMailer version 6.5.3.

## Send method
Call this function to send email.
```
wfPlugin::includeonce('wf/phpmailer');
$wf_phpmailer = new PluginWfPhpmailer();
$wf_phpmailer->send(array('some data...'));
```

## Example of data
```
SMTPAuth: true
SMTPSecure: ssl
Port: 465
SMTPDebug: 0
Username: _username_
Password: _password_
Host: _host_
From: _form_
FromName: _fromname_
To: _to_
Subject: _subject_
Body: _body_
WordWrap: 255
```

## Attachment
Use attachment param to include files.
```
attachment:
  -
    path: /theme/[theme]/README.md
```

## Test widget
Use this widget just for testing purpose. It will send email and output result.
```
type: widget
data:
  plugin: 'wf/phpmailer'
  method: 'send'
  data: 'yml:/theme/[theme]/data/phpmailer.yml:gmail'
```

## Known problems
Example of settings using Gmail or SMTPSecure.

## Gmail
If using Gmail maybe one should login and change application security settings (https://www.google.com/settings security).

## SMTPSecure
DLL php_openssl.dll must be enabled (uncomment) in php.ini if using SMTPSecure (extension=php_openssl.dll).