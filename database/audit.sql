CREATE TABLE audit (
  id         INT    UNSIGNED  NOT NULL auto_increment,
  date_time  TIMESTAMP        NOT NULL,
  action     VARCHAR(20)      NOT NULL,
  user       TINYINT UNSIGNED NOT NULL,
  subject    VARCHAR(50),
    PRIMARY KEY (id),
    FOREIGN KEY fk_audit_resident (user)
      REFERENCES resident(id)
      ON DELETE NO ACTION

) ENGINE = InnoDB;
