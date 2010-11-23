SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `#__facetedvideos_facetobjects`;
DROP TABLE IF EXISTS `#__facetedvideos_facettypes`;
DROP TABLE IF EXISTS `#__facetedvideos_mediaobjects`;
DROP TABLE IF EXISTS `#__facetedvideos_mediafacetassoc`;

CREATE TABLE `#__facetedvideos_facetobjects`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`facetTypeId` INTEGER NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	`published` TINYINT NOT NULL,
	PRIMARY KEY (`id`),
	KEY (`facetTypeId`)
) ;

CREATE TABLE `#__facetedvideos_facettypes`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(150) NOT NULL,
	`showAsColumn` INTEGER NOT NULL,
	`published` TINYINT NOT NULL,
	PRIMARY KEY (`id`)
) ;

CREATE TABLE `#__facetedvideos_mediaobjects`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`filename` VARCHAR(255) NOT NULL,
	`title` VARCHAR(255) NOT NULL,
	`lenght` INTEGER NOT NULL,
	`added` TIMESTAMP DEFAULT 0,
	`published` TINYINT NOT NULL, 
	`hits` INTEGER NOT NULL,
	PRIMARY KEY (`id`)
) ;

CREATE TABLE `#__facetedvideos_mediafacetassoc`
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`mediaId` INTEGER NOT NULL,
	`facetObjectId` INTEGER NOT NULL,
	PRIMARY KEY (`id`),
	KEY (`facetObjectId`),
	KEY (`mediaId`)
) ;

SET FOREIGN_KEY_CHECKS=1;

ALTER TABLE `#__facetedvideos_facetobjects` ADD CONSTRAINT `FK_facetobjects_facettypes` 
	FOREIGN KEY (`facetTypeId`) REFERENCES `#__facetedvideos_facettypes` (`id`)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__facetedvideos_mediafacetassoc` ADD CONSTRAINT `FK_mediafacetassoc_facetobjects` 
	FOREIGN KEY (`facetObjectId`) REFERENCES `#__facetedvideos_facetobjects` (`id`)
	ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `#__facetedvideos_mediafacetassoc` ADD CONSTRAINT `FK_mediafacetassoc_mediadata` 
	FOREIGN KEY (`mediaId`) REFERENCES `#__facetedvideos_mediaobjects` (`id`)
	ON DELETE CASCADE ON UPDATE CASCADE;
