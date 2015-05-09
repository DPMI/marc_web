ALTER TABLE `measurementpoints` MODIFY `MAMPid` VARCHAR(16);
ALTER TABLE `measurementpoints` ADD CONSTRAINT `mampid_uk` UNIQUE(`MAMPid`);
