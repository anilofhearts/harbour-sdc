<?php
/**
 * Test if logging is working properly
 */

// Include CodeIgniter
require_once('index.php');

echo "=== TESTING LOGGING FUNCTIONALITY ===\n\n";

// Test 1: Check if we can write to logs directory
$log_dir = 'application/logs/';
echo "1. Checking logs directory: $log_dir\n";

if (is_dir($log_dir)) {
    echo "   ✓ Logs directory exists\n";
    
    if (is_writable($log_dir)) {
        echo "   ✓ Logs directory is writable\n";
    } else {
        echo "   ✗ Logs directory is NOT writable\n";
        echo "   Fix: chmod 755 application/logs/\n";
    }
} else {
    echo "   ✗ Logs directory does not exist\n";
}

// Test 2: Try to create a log entry
echo "\n2. Testing log creation...\n";

try {
    $CI =& get_instance();
    
    if (isset($CI)) {
        echo "   ✓ CodeIgniter loaded successfully\n";
        
        // Test logging
        log_message('debug', 'TEST LOG ENTRY - Logging is working!');
        log_message('info', 'TEST LOG ENTRY - Info level logging');
        log_message('error', 'TEST LOG ENTRY - Error level logging');
        
        echo "   ✓ Log entries created\n";
        
        // Check if log file was created
        $log_file = 'application/logs/log-' . date('Y-m-d') . '.php';
        if (file_exists($log_file)) {
            echo "   ✓ Log file created: $log_file\n";
            
            // Show log content
            $content = file_get_contents($log_file);
            echo "   Log file size: " . strlen($content) . " bytes\n";
            
            if (strlen($content) > 100) {
                echo "   ✓ Log file has content\n";
            } else {
                echo "   ⚠ Log file is very small, might be empty\n";
            }
        } else {
            echo "   ✗ Log file was not created\n";
        }
    } else {
        echo "   ✗ CodeIgniter not loaded\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error testing logging: " . $e->getMessage() . "\n";
}

// Test 3: Check current log files
echo "\n3. Checking existing log files...\n";
$log_files = glob('application/logs/log-*.php');
if (count($log_files) > 0) {
    echo "   Found " . count($log_files) . " log files:\n";
    foreach ($log_files as $file) {
        $size = filesize($file);
        $modified = date('Y-m-d H:i:s', filemtime($file));
        echo "   - $file ($size bytes, modified: $modified)\n";
    }
} else {
    echo "   No log files found\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "If logging is working, you should see log entries above.\n";
echo "If not, check the logs directory permissions.\n";
?>
