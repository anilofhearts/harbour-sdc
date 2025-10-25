<?php
/**
 * Command Line Script to Clean Up Incomplete Agreements
 * 
 * Usage:
 * php cleanup_incomplete_agreements.php --find
 * php cleanup_incomplete_agreements.php --backup
 * php cleanup_incomplete_agreements.php --delete
 * php cleanup_incomplete_agreements.php --restore
 */

// Include CodeIgniter
require_once('index.php');

// Get command line arguments
$action = $argv[1] ?? '--help';

switch ($action) {
    case '--find':
        findIncompleteAgreements();
        break;
    case '--backup':
        createBackup();
        break;
    case '--delete':
        deleteIncompleteAgreements();
        break;
    case '--restore':
        restoreFromBackup();
        break;
    case '--help':
    default:
        showHelp();
        break;
}

function findIncompleteAgreements() {
    echo "=== FINDING INCOMPLETE AGREEMENTS ===\n\n";
    
    $CI =& get_instance();
    
    // 1. Agreements with no locations
    $no_locations = $CI->db->query("
        SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
        FROM agreement a
        LEFT JOIN section s ON s.section_id = a.section_id
        LEFT JOIN agreement_location al ON al.agreement_id = a.agreement_id
        WHERE al.agreement_location_id IS NULL
        AND a.date_of_completion IS NULL
    ")->result();

    // 2. Agreements with no items
    $no_items = $CI->db->query("
        SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
        FROM agreement a
        LEFT JOIN section s ON s.section_id = a.section_id
        LEFT JOIN agreement_item ai ON ai.agreement_id = a.agreement_id
        WHERE ai.agreement_item_id IS NULL
        AND a.date_of_completion IS NULL
    ")->result();

    // 3. Agreements with no vehicles
    $no_vehicles = $CI->db->query("
        SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
        FROM agreement a
        LEFT JOIN section s ON s.section_id = a.section_id
        LEFT JOIN vehicle v ON v.vehicle_agreement_id = a.agreement_id
        WHERE v.vehicle_id IS NULL
        AND a.date_of_completion IS NULL
    ")->result();

    // 4. Agreements with zero amount
    $zero_amount = $CI->db->query("
        SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
        FROM agreement a
        LEFT JOIN section s ON s.section_id = a.section_id
        WHERE (a.amount IS NULL OR a.amount = 0)
        AND a.date_of_completion IS NULL
    ")->result();

    // 5. Agreements with missing required fields
    $missing_fields = $CI->db->query("
        SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
        FROM agreement a
        LEFT JOIN section s ON s.section_id = a.section_id
        WHERE (a.agreement IS NULL OR a.agreement = '')
        OR (a.agreement_no IS NULL OR a.agreement_no = '')
        OR a.date_of_agreement IS NULL
        OR a.date_of_commencement IS NULL
        AND a.date_of_completion IS NULL
    ")->result();

    echo "SUMMARY:\n";
    echo "- Agreements with no locations: " . count($no_locations) . "\n";
    echo "- Agreements with no items: " . count($no_items) . "\n";
    echo "- Agreements with no vehicles: " . count($no_vehicles) . "\n";
    echo "- Agreements with zero amount: " . count($zero_amount) . "\n";
    echo "- Agreements with missing fields: " . count($missing_fields) . "\n\n";

    if (count($no_locations) > 0) {
        echo "AGREEMENTS WITH NO LOCATIONS:\n";
        foreach ($no_locations as $agreement) {
            echo "- ID: {$agreement->agreement_id}, No: {$agreement->agreement_no}, Name: {$agreement->agreement}, Section: {$agreement->section}\n";
        }
        echo "\n";
    }

    if (count($no_items) > 0) {
        echo "AGREEMENTS WITH NO ITEMS:\n";
        foreach ($no_items as $agreement) {
            echo "- ID: {$agreement->agreement_id}, No: {$agreement->agreement_no}, Name: {$agreement->agreement}, Section: {$agreement->section}\n";
        }
        echo "\n";
    }

    if (count($no_vehicles) > 0) {
        echo "AGREEMENTS WITH NO VEHICLES:\n";
        foreach ($no_vehicles as $agreement) {
            echo "- ID: {$agreement->agreement_id}, No: {$agreement->agreement_no}, Name: {$agreement->agreement}, Section: {$agreement->section}\n";
        }
        echo "\n";
    }

    if (count($zero_amount) > 0) {
        echo "AGREEMENTS WITH ZERO AMOUNT:\n";
        foreach ($zero_amount as $agreement) {
            echo "- ID: {$agreement->agreement_id}, No: {$agreement->agreement_no}, Name: {$agreement->agreement}, Amount: {$agreement->amount}, Section: {$agreement->section}\n";
        }
        echo "\n";
    }

    if (count($missing_fields) > 0) {
        echo "AGREEMENTS WITH MISSING FIELDS:\n";
        foreach ($missing_fields as $agreement) {
            echo "- ID: {$agreement->agreement_id}, No: {$agreement->agreement_no}, Name: {$agreement->agreement}, Section: {$agreement->section}\n";
        }
        echo "\n";
    }
}

function createBackup() {
    echo "=== CREATING BACKUP OF INCOMPLETE AGREEMENTS ===\n\n";
    
    $CI =& get_instance();
    
    try {
        // Create backup table
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS agreement_backup_incomplete AS 
            SELECT *, NOW() as backup_created_at FROM agreement 
            WHERE date_of_completion IS NULL
            AND (
                NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = agreement.agreement_id)
                OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = agreement.agreement_id)
                OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = agreement.agreement_id)
                OR (amount IS NULL OR amount = 0)
                OR (agreement IS NULL OR agreement = '')
                OR (agreement_no IS NULL OR agreement_no = '')
                OR date_of_agreement IS NULL
                OR date_of_commencement IS NULL
            )
        ");

        $backup_count = $CI->db->query("SELECT COUNT(*) as count FROM agreement_backup_incomplete")->row()->count;
        
        echo "✓ Backup created successfully!\n";
        echo "✓ Backed up $backup_count incomplete agreements to 'agreement_backup_incomplete' table.\n";
        echo "✓ You can now safely delete incomplete agreements.\n\n";
        
    } catch (Exception $e) {
        echo "✗ Error creating backup: " . $e->getMessage() . "\n";
    }
}

function deleteIncompleteAgreements() {
    echo "=== DELETING INCOMPLETE AGREEMENTS ===\n\n";
    
    $CI =& get_instance();
    
    // Check if backup exists
    $backup_exists = $CI->db->query("SHOW TABLES LIKE 'agreement_backup_incomplete'")->num_rows() > 0;
    
    if (!$backup_exists) {
        echo "✗ No backup found! Please create a backup first using --backup\n";
        return;
    }
    
    echo "⚠ WARNING: This will permanently delete incomplete agreements!\n";
    echo "⚠ Make sure you have created a backup first!\n\n";
    
    // Ask for confirmation
    echo "Type 'DELETE' to confirm: ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    
    if (trim($line) !== 'DELETE') {
        echo "✗ Deletion cancelled.\n";
        return;
    }
    
    try {
        $CI->db->trans_start();
        
        // Get incomplete agreement IDs
        $incomplete_agreements = $CI->db->query("
            SELECT DISTINCT a.agreement_id
            FROM agreement a
            WHERE a.date_of_completion IS NULL
            AND (
                NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
                OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
                OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = a.agreement_id)
                OR (a.amount IS NULL OR a.amount = 0)
                OR (a.agreement IS NULL OR a.agreement = '')
                OR (a.agreement_no IS NULL OR a.agreement_no = '')
                OR a.date_of_agreement IS NULL
                OR a.date_of_commencement IS NULL
            )
        ")->result();

        $deleted_count = 0;
        
        foreach ($incomplete_agreements as $agreement) {
            $agreement_id = $agreement->agreement_id;
            
            // Delete related data first
            $CI->db->where('agreement_id', $agreement_id);
            $CI->db->delete('trip');

            $CI->db->query("
                DELETE c FROM chainage c
                INNER JOIN agreement_location al ON c.chainage_agr_loc_id = al.agreement_location_id
                WHERE al.agreement_id = ?
            ", [$agreement_id]);

            $CI->db->where('vehicle_agreement_id', $agreement_id);
            $CI->db->delete('vehicle');

            $CI->db->where('agreement_id', $agreement_id);
            $CI->db->delete('agreement_item');

            $CI->db->where('agreement_id', $agreement_id);
            $CI->db->delete('agreement_location');

            // Delete the agreement
            $CI->db->where('agreement_id', $agreement_id);
            $CI->db->delete('agreement');

            $deleted_count++;
        }

        $CI->db->trans_complete();

        if ($CI->db->trans_status() === FALSE) {
            throw new Exception('Transaction failed');
        }

        echo "✓ Deletion completed successfully!\n";
        echo "✓ Deleted $deleted_count incomplete agreements and all related data.\n\n";
        
        // Show remaining agreements
        $remaining = $CI->db->query("SELECT COUNT(*) as count FROM agreement WHERE date_of_completion IS NULL")->row();
        echo "✓ Total remaining agreements: " . $remaining->count . "\n";
        
    } catch (Exception $e) {
        $CI->db->trans_rollback();
        echo "✗ Error during deletion: " . $e->getMessage() . "\n";
        echo "✗ No data was deleted due to the error.\n";
    }
}

function restoreFromBackup() {
    echo "=== RESTORING FROM BACKUP ===\n\n";
    
    $CI =& get_instance();
    
    try {
        // Check if backup exists
        $backup_exists = $CI->db->query("SHOW TABLES LIKE 'agreement_backup_incomplete'")->num_rows() > 0;
        
        if (!$backup_exists) {
            echo "✗ No backup table found. Please create a backup first.\n";
            return;
        }

        $CI->db->trans_start();

        // Restore agreements
        $CI->db->query("
            INSERT INTO agreement 
            SELECT * FROM agreement_backup_incomplete 
            WHERE agreement_id NOT IN (SELECT agreement_id FROM agreement)
        ");

        $CI->db->trans_complete();

        if ($CI->db->trans_status() === FALSE) {
            throw new Exception('Transaction failed');
        }

        echo "✓ Restoration completed successfully!\n";
        echo "✓ Restored agreements from backup.\n\n";
        
    } catch (Exception $e) {
        $CI->db->trans_rollback();
        echo "✗ Error during restoration: " . $e->getMessage() . "\n";
    }
}

function showHelp() {
    echo "=== CLEANUP INCOMPLETE AGREEMENTS TOOL ===\n\n";
    echo "Usage: php cleanup_incomplete_agreements.php [OPTION]\n\n";
    echo "Options:\n";
    echo "  --find     Find incomplete agreements\n";
    echo "  --backup   Create backup of incomplete agreements\n";
    echo "  --delete   Delete incomplete agreements (requires backup)\n";
    echo "  --restore  Restore from backup\n";
    echo "  --help     Show this help message\n\n";
    echo "Recommended workflow:\n";
    echo "1. php cleanup_incomplete_agreements.php --find\n";
    echo "2. php cleanup_incomplete_agreements.php --backup\n";
    echo "3. php cleanup_incomplete_agreements.php --delete\n";
    echo "4. (if needed) php cleanup_incomplete_agreements.php --restore\n\n";
}
?>
