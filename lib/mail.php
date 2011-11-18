<?php
namespace lib;

include_once $_SERVER['NP_ROOT'] . '/lib/init-cli.php';

new \lib\mail();

class mail extends phpmailer {

  public function __construct() {
    // Set to use PHP's mail()
    $this->IsMail();

    // Set Content-Type and charset
    $content_type = 'text/plain';
    $this->ContentType = $content_type;
    // if ('text/html' == $content_type)
    $this->IsHTML(false);

    $this->Hostname = \lib\conf\constants::$domain;

    // Set custom headers
    /* if (!empty($headers)) { */
    /*   foreach((array) $headers as $name => $content) { */
    /*     $phpmailer->AddCustomHeader(sprintf('%1$s: %2$s', $name, $content)); */
    /*   } */

    /*   if (false !== stripos($content_type, 'multipart') && ! empty($boundary)) { */
    /*     $phpmailer->AddCustomHeader(sprintf("Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary)); */
    /*   } */
    /* } */

  }

  /**
   * Send mail, similar to PHP's mail
   *
   * A true return value does not automatically mean that the user received the
   * email successfully. It just only means that the method used was able to
   * process the request without any errors.
   *
   * The default content type is 'text/plain' which does not allow using HTML.
   */
  private function send($from_email, $from_name, array $to, $subject, $message, array $cc = array(), array $bcc = array(), array $attachments = array()) {
    $this->From     = $from_email;
    $this->FromName = $from_name;

    // add recipients
    foreach ((array) $to as $recipient_name => $recipient_email) {
      $this->AddAddress(trim($recipient_email), trim($recipient_name));
    }
    // Add any CC and BCC recipients
    foreach ($cc as $recipient_name => $recipient_email) {
      $this->AddCc(trim($recipient_email), trim($recipient_name));
    }
    foreach ($bcc as $recipient_name => $recipient_email) {
      $this->AddBcc(trim($recipient_email), trim($recipient_name));
    }

    // Set mail's subject and body
    $this->Subject = $subject;
    $this->Body    = $message;

    foreach ($attachments as $attachment) {
      $this->AddAttachment($attachment);
    }

    // Send!
    $result = $this->Send();

    return $result;    
  }

}
