# Kannel client

Send MT and Read MO

## Send MT

Sends MT messages using function SendMt::send.

SendMt::send(string $url, string $username, string $password, string $from, string $to, string $text, string $smsc=null, array $params = [], float $timeout=60);

* $url is the destination
* $username and $password are the credentials
* $from is the origin of message, usually the Shortcode
* $to is the destination of message, the msisdn
* $text is the content of message
* $smsc optional. Specifies the name of SMSC center configured in kannel.
* $params are optional values like charset, udh, flash.

Uses apolinux/curl library.

## TODO

pending code of ReadMo class



