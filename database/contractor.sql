CREATE TABLE contractor (
  id               INT      UNSIGNED  NOT NULL auto_increment,
  contractor_type  TINYINT  UNSIGNED  NOT NULL,
  company_name     VARCHAR(50)        NOT NULL,
  website          VARCHAR(100),
  company_telno    VARCHAR(20),
  contact_name     VARCHAR(50),
  contact_telno    VARCHAR(20),
  notes            VARCHAR(200),
  added_by         TINYINT  UNSIGNED  NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY fk_cont_resident (added_by)
      REFERENCES resident(id),
    FOREIGN KEY fk_cont_type (contractor_type)
      REFERENCES contractor_type(id)
) ENGINE = InnoDB;
