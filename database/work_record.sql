CREATE TABLE work_record (
  id             INT    UNSIGNED  NOT NULL auto_increment,
  work_date      DATE             NOT NULL,
  notes          VARCHAR(500)     NOT NULL,
  hours          TINYINT UNSIGNED NOT NULL,
  added_by       TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY fk_wr_resident (added_by)
      REFERENCES resident(id)
) ENGINE = InnoDB;
