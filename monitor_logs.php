<?php
/**
 * Real-time Log Monitor for Agreement Saving Issues
 * 
 * Usage: php monitor_logs.php
 * This will show real-time log entries as they happen
 */

$log_file = 'application/logs/log-' . date('Y-m-d') . '.php';

echo "=== AGREEMENT SAVE LOG MONITOR ===\n";
echo "Monitoring: $log_file\n";
echo "Press Ctrl+C to stop monitoring\n\n";

if (!file_exists($log_file)) {
    echo "Log file doesn't exist yet. It will be created when the first log entry is made.\n";
    echo "Try saving an agreement to generate log entries.\n\n";
}

// Function to read the last few lines of the log file
function getLastLogEntries($file, $lines = 20) {
    if (!file_exists($file)) {
        return [];
    }
    
    $content = file_get_contents($file);
    $logEntries = explode("\n", $content);
    
    // Filter out empty lines and get last N entries
    $logEntries = array_filter($logEntries, function($line) {
        return !empty(trim($line)) && strpos($line, '<?php') === false;
    });
    
    return array_slice($logEntries, -$lines);
}

// Function to parse log entry
function parseLogEntry($line) {
    if (empty($line)) return null;
    
    // Extract timestamp, level, and message
    if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) --> (\w+): (.+)$/', $line, $matches)) {
        return [
            'timestamp' => $matches[1],
            'level' => $matches[2],
            'message' => $matches[3]
        ];
    }
    
    return null;
}

$lastSize = 0;
$lastEntries = [];

echo "Waiting for log entries...\n";

while (true) {
    if (file_exists($log_file)) {
        $currentSize = filesize($log_file);
        
        if ($currentSize > $lastSize) {
            // New content added
            $newEntries = getLastLogEntries($log_file, 10);
            
            foreach ($newEntries as $entry) {
                if (!in_array($entry, $lastEntries)) {
                    $parsed = parseLogEntry($entry);
                    if ($parsed) {
                        $color = '';
                        switch ($parsed['level']) {
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
                        
                        echo $color . "[{$parsed['timestamp']}] {$parsed['level']}: {$parsed['message']}\033[0m\n";
                    }
                }
            }
            
            $lastEntries = $newEntries;
        }
        
        $lastSize = $currentSize;
    }
    
    sleep(1); // Check every second
}
?>
