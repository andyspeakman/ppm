CREATE TABLE flat (
  id                  VARCHAR(3)       NOT NULL                COMMENT 'Identifier of a flat - its flat number.',
  display_order       TINYINT UNSIGNED NOT NULL                COMMENT 'Numerical order of flat (taking into account A, B, etc).',
  communal_hot_water  CHAR(1)          NOT NULL                COMMENT 'Y/N flag indicating whether the flat uses communal hot water',
  status              TINYINT UNSIGNED NOT NULL                COMMENT 'Foreign key to FLAT_TYPE',
    PRIMARY KEY (id),
    FOREIGN KEY fk_flat_status (status)
      REFERENCES flat_status (id)
      ON DELETE CASCADE

) ENGINE = INNODB;
