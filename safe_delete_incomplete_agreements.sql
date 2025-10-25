-- =====================================================
-- SAFE DELETE INCOMPLETE AGREEMENTS (WITH BACKUP)
-- =====================================================
-- This script safely deletes incomplete agreements with backup
-- Run this if you want to be extra careful

-- =====================================================
-- STEP 1: CREATE BACKUP TABLES
-- =====================================================

-- Backup agreements
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
);

-- Backup agreement locations
CREATE TABLE IF NOT EXISTS agreement_location_backup_incomplete AS 
SELECT al.*, NOW() as backup_created_at FROM agreement_location al
INNER JOIN agreement a ON al.agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    NOT EXISTS (SELECT 1 FROM agreement_location al2 WHERE al2.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = a.agreement_id)
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- Backup agreement items
CREATE TABLE IF NOT EXISTS agreement_item_backup_incomplete AS 
SELECT ai.*, NOW() as backup_created_at FROM agreement_item ai
INNER JOIN agreement a ON ai.agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai2 WHERE ai2.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = a.agreement_id)
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- Backup vehicles
CREATE TABLE IF NOT EXISTS vehicle_backup_incomplete AS 
SELECT v.*, NOW() as backup_created_at FROM vehicle v
INNER JOIN agreement a ON v.vehicle_agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM vehicle v2 WHERE v2.vehicle_agreement_id = a.agreement_id)
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- Backup trips
CREATE TABLE IF NOT EXISTS trip_backup_incomplete AS 
SELECT t.*, NOW() as backup_created_at FROM trip t
INNER JOIN agreement a ON t.agreement_id = a.agreement_id
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
);

-- Backup chainage
CREATE TABLE IF NOT EXISTS chainage_backup_incomplete AS 
SELECT c.*, NOW() as backup_created_at FROM chainage c
INNER JOIN agreement_location al ON c.chainage_agr_loc_id = al.agreement_location_id
INNER JOIN agreement a ON al.agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    NOT EXISTS (SELECT 1 FROM agreement_location al2 WHERE al2.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = a.agreement_id)
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- =====================================================
-- STEP 2: SHOW WHAT WILL BE DELETED
-- =====================================================

SELECT 'BACKUP COMPLETED - REVIEW THE FOLLOWING BEFORE DELETION:' as status;

-- Show agreements that will be deleted
SELECT 
    'Agreements to be deleted:' as info,
    COUNT(*) as count
FROM agreement_backup_incomplete;

-- Show related data that will be deleted
SELECT 
    'Related data to be deleted:' as info,
    'Locations' as type,
    COUNT(*) as count
FROM agreement_location_backup_incomplete
UNION ALL
SELECT 
    'Related data to be deleted:' as info,
    'Items' as type,
    COUNT(*) as count
FROM agreement_item_backup_incomplete
UNION ALL
SELECT 
    'Related data to be deleted:' as info,
    'Vehicles' as type,
    COUNT(*) as count
FROM vehicle_backup_incomplete
UNION ALL
SELECT 
    'Related data to be deleted:' as info,
    'Trips' as type,
    COUNT(*) as count
FROM trip_backup_incomplete
UNION ALL
SELECT 
    'Related data to be deleted:' as info,
    'Chainage' as type,
    COUNT(*) as count
FROM chainage_backup_incomplete;

-- =====================================================
-- STEP 3: ACTUAL DELETION (UNCOMMENT TO EXECUTE)
-- =====================================================

-- Uncomment the following lines to actually delete the incomplete agreements
-- Make sure you have reviewed the backup data first!

/*
-- Delete trips first
DELETE t FROM trip t
INNER JOIN agreement a ON t.agreement_id = a.agreement_id
WHERE a.agreement_id IN (SELECT agreement_id FROM agreement_backup_incomplete);

-- Delete chainage
DELETE c FROM chainage c
INNER JOIN agreement_location al ON c.chainage_agr_loc_id = al.agreement_location_id
WHERE al.agreement_id IN (SELECT agreement_id FROM agreement_backup_incomplete);

-- Delete vehicles
DELETE v FROM vehicle v
WHERE v.vehicle_agreement_id IN (SELECT agreement_id FROM agreement_backup_incomplete);

-- Delete agreement items
DELETE ai FROM agreement_item ai
WHERE ai.agreement_id IN (SELECT agreement_id FROM agreement_backup_incomplete);

-- Delete agreement locations
DELETE al FROM agreement_location al
WHERE al.agreement_id IN (SELECT agreement_id FROM agreement_backup_incomplete);

-- Delete agreements
DELETE a FROM agreement a
WHERE a.agreement_id IN (SELECT agreement_id FROM agreement_backup_incomplete);

-- Show final count
SELECT 'DELETION COMPLETED' as status;
SELECT COUNT(*) as remaining_agreements FROM agreement WHERE date_of_completion IS NULL;
*/

-- =====================================================
-- RESTORE FROM BACKUP (if needed)
-- =====================================================

-- If you need to restore from backup, uncomment and run these:
/*
-- Restore agreements
INSERT INTO agreement SELECT * FROM agreement_backup_incomplete WHERE 1=1;

-- Restore agreement locations
INSERT INTO agreement_location SELECT * FROM agreement_location_backup_incomplete WHERE 1=1;

-- Restore agreement items
INSERT INTO agreement_item SELECT * FROM agreement_item_backup_incomplete WHERE 1=1;

-- Restore vehicles
INSERT INTO vehicle SELECT * FROM vehicle_backup_incomplete WHERE 1=1;

-- Restore trips
INSERT INTO trip SELECT * FROM trip_backup_incomplete WHERE 1=1;

-- Restore chainage
INSERT INTO chainage SELECT * FROM chainage_backup_incomplete WHERE 1=1;
*/
