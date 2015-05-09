ALTER TABLE `filter` DROP FOREIGN KEY `fk_filter_mp`;
ALTER TABLE `filter` ADD CONSTRAINT `fk_filter_mp` FOREIGN KEY (`mp`) REFERENCES `measurementpoints` (`id`) ON DELETE CASCADE;
