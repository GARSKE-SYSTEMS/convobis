<?php

namespace Convobis\Util;

class MailHelper {
    /**
     * Send an email.
     * @param string $to
     * @param string $subject
     * @param string $body
     * @return bool
     */
    public static function send(string $to, string $subject, string $body): bool {
        $headers = "From: noreply@convobis.local\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";
        return mail($to, $subject, $body, $headers);
    }
}
