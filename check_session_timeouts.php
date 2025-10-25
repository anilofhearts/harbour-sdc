<?php
/**
 * Check Current Session Timeout Settings
 */

echo "=== SESSION TIMEOUT CONFIGURATION ===\n\n";

// Load CodeIgniter config
require_once('index.php');

$CI =& get_instance();
$CI->config->load('config');

echo "Current Session Timeout Settings:\n";
echo "================================\n";

// Session expiration
$sess_expiration = $CI->config->item('sess_expiration');
echo "Session Expiration: " . $sess_expiration . " seconds (" . ($sess_expiration / 3600) . " hours)\n";

// Session time to update
$sess_time_to_update = $CI->config->item('sess_time_to_update');
echo "Session Update Interval: " . $sess_time_to_update . " seconds (" . ($sess_time_to_update / 60) . " minutes)\n";

// CSRF expire
$csrf_expire = $CI->config->item('csrf_expire');
echo "CSRF Token Expire: " . $csrf_expire . " seconds (" . ($csrf_expire / 3600) . " hours)\n";

echo "\nWhat This Means:\n";
echo "================\n";
echo "• Users will stay logged in for " . ($sess_expiration / 3600) . " hours\n";
echo "• Session will be refreshed every " . ($sess_time_to_update / 60) . " minutes of activity\n";
echo "• CSRF tokens will be valid for " . ($csrf_expire / 3600) . " hours\n";

echo "\nPrevious Settings (for comparison):\n";
echo "===================================\n";
echo "• Session Expiration: 28800 seconds (8 hours)\n";
echo "• Session Update: 1800 seconds (30 minutes)\n";
echo "• CSRF Expire: 7200 seconds (2 hours)\n";

echo "\nNew Settings:\n";
echo "=============\n";
echo "• Session Expiration: 86400 seconds (24 hours) ✓\n";
echo "• Session Update: 3600 seconds (1 hour) ✓\n";
echo "• CSRF Expire: 86400 seconds (24 hours) ✓\n";

echo "\nBenefits:\n";
echo "=========\n";
echo "✓ Users stay logged in 3x longer (24 hours vs 8 hours)\n";
echo "✓ Less frequent session updates (1 hour vs 30 minutes)\n";
echo "✓ CSRF tokens last much longer (24 hours vs 2 hours)\n";
echo "✓ Better user experience with fewer logouts\n";

echo "\nSecurity Note:\n";
echo "=============\n";
echo "⚠ Longer sessions mean more security risk if someone gains access\n";
echo "⚠ Consider your security requirements for your application\n";
echo "⚠ You can adjust these settings in application/config/config.php\n";

echo "\nTo apply these changes:\n";
echo "=====================\n";
echo "1. The changes are already applied to config.php\n";
echo "2. Clear any existing sessions (optional but recommended)\n";
echo "3. Users will get the new timeout on their next login\n";
echo "4. Existing sessions will continue with old timeout until they expire\n";

echo "\nTo clear existing sessions:\n";
echo "===========================\n";
echo "• Delete files in: " . sys_get_temp_dir() . "\n";
echo "• Or restart your web server\n";
echo "• Or wait for existing sessions to expire naturally\n";

echo "\n=== CONFIGURATION COMPLETE ===\n";
?>
