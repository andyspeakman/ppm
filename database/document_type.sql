CREATE TABLE document_type (
  id      TINYINT UNSIGNED NOT NULL,
  folder  VARCHAR(50)      NOT NULL,
  name    VARCHAR(50)      NOT NULL,
  groups  VARCHAR(50)      NOT NULL DEFAULT 'admin, director',
    PRIMARY KEY (id)

) ENGINE = INNODB;
