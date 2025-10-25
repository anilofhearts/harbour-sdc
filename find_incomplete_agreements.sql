-- =====================================================
-- FIND INCOMPLETE AGREEMENTS
-- =====================================================
-- This script identifies agreements that are incomplete
-- Run this first to see what will be deleted

-- 1. AGREEMENTS WITH NO LOCATIONS
SELECT 
    'Missing Locations' as issue_type,
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
LEFT JOIN agreement_location al ON al.agreement_id = a.agreement_id
WHERE al.agreement_location_id IS NULL
AND a.date_of_completion IS NULL;

-- 2. AGREEMENTS WITH NO ITEMS
SELECT 
    'Missing Items' as issue_type,
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
LEFT JOIN agreement_item ai ON ai.agreement_id = a.agreement_id
WHERE ai.agreement_item_id IS NULL
AND a.date_of_completion IS NULL;

-- 3. AGREEMENTS WITH NO VEHICLES
SELECT 
    'Missing Vehicles' as issue_type,
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
LEFT JOIN vehicle v ON v.vehicle_agreement_id = a.agreement_id
WHERE v.vehicle_id IS NULL
AND a.date_of_completion IS NULL;

-- 4. AGREEMENTS WITH NO CHAINAGE DATA
SELECT 
    'Missing Chainage' as issue_type,
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
LEFT JOIN agreement_location al ON al.agreement_id = a.agreement_id
LEFT JOIN chainage c ON c.chainage_agr_loc_id = al.agreement_location_id
WHERE c.chainage_id IS NULL
AND a.date_of_completion IS NULL;

-- 5. AGREEMENTS WITH ZERO OR NULL AMOUNT
SELECT 
    'Zero Amount' as issue_type,
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
WHERE (a.amount IS NULL OR a.amount = 0)
AND a.date_of_completion IS NULL;

-- 6. AGREEMENTS WITH MISSING REQUIRED FIELDS
SELECT 
    'Missing Required Fields' as issue_type,
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
WHERE (a.agreement IS NULL OR a.agreement = '')
OR (a.agreement_no IS NULL OR a.agreement_no = '')
OR (a.date_of_agreement IS NULL)
OR (a.date_of_commencement IS NULL)
AND a.date_of_completion IS NULL;

-- 7. COMPREHENSIVE INCOMPLETE AGREEMENTS LIST
SELECT DISTINCT
    a.agreement_id,
    a.agreement_no,
    a.agreement,
    a.amount,
    a.section_id,
    s.section,
    CASE 
        WHEN al.agreement_location_id IS NULL THEN 'Missing Locations'
        WHEN ai.agreement_item_id IS NULL THEN 'Missing Items'
        WHEN v.vehicle_id IS NULL THEN 'Missing Vehicles'
        WHEN c.chainage_id IS NULL THEN 'Missing Chainage'
        WHEN (a.amount IS NULL OR a.amount = 0) THEN 'Zero Amount'
        WHEN (a.agreement IS NULL OR a.agreement = '') THEN 'Missing Agreement Name'
        WHEN (a.agreement_no IS NULL OR a.agreement_no = '') THEN 'Missing Agreement Number'
        ELSE 'Other Issues'
    END as issue_type
FROM agreement a
LEFT JOIN section s ON s.section_id = a.section_id
LEFT JOIN agreement_location al ON al.agreement_id = a.agreement_id
LEFT JOIN agreement_item ai ON ai.agreement_id = a.agreement_id
LEFT JOIN vehicle v ON v.vehicle_agreement_id = a.agreement_id
LEFT JOIN agreement_location al2 ON al2.agreement_id = a.agreement_id
LEFT JOIN chainage c ON c.chainage_agr_loc_id = al2.agreement_location_id
WHERE a.date_of_completion IS NULL
AND (
    al.agreement_location_id IS NULL
    OR ai.agreement_item_id IS NULL
    OR v.vehicle_id IS NULL
    OR c.chainage_id IS NULL
    OR (a.amount IS NULL OR a.amount = 0)
    OR (a.agreement IS NULL OR a.agreement = '')
    OR (a.agreement_no IS NULL OR a.agreement_no = '')
    OR a.date_of_agreement IS NULL
    OR a.date_of_commencement IS NULL
)
ORDER BY a.agreement_id;
