TRUNCATE TABLE audit;
TRUNCATE TABLE contractor;
TRUNCATE TABLE document;
TRUNCATE TABLE message;
TRUNCATE TABLE task;
TRUNCATE TABLE work_record;
TRUNCATE TABLE resident_list;


ALTER TABLE audit AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE contractor AUTO_INCREMENT = 1;
ALTER TABLE contractor_type AUTO_INCREMENT = 1;
ALTER TABLE document AUTO_INCREMENT = 1;

INSERT INTO users (email, name, password, flat, role) VALUES ('andy.speakman@gmail.com', 'Andy Speakman', 'f448763397d1fff4a45c6f443223e967', '4', 'admin');
INSERT INTO users (email, name, password, flat, role) VALUES ('nadine.hope@locall.net', 'Nadine Hope', MD5('salieri'), '27', 'director');
INSERT INTO users (email, name, password, flat, role) VALUES ('peter@peterbates.co.uk', 'Peter Bates', MD5('attwood2'), '30B', 'director');
INSERT INTO users (email, name, password, flat, role) VALUES ('maggieocarroll@yahoo.com', 'Maggie O''Carroll', MD5('cjcregg'), '7', 'director');
INSERT INTO users (email, name, password, flat, role) VALUES ('nick.howe@uniform.net', 'Nick Howe', MD5('lampshades'), '32', 'director');
INSERT INTO users (email, name, password, flat, role) VALUES ('morgangill87@yahoo.co.uk', 'Gill Morgan', MD5('nadinehope'), '30A', 'director');
INSERT INTO users (email, name, password, flat, role) VALUES ('emmamacnair@doctors.org.uk', 'Emma MacNair', MD5('ppm7b'), '7B', 'director');

INSERT INTO users (email, name, password, flat, role) VALUES ('pfoster23@yahoo.co.uk', 'Paul Foster', MD5('mansions'), '23', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('alunlj@hotmail.com', 'Alun Jones', MD5('jasper'), '2', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('lisdavidson@yahoo.co.uk', 'Lis Davidson', MD5('oelc'), '34', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('fjones@liverpool.ac.uk', 'Frederick Jones', MD5('modudoxx'), '28', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('caruthchris@aol.com', 'Christine Ruth', MD5('florio'), '15', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('barbarashields@btinternet.com', 'Barbara Shields', MD5('barbara'), '2B', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('poornaseva@gmail.com', 'Phil Cullen', MD5('selenium'), '30', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('ronram.stewart1@btinternet.com', 'Ron Stewart', MD5('ronramstewart'), '24', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('gkidd@fastmail.fm', 'George Kidd', MD5('qwerty123'), '1B', 'shareholder');

INSERT INTO users (email, name, password, flat, role) VALUES ('testshareholder@ppm.com', 'Test Shareholder', MD5('testshareholder'), '99', 'shareholder');
INSERT INTO users (email, name, password, flat, role) VALUES ('testdirector@ppm.com', 'Test Director', MD5('testdirector'), '99', 'director');

INSERT INTO document_type (id, folder, name) VALUES (1, '/documents/minutes', 'Directors Meeting Minutes');
INSERT INTO document_type (id, folder, name) VALUES (2, '/documents/minutes', 'AGM Minutes');
INSERT INTO document_type (id, folder, name) VALUES (3, '/documents/bulletins', 'Shareholders Bulletin');
INSERT INTO document_type (id, folder, name) VALUES (4, '/documents/repairs', 'Repairs & Renewals');
INSERT INTO document_type (id, folder, name) VALUES (5, '/documents/shareholders/correspondence', 'Shareholders Correspondence');
INSERT INTO document_type (id, folder, name) VALUES (6, '/documents/contractors/invoices', 'Invoices');
INSERT INTO document_type (id, folder, name) VALUES (7, '/documents/contractors/reports', 'Contractor Reports');
INSERT INTO document_type (id, folder, name) VALUES (8, '/documents/tax', 'Tax Affairs');

INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (5, '2009-03-15', 'Richard West - Water Ingress', 'rwest-20090315.pdf', 488);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (5, '2009-01-19', 'Christine Ruth - Redecoration', 'cruth-20090119.pdf', 184);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (5, '2009-04-06', 'Alun Lloyd-Jones - Water Ingress', 'ajones-20090406.pdf', 212);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (5, '2009-03-21', 'Richard West - Insurance', 'rwest-20090321.pdf', 220);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (5, '2009-03-24', 'Gina Grunenberg - Garage Ivy', 'ggruenberg-20090324.pdf', 548);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (5, '2009-06-30', 'Richard West - Insurance Invalidation', 'rwest-20090630.pdf', 488);

INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2009-03-31', 'Lanes - Jet Vacuumation/CCTV', 'lanes-20090331.pdf', 316);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2007-11-16', 'D A Hampson - Basement Lighting', 'dahampson-20071116.pdf', 184);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2008-03-17', 'D A Hampson - Flood Lighting', 'dahampson-20080317.pdf', 160);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2009-05-05', 'British Gas - March/April 09', 'bgas-20090505.pdf', 544);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2008-11-06', 'Aintree Plumbing & Heating - Boiler Room Pipework', 'aintree-20081106.pdf', 156);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2009-04-30', 'Survey Operations - Surveys and CAD', 'survops-20090430.pdf', 392);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2009-05-31', 'Blundellsands - Bookkeeping & Statements', 'bsp-20090531.pdf', 148);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2009-06-10', 'Paul Bridson - Entry Phone Repair', 'bridson-20090610.pdf', 128);
INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (6, '2009-06-23', 'P Thomas - Drainage repairs', 'pthomas-20090623.pdf', 164);

INSERT INTO document (doc_type, doc_date, title, path, size) VALUES (7, '2009-03-09', 'Lanes - Customer Acceptance Report', 'lanes-20090309.pdf', 284);

INSERT INTO contractor_type (name) VALUES ('Aerials');
INSERT INTO contractor_type (name) VALUES ('Architects');
INSERT INTO contractor_type (name) VALUES ('Cleaners');
INSERT INTO contractor_type (name) VALUES ('Drains');
INSERT INTO contractor_type (name) VALUES ('Electricians');
INSERT INTO contractor_type (name) VALUES ('Insurance');
INSERT INTO contractor_type (name) VALUES ('Joiners');
INSERT INTO contractor_type (name) VALUES ('Miscellaneous');
INSERT INTO contractor_type (name) VALUES ('Pest Control');
INSERT INTO contractor_type (name) VALUES ('Plumbers');
INSERT INTO contractor_type (name) VALUES ('Property Management');
INSERT INTO contractor_type (name) VALUES ('Roofers');
INSERT INTO contractor_type (name) VALUES ('Security');
INSERT INTO contractor_type (name) VALUES ('Surveyors');
INSERT INTO contractor_type (name) VALUES ('Trees & Gardens');
INSERT INTO contractor_type (name) VALUES ('Decorators');
INSERT INTO contractor_type (name) VALUES ('Plasterers');
INSERT INTO contractor_type (name) VALUES ('Metalworkers');

