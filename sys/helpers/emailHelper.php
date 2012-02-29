<?php

/**
 * Documentar este helper...
 * 
 * @package Mojo*PHP
 * @since 26/02/2012
 */

function val_email($address) {
    return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
}

function send_mail($recipient, $subject = 'Test email', $message = 'Hello World', $param = NULL) {
    return mail($recipient, $subject, $message, $param);
}