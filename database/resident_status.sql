CREATE TABLE resident_status (
  id      TINYINT UNSIGNED NOT NULL  AUTO_INCREMENT COMMENT 'Unique ID of status, used as primary key.',
  name    VARCHAR(50)      NOT NULL                 COMMENT 'Description of status, e.g. Tenant, Owner, etc.',
    PRIMARY KEY (id)

) ENGINE = INNODB;
