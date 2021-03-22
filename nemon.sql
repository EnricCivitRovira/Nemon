CREATE SCHEMA `nemon`;
USE `nemon`;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteUrls`()
BEGIN
	DELETE FROM urls;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getUrls`()
BEGIN
	SELECT 
		url AS Url,
		urlCount AS UrlCount
	FROM urls
    ORDER BY urlCount DESC;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertUpdateUrl`(
IN pUrl VARCHAR(45)
)
BEGIN

INSERT INTO `nemon`.`urls` (`url`,`urlCount`) 
	VALUES (pUrl, 1)
    ON DUPLICATE KEY UPDATE updateDate = NOW(), urlCount = urlCount + 1;

        
END$$
DELIMITER ;

CREATE TABLE `urls` (
  `idurls` bigint(20) NOT NULL AUTO_INCREMENT,
  `url` varchar(45) NOT NULL,
  `urlCount` bigint(20) DEFAULT NULL,
  `creationDate` datetime DEFAULT current_timestamp(),
  `updateDate` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`url`),
  KEY `idurls` (`idurls`)
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=utf8;
