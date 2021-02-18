# Buto-Plugin-WfPhpmailer
Plugin to send mail from other plugins.

## Send method
Call this function to send email.
```
wfPlugin::includeonce('wf/phpmailer');
$wf_phpmailer = new PluginWfPhpmailer();
$wf_phpmailer->send(array('some data...'));
```

## Example of data
Check this example of data.

### Using Google
Example of data when using smtp.gmail.com mailserver.
```
SMTPAuth: true
SMTPSecure: ssl
Port: 465
SMTPDebug: 0
Username: _gmail_adress_
Password: _gmail_password_
Host: smtp.gmail.com
From: _some_email_adress_
FromName: _some_name_
To: _some_email_adress_
Subject: 'Subject'
Body: 'Body.'
```
### Using FSData
Example of data when using smtp.fsdata.se mailserver.
```
SMTPAuth: true
Username: _fsdata_username_
Password: _fsdata_password_
Host: smtp.fsdata.se
Port: 26
From: from@world.com
FromName: 'Hello World'
_comment: 'Port 587 for secure or 26?'
To: 'info@mydomainzzz.com'
Subject: 'Subject'
Body: 'Body.'
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
Se example of settings for Gmail and FSData.

## Gmail
If using Gmail maybe one should login and change application security settings (https://www.google.com/settings security).

## SMTPSecure
DLL php_openssl.dll must be enabled (uncomment) in php.ini if using SMTPSecure (extension=php_openssl.dll).