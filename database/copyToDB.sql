-- copy testCompanyDB (need to change the path)
copy companies from '/home/szolzoli/test/testSzallasPHP7/database/testCompanyDB.csv' DELIMITER ';' CSV HEADER;

-- set autoinc for the last companyId
SELECT setval('"companies_companyId_seq"', (SELECT MAX("companyId") FROM companies));


