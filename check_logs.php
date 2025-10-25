<?php
/**
 * Check Application Logs for Agreement Save Issues
 */

$log_file = 'application/logs/log-' . date('Y-m-d') . '.php';

echo "=== CHECKING APPLICATION LOGS ===\n";
echo "Log file: $log_file\n\n";

if (!file_exists($log_file)) {
    echo "No log file found for today. Logs will be created when you try to save an agreement.\n";
    echo "Make sure the application/logs directory is writable.\n";
    exit;
}

$content = file_get_contents($log_file);
$lines = explode("\n", $content);

// Filter out empty lines and PHP opening tag
$log_entries = array_filter($lines, function($line) {
    return !empty(trim($line)) && strpos($line, '<?php') === false;
});

echo "Total log entries: " . count($log_entries) . "\n\n";

// Show last 20 entries
echo "=== LAST 20 LOG ENTRIES ===\n";
$recent_entries = array_slice($log_entries, -20);

foreach ($recent_entries as $entry) {
    if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) --> (\w+): (.+)$/', $entry, $matches)) {
        $timestamp = $matches[1];
        $level = $matches[2];
        $message = $matches[3];
        
        $color = '';
        switch ($level) {
            case 'ERROR':
                $color = "\033[31m"; // Red
                break;
            case 'DEBUG':
                $color = "\033[36m"; // Cyan
                break;
            case 'INFO':
                $color = "\033[32m"; // Green
                break;
            case 'WARNING':
                $color = "\033[33m"; // Yellow
                break;
        }
        
        echo $color . "[$timestamp] $level: $message\033[0m\n";
    }
}

echo "\n=== AGREEMENT SAVE RELATED ENTRIES ===\n";
$agreement_entries = array_filter($log_entries, function($line) {
    return stripos($line, 'agreement') !== false || 
           stripos($line, 'validation') !== false ||
           stripos($line, 'form') !== false;
});

foreach ($agreement_entries as $entry) {
    if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) --> (\w+): (.+)$/', $entry, $matches)) {
        $timestamp = $matches[1];
        $level = $matches[2];
        $message = $matches[3];
        
        $color = '';
        switch ($level) {
            case 'ERROR':
                $color = "\033[31m"; // Red
                break;
            case 'DEBUG':
                $color = "\033[36m"; // Cyan
                break;
            case 'INFO':
                $color = "\033[32m"; // Green
                break;
            case 'WARNING':
                $color = "\033[33m"; // Yellow
                break;
        }
        
        echo $color . "[$timestamp] $level: $message\033[0m\n";
    }
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. Try to save an agreement now\n";
echo "2. Run this script again to see new log entries\n";
echo "3. Or run: php monitor_logs.php for real-time monitoring\n";
?>
