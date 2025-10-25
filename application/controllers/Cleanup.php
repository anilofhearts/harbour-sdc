<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cleanup extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Only allow admin users to access this
        $role_id = $_SESSION['harbour']['role_id'];
        if (!in_array($role_id, ['admin', 'state', 'circle'])) {
            redirect('login', 'refresh');
        }
    }

    public function index()
    {
        $this->load->view('cleanup/dashboard');
    }

    public function find_incomplete_agreements()
    {
        echo "<h1>Finding Incomplete Agreements</h1>";
        
        // 1. Agreements with no locations
        $no_locations = $this->db->query("
            SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
            FROM agreement a
            LEFT JOIN section s ON s.section_id = a.section_id
            LEFT JOIN agreement_location al ON al.agreement_id = a.agreement_id
            WHERE al.agreement_location_id IS NULL
            AND a.date_of_completion IS NULL
        ")->result();

        // 2. Agreements with no items
        $no_items = $this->db->query("
            SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
            FROM agreement a
            LEFT JOIN section s ON s.section_id = a.section_id
            LEFT JOIN agreement_item ai ON ai.agreement_id = a.agreement_id
            WHERE ai.agreement_item_id IS NULL
            AND a.date_of_completion IS NULL
        ")->result();

        // 3. Agreements with no vehicles
        $no_vehicles = $this->db->query("
            SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
            FROM agreement a
            LEFT JOIN section s ON s.section_id = a.section_id
            LEFT JOIN vehicle v ON v.vehicle_agreement_id = a.agreement_id
            WHERE v.vehicle_id IS NULL
            AND a.date_of_completion IS NULL
        ")->result();

        // 4. Agreements with zero amount
        $zero_amount = $this->db->query("
            SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
            FROM agreement a
            LEFT JOIN section s ON s.section_id = a.section_id
            WHERE (a.amount IS NULL OR a.amount = 0)
            AND a.date_of_completion IS NULL
        ")->result();

        // 5. Agreements with missing required fields
        $missing_fields = $this->db->query("
            SELECT a.agreement_id, a.agreement_no, a.agreement, a.amount, a.section_id, s.section
            FROM agreement a
            LEFT JOIN section s ON s.section_id = a.section_id
            WHERE (a.agreement IS NULL OR a.agreement = '')
            OR (a.agreement_no IS NULL OR a.agreement_no = '')
            OR a.date_of_agreement IS NULL
            OR a.date_of_commencement IS NULL
            AND a.date_of_completion IS NULL
        ")->result();

        $data = [
            'no_locations' => $no_locations,
            'no_items' => $no_items,
            'no_vehicles' => $no_vehicles,
            'zero_amount' => $zero_amount,
            'missing_fields' => $missing_fields
        ];

        $this->load->view('cleanup/incomplete_agreements', $data);
    }

    public function delete_incomplete_agreements()
    {
        if (!$this->input->post('confirm_delete')) {
            echo "<h1>Error: Confirmation required</h1>";
            echo "<p>You must confirm the deletion by checking the confirmation box.</p>";
            return;
        }

        echo "<h1>Deleting Incomplete Agreements</h1>";
        
        $deleted_count = 0;
        $errors = [];

        try {
            // Start transaction
            $this->db->trans_start();

            // Get incomplete agreement IDs
            $incomplete_agreements = $this->db->query("
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

            foreach ($incomplete_agreements as $agreement) {
                $agreement_id = $agreement->agreement_id;
                
                // Delete related data first
                $this->db->where('agreement_id', $agreement_id);
                $this->db->delete('trip');

                $this->db->query("
                    DELETE c FROM chainage c
                    INNER JOIN agreement_location al ON c.chainage_agr_loc_id = al.agreement_location_id
                    WHERE al.agreement_id = ?
                ", [$agreement_id]);

                $this->db->where('vehicle_agreement_id', $agreement_id);
                $this->db->delete('vehicle');

                $this->db->where('agreement_id', $agreement_id);
                $this->db->delete('agreement_item');

                $this->db->where('agreement_id', $agreement_id);
                $this->db->delete('agreement_location');

                // Delete the agreement
                $this->db->where('agreement_id', $agreement_id);
                $this->db->delete('agreement');

                $deleted_count++;
            }

            // Complete transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            echo "<div class='alert alert-success'>";
            echo "<h3>Deletion Completed Successfully!</h3>";
            echo "<p>Deleted $deleted_count incomplete agreements and all related data.</p>";
            echo "</div>";

        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo "<div class='alert alert-danger'>";
            echo "<h3>Error During Deletion</h3>";
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>No data was deleted due to the error.</p>";
            echo "</div>";
        }

        // Show remaining agreements
        $remaining = $this->db->query("
            SELECT COUNT(*) as count FROM agreement WHERE date_of_completion IS NULL
        ")->row();

        echo "<h3>Remaining Agreements</h3>";
        echo "<p>Total remaining agreements: " . $remaining->count . "</p>";
    }

    public function create_backup()
    {
        echo "<h1>Creating Backup of Incomplete Agreements</h1>";
        
        try {
            // Create backup tables
            $this->db->query("
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

            $backup_count = $this->db->query("SELECT COUNT(*) as count FROM agreement_backup_incomplete")->row()->count;

            echo "<div class='alert alert-success'>";
            echo "<h3>Backup Created Successfully!</h3>";
            echo "<p>Backed up $backup_count incomplete agreements to 'agreement_backup_incomplete' table.</p>";
            echo "<p>You can now safely delete incomplete agreements.</p>";
            echo "</div>";

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>";
            echo "<h3>Error Creating Backup</h3>";
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "</div>";
        }
    }

    public function restore_from_backup()
    {
        if (!$this->input->post('confirm_restore')) {
            echo "<h1>Error: Confirmation required</h1>";
            echo "<p>You must confirm the restoration by checking the confirmation box.</p>";
            return;
        }

        echo "<h1>Restoring from Backup</h1>";
        
        try {
            // Check if backup exists
            $backup_exists = $this->db->query("SHOW TABLES LIKE 'agreement_backup_incomplete'")->num_rows() > 0;
            
            if (!$backup_exists) {
                throw new Exception('No backup table found. Please create a backup first.');
            }

            // Start transaction
            $this->db->trans_start();

            // Restore agreements
            $this->db->query("
                INSERT INTO agreement 
                SELECT * FROM agreement_backup_incomplete 
                WHERE agreement_id NOT IN (SELECT agreement_id FROM agreement)
            ");

            // Complete transaction
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            echo "<div class='alert alert-success'>";
            echo "<h3>Restoration Completed Successfully!</h3>";
            echo "<p>Restored agreements from backup.</p>";
            echo "</div>";

        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo "<div class='alert alert-danger'>";
            echo "<h3>Error During Restoration</h3>";
            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "</div>";
        }
    }
}
