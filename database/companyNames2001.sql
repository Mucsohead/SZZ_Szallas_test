-- get companies from 2001-01-01 until today
SELECT d.date, STRING_AGG(c."companyName", ', ') AS companies
FROM
    (SELECT generate_series('2001-01-01'::date, NOW(), '1 day')::date AS date) AS d
        LEFT JOIN companies c ON d.date = c."companyFoundationDate"
GROUP BY d.date
ORDER BY d.date asc;
