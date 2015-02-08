CREATE TABLE task (
  id             INT    UNSIGNED  NOT NULL auto_increment,
  title          VARCHAR(50)      NOT NULL,
  priority       INT    UNSIGNED  NOT NULL,
  notes          VARCHAR(500)     NOT NULL,
  date_added     TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_requested DATE,
  added_by       TINYINT UNSIGNED NOT NULL,
  requested_by   VARCHAR(100)     NOT NULL,
  assigned_to    TINYINT UNSIGNED,
  date_completed DATE,
  effort         INT    UNSIGNED,
  cost           DECIMAL(8,2),
    PRIMARY KEY (id),
    FOREIGN KEY fk_task_added_resident (added_by)
      REFERENCES resident(id),
    FOREIGN KEY fk_task_assign_resident (assigned_to)
      REFERENCES resident(id)
) ENGINE = InnoDB;
