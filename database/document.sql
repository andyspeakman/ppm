CREATE TABLE document (
  id        SMALLINT UNSIGNED  NOT NULL AUTO_INCREMENT COMMENT 'Unique ID of document.',
  doc_type  TINYINT UNSIGNED   NOT NULL                COMMENT 'Foreign key to DOCUMENT_TYPE.',
  date_time TIMESTAMP          NOT NULL                COMMENT 'Timestamp when the document was added to the database',
  doc_date  DATE               NOT NULL                COMMENT 'Date that the document was created.',
  title     VARCHAR(100)       NOT NULL                COMMENT 'Title of the document',
  path      VARCHAR(100)       NOT NULL                COMMENT 'Path to the document on the server, from the root.',
  size      SMALLINT UNSIGNED  NOT NULL                COMMENT 'Size of the document, in KB.', 
    PRIMARY KEY (id),
    FOREIGN KEY fk_doc_type (doc_type)
      REFERENCES document_type (id)
      ON DELETE CASCADE
) ENGINE = InnoDB;