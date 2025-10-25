-- =====================================================
-- DELETE INCOMPLETE AGREEMENTS
-- =====================================================
-- WARNING: This script will permanently delete incomplete agreements
-- Make sure to backup your database before running this script
-- Run the find_incomplete_agreements.sql first to see what will be deleted

-- Create a backup table first (optional but recommended)
-- CREATE TABLE agreement_backup AS SELECT * FROM agreement WHERE date_of_completion IS NULL;

-- =====================================================
-- STEP 1: DELETE RELATED DATA FIRST (to avoid foreign key constraints)
-- =====================================================

-- Delete trips related to incomplete agreements
DELETE t FROM trip t
INNER JOIN agreement a ON t.agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    -- Agreements with no locations
    NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
    -- Agreements with no items
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
    -- Agreements with no vehicles
    OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = a.agreement_id)
    -- Agreements with zero amount
    OR (a.amount IS NULL OR a.amount = 0)
    -- Agreements with missing required fields
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- Delete chainage data for incomplete agreements
DELETE c FROM chainage c
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

-- Delete vehicles for incomplete agreements
DELETE v FROM vehicle v
INNER JOIN agreement a ON v.vehicle_agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- Delete agreement items for incomplete agreements
DELETE ai FROM agreement_item ai
INNER JOIN agreement a ON ai.agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- Delete agreement locations for incomplete agreements
DELETE al FROM agreement_location al
INNER JOIN agreement a ON al.agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- =====================================================
-- STEP 2: DELETE THE INCOMPLETE AGREEMENTS
-- =====================================================

-- Delete incomplete agreements
DELETE a FROM agreement a
WHERE a.date_of_completion IS NULL
AND (
    -- No locations
    NOT EXISTS (SELECT 1 FROM agreement_location al WHERE al.agreement_id = a.agreement_id)
    -- No items
    OR NOT EXISTS (SELECT 1 FROM agreement_item ai WHERE ai.agreement_id = a.agreement_id)
    -- No vehicles
    OR NOT EXISTS (SELECT 1 FROM vehicle v WHERE v.vehicle_agreement_id = a.agreement_id)
    -- Zero amount
    OR (a.amount IS NULL OR a.amount = 0)
    -- Missing required fields
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
);

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check how many agreements remain
SELECT COUNT(*) as remaining_agreements FROM agreement WHERE date_of_completion IS NULL;

-- Check for any remaining incomplete agreements
SELECT 
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    CASE 
        WHEN al.agreement_location_id IS NULL THEN 'Missing Locations'
        WHEN ai.agreement_item_id IS NULL THEN 'Missing Items'
        WHEN v.vehicle_id IS NULL THEN 'Missing Vehicles'
        WHEN (a.amount IS NULL OR a.amount = 0) THEN 'Zero Amount'
        ELSE 'Complete'
    END as status
FROM agreement a
LEFT JOIN agreement_location al ON al.agreement_id = a.agreement_id
LEFT JOIN agreement_item ai ON ai.agreement_id = a.agreement_id
LEFT JOIN vehicle v ON v.vehicle_agreement_id = a.agreement_id
WHERE a.date_of_completion IS NULL
AND (
    al.agreement_location_id IS NULL
    OR ai.agreement_item_id IS NULL
    OR v.vehicle_id IS NULL
    OR (a.amount IS NULL OR a.amount = 0)
);
