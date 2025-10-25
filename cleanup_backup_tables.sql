-- =====================================================
-- CLEANUP BACKUP TABLES
-- =====================================================
-- Run this script to clean up backup tables after you're sure
-- the deletion was successful and you don't need the backups anymore

-- WARNING: This will permanently delete the backup tables
-- Make sure you don't need the backup data anymore

-- Drop backup tables
DROP TABLE IF EXISTS agreement_backup_incomplete;
DROP TABLE IF EXISTS agreement_location_backup_incomplete;
DROP TABLE IF EXISTS agreement_item_backup_incomplete;
DROP TABLE IF EXISTS vehicle_backup_incomplete;
DROP TABLE IF EXISTS trip_backup_incomplete;
DROP TABLE IF EXISTS chainage_backup_incomplete;

-- Show remaining tables
SHOW TABLES LIKE '%backup%';
