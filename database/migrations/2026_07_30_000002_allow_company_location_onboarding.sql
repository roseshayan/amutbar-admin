-- Allows a verified cargo owner to leave province/city empty during signup
-- and complete them later from the profile. Existing foreign keys are kept;
-- adding them again would cause errno 121 (duplicate constraint name).
ALTER TABLE `companies`
  MODIFY `province_id` int UNSIGNED NULL,
  MODIFY `city_id` bigint UNSIGNED NULL;

UPDATE `companies` c
LEFT JOIN `provinces` p ON p.id = c.province_id
SET c.province_id = NULL
WHERE c.province_id IS NOT NULL AND p.id IS NULL;

UPDATE `companies` c
LEFT JOIN `cities` ci ON ci.id = c.city_id
SET c.city_id = NULL
WHERE c.city_id IS NOT NULL AND ci.id IS NULL;
