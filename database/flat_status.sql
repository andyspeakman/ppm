CREATE TABLE flat_status (
  id      TINYINT UNSIGNED NOT NULL  AUTO_INCREMENT COMMENT 'Unique ID of type, used as primary key.',
  name    VARCHAR(50)      NOT NULL                 COMMENT 'Description of type.',
    PRIMARY KEY (id)

) ENGINE = INNODB;
