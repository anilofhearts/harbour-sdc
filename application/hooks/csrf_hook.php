<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CSRFHook {
    public function inject_csrf_token() {
        $CI =& get_instance();

        // Retrieve the CSRF token
        $csrf_token = $CI->security->get_csrf_hash();

        // Retrieve the CSRF cookie name from the configuration
        $csrf_cookie_name = $CI->config->item('csrf_cookie_name');

        // Explicitly set the CSRF cookie with desired attributes
        setcookie(
            $csrf_cookie_name, // Use the CSRF cookie name from config
            $csrf_token,       // The CSRF token
            [
                'expires' => time() + $CI->config->item('csrf_expire'), // Expiration time
                'path' => '/',                                         // Path
                'domain' => 'test.hedkerala.in',                      // Domain
                'secure' => TRUE,                                     // Only send over HTTPS
                'httponly' => TRUE,                                   // Prevent JavaScript access
                'samesite' => 'Strict',                                  // Set SameSite attribute
            ]
        );

        // Debugging information
        log_message('debug', 'CSRF Token set with SameSite=Lax');
    }
}
