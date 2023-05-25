--install tablefunc extension if it not exist (for crosstab)
CREATE EXTENSION IF NOT EXISTS tablefunc;

-- create temporary activity_companies table from dinamic column
DO
$$
    DECLARE
        column_names text;
        column_definitions text;
    BEGIN
        -- get column names for the crosstab SELECT
        SELECT string_agg('"' || activity || '"', ', ')
        INTO column_names
        FROM (SELECT distinct activity FROM companies order by 1) as activity_names;

        -- get column definitions for the crosstab table
        SELECT string_agg('"' || activity || '" text', ', ')
        INTO column_definitions
        FROM (SELECT distinct activity FROM companies order by 1) as activity_names;

        -- create the activity_companies temporary table
        EXECUTE 'CREATE TEMPORARY TABLE activity_companies AS
        SELECT ' || column_names || '
        FROM crosstab(
            ''SELECT "companyId", activity, "companyName"
             FROM companies
             ORDER BY 1'',
            ''SELECT DISTINCT activity FROM companies ORDER BY 1''
        ) AS ct ("companyId" text,' || column_definitions || ');';
    END;
$$;

-- get crosstab output from activity_companies
SELECT *
FROM activity_companies;
