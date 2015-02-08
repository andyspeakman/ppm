CREATE TABLE message (
  id             INT    UNSIGNED  NOT NULL auto_increment,
  title          VARCHAR(50)      NOT NULL,
  notes          VARCHAR(500)     NOT NULL,
  date_added     TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  added_by       TINYINT UNSIGNED NOT NULL,
  date_expires   DATE,
  expired        CHAR(1)          NOT NULL DEFAULT 'N',
    PRIMARY KEY (id),
    FOREIGN KEY fk_msg_resident (added_by)
      REFERENCES resident(id)
) ENGINE = InnoDB;
