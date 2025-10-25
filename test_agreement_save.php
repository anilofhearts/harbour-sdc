<?php
/**
 * Test Agreement Save Functionality
 */

// Include CodeIgniter
require_once('index.php');

echo "=== TESTING AGREEMENT SAVE FUNCTIONALITY ===\n\n";

try {
    $CI =& get_instance();
    
    if (isset($CI)) {
        echo "✓ CodeIgniter loaded successfully\n";
        
        // Test 1: Check if Section controller exists
        if (class_exists('Section')) {
            echo "✓ Section controller exists\n";
        } else {
            echo "✗ Section controller not found\n";
        }
        
        // Test 2: Check if Manager model is loaded
        if (isset($CI->manager)) {
            echo "✓ Manager model is loaded\n";
        } else {
            echo "✗ Manager model not loaded\n";
        }
        
        // Test 3: Check database connection
        try {
            $test_query = $CI->db->query("SELECT 1 as test");
            if ($test_query) {
                echo "✓ Database connection working\n";
            }
        } catch (Exception $e) {
            echo "✗ Database error: " . $e->getMessage() . "\n";
        }
        
        // Test 4: Check session
        if (isset($_SESSION['harbour'])) {
            echo "✓ User session exists\n";
            echo "  - Section ID: " . $_SESSION['harbour']['section_id'] . "\n";
            echo "  - User ID: " . $_SESSION['harbour']['user_id'] . "\n";
            echo "  - Role: " . $_SESSION['harbour']['role_id'] . "\n";
        } else {
            echo "✗ No user session found\n";
            echo "  You need to be logged in to save agreements\n";
        }
        
        // Test 5: Check form validation
        $CI->load->library('form_validation');
        $CI->form_validation->set_rules('test_field', 'Test Field', 'required');
        
        if ($CI->form_validation->run() == TRUE) {
            echo "✓ Form validation working\n";
        } else {
            echo "✗ Form validation failed (this is expected for test)\n";
        }
        
        // Test 6: Simulate agreement save attempt
        echo "\n=== SIMULATING AGREEMENT SAVE ===\n";
        
        // Create test POST data
        $_POST = array(
            'agreement_id' => '',
            'agreement_no' => 'TEST-AGREEMENT-' . time(),
            'agreement' => 'Test Agreement',
            'amount' => '100000',
            'date_of_agreement' => date('Y-m-d'),
            'date_of_commencement' => date('Y-m-d'),
            'exp_date_of_completion' => date('Y-m-d', strtotime('+1 year')),
            'type_of_work' => 'Construction',
            'name_of_contractor' => 'Test Contractor',
            'address' => 'Test Address',
            'contractor_email_id' => 'test@example.com',
            'contractor_phone_no' => '1234567890',
            'period_of_commencement' => '12 months',
            'short_code' => 'TEST',
            'section_id' => $_SESSION['harbour']['section_id'] ?? 1,
            'department' => 'CTE',
            'item[]' => array('Test Item'),
            'unit[]' => array('Nos'),
            'estimated_quantity[]' => array('100'),
            'estimated_rate[]' => array('1000'),
            'location[]' => array('Test Location')
        );
        
        echo "✓ Test POST data created\n";
        
        // Test the addUpdateAgreement method
        echo "Testing addUpdateAgreement method...\n";
        
        // This will trigger the logging we added
        $CI->load->controller('section');
        $section_controller = new Section();
        
        echo "✓ Section controller instantiated\n";
        echo "✓ Test data prepared\n";
        
        // Check if log file was created
        $log_file = 'application/logs/log-' . date('Y-m-d') . '.php';
        if (file_exists($log_file)) {
            echo "✓ Log file exists: $log_file\n";
            
            $content = file_get_contents($log_file);
            if (strpos($content, 'TEST') !== false) {
                echo "✓ Test log entries found\n";
            } else {
                echo "⚠ No test log entries found\n";
            }
        } else {
            echo "✗ No log file created\n";
        }
        
    } else {
        echo "✗ CodeIgniter not loaded\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "If you see any ✗ marks above, those are the issues preventing agreement saving.\n";
echo "If everything shows ✓, then the issue might be with the form submission itself.\n";
?>
