-- Allows a verified cargo owner to complete province/city in the next
-- onboarding step. Back up the database and run this once before deploying
-- the Cargo app/API routes.
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

ALTER TABLE `companies`
  ADD CONSTRAINT `fk_companies_city`
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);
