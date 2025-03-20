<?php
namespace App\Queries;
class EmployeeQueries
{
    // Fix in your Laravel controller or repository
// Tambahkan method ini di dalam class App\Queries\EmployeeQueries

/**
 * Get the SQL query for retrieving user roles based on TOA data
 *
 * @return string The SQL query
 */
public static function getRoleToaQuery(): string
{
    return '
        WITH combined_apps AS (
            SELECT
                megi."BP",
                megi.email,
                megi."name",
                ma.app_id,
                ma.app_name,
                ma.app_url,
                ma.img_url,
                ma.environment,
                met.title_id,
                met.type,
                met.seq_nbr,
                met.start_effective_date,
                met.end_effective_date,
                met.remark,
                mcch.cost_center_name,
                \'employee_mapping\' as source
            FROM m_employee_general_info AS megi
            JOIN map_employee_application AS mea ON megi."BP" = mea."PARTNER"
            JOIN map_employee_title AS met ON megi."BP" = met."BP"
            JOIN m_application AS ma ON ma.app_id = mea.app_id
            JOIN map_cost_center_hierarchy AS mcch ON met.cost_center = mcch.cost_center
            WHERE LOWER(megi.email) = LOWER(?)
            AND CURRENT_DATE BETWEEN met.start_effective_date
                AND COALESCE(met.end_effective_date, \'9999-12-31\'::date)

            UNION ALL

            SELECT
                megi."BP",
                megi.email,
                megi."name",
                ma.app_id,
                ma.app_name,
                ma.app_url,
                ma.img_url,
                ma.environment,
                met.title_id,
                met.type,
                met.seq_nbr,
                met.start_effective_date,
                met.end_effective_date,
                met.remark,
                mcch.cost_center_name,
                \'cost_center_mapping\' as source
            FROM m_employee_general_info AS megi
            JOIN map_employee_title AS met ON megi."BP" = met."BP"
            JOIN map_cost_center_application AS mcca ON met.cost_center = mcca.cost_center
            JOIN m_application AS ma ON ma.app_id = mcca.app_id
            JOIN map_cost_center_hierarchy AS mcch ON met.cost_center = mcch.cost_center
            WHERE LOWER(megi.email) = LOWER(?)
            AND CURRENT_DATE BETWEEN met.start_effective_date
                AND COALESCE(met.end_effective_date, \'9999-12-31\'::date)
        )
        SELECT DISTINCT ON (app_id) *
        FROM combined_apps
        ORDER BY app_id, seq_nbr DESC;
    ';
}

    public static function getRoleUserDotsQuery()
    {
        return "
            WITH RECURSIVE org_hierarchy AS (
                SELECT
                    cost_center,
                    cost_center_dh,
                    cost_center_gh
                FROM map_cost_center_hierarchy
                WHERE (cost_center = cost_center_dh OR cost_center = cost_center_gh)
                    AND CURRENT_DATE BETWEEN COALESCE(start_effective_date, '2023-01-01'::date)
                                        AND COALESCE(end_effective_date, '9999-12-31'::date)
            )
            SELECT DISTINCT \"BP\",
                em_cost_center,
                cost_center,
                user_type
            FROM (
                SELECT DISTINCT a.\"BP\",
                    b.emp_cost_center AS em_cost_center,
                    b.job_cost_center AS cost_center,
                    'IC001' AS user_type
                FROM map_employee_title a
                INNER JOIN (
                    SELECT a.cost_center AS emp_cost_center, c.cost_center AS job_cost_center
                    FROM org_hierarchy a
                    INNER JOIN org_hierarchy b ON a.cost_center_gh = b.cost_center
                    INNER JOIN org_hierarchy c ON c.cost_center_gh = b.cost_center
                    UNION
                    SELECT cost_center AS emp_cost_center,
                        cost_center AS job_cost_center
                    FROM map_cost_center_hierarchy
                    WHERE NOT cost_center = cost_center_dh AND NOT cost_center = cost_center_gh
                ) b ON a.cost_center = b.emp_cost_center
                WHERE CURRENT_DATE BETWEEN COALESCE(start_effective_date, '2023-01-01'::date) AND COALESCE(end_effective_date, '9999-12-31'::date)
                UNION
                SELECT DISTINCT a.\"BP\",
                                b.emp_cost_center AS em_cost_center,
                                b.job_cost_center AS cost_center,
                                'V0001' AS user_type
                FROM map_employee_title a
                INNER JOIN (
                    SELECT a.cost_center AS emp_cost_center, c.cost_center AS job_cost_center
                    FROM org_hierarchy a
                    INNER JOIN org_hierarchy b ON a.cost_center_gh = b.cost_center
                    INNER JOIN org_hierarchy c ON c.cost_center_gh = b.cost_center
                    WHERE a.cost_center = a.cost_center_gh
                ) b ON a.cost_center = b.emp_cost_center
                INNER JOIN m_title c ON a.title_id = c.title_id AND c.is_head = true
                WHERE CURRENT_DATE BETWEEN COALESCE(a.start_effective_date, '2023-01-01'::date) AND COALESCE(a.end_effective_date, '9999-12-31'::date)
                UNION
                SELECT DISTINCT a.\"BP\",
                                b.emp_cost_center AS em_cost_center,
                                b.job_cost_center AS cost_center,
                                'V0001' AS user_type
                FROM map_employee_title a
                INNER JOIN (
                    SELECT a.cost_center AS emp_cost_center, a.cost_center AS job_cost_center
                    FROM org_hierarchy a
                    WHERE a.cost_center = a.cost_center_dh
                ) b ON a.cost_center = b.emp_cost_center
                INNER JOIN m_title c ON a.title_id = c.title_id AND c.is_head = true
                WHERE CURRENT_DATE BETWEEN COALESCE(a.start_effective_date, '2023-01-01'::date) AND COALESCE(a.end_effective_date, '9999-12-31'::date)
                UNION
                SELECT DISTINCT a.\"BP\",
                                a.cost_center AS em_cost_center,
                                a.cost_center AS cost_center,
                                'EM001' AS user_type
                FROM map_employee_title a
                INNER JOIN map_cost_center_hierarchy b
                ON a.cost_center = b.cost_center
                    AND CURRENT_DATE BETWEEN COALESCE(b.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(b.end_effective_date, '9999-12-31'::date)
                WHERE CURRENT_DATE BETWEEN COALESCE(a.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(a.end_effective_date, '9999-12-31'::date)
                UNION
                SELECT DISTINCT a.\"BP\",
                                a.cost_center AS em_cost_center,
                                a.cost_center AS cost_center,
                                'IC001' AS user_type
                FROM map_employee_title a
                INNER JOIN map_cost_center_hierarchy b
                ON a.cost_center = b.cost_center
                    AND CURRENT_DATE BETWEEN COALESCE(b.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(b.end_effective_date, '9999-12-31'::date)
                WHERE CURRENT_DATE BETWEEN COALESCE(a.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(a.end_effective_date, '9999-12-31'::date)
                UNION
                SELECT DISTINCT a.\"BP\",
                                a.cost_center AS em_cost_center,
                                a.cost_center AS cost_center,
                                'VD001' AS user_type
                FROM map_employee_title a
                INNER JOIN map_cost_center_hierarchy b
                ON a.cost_center = b.cost_center_dh
                    AND CURRENT_DATE BETWEEN COALESCE(b.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(b.end_effective_date, '9999-12-31'::date)
                WHERE EXISTS (
                    SELECT 1
                    FROM m_title c
                    WHERE a.title_id = c.title_id
                    AND c.is_head
                ) AND CURRENT_DATE BETWEEN COALESCE(a.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(a.end_effective_date, '9999-12-31'::date)
                UNION
                SELECT DISTINCT a.\"BP\",
                                a.cost_center AS em_cost_center,
                                a.cost_center AS cost_center,
                                'VG001' AS user_type
                FROM map_employee_title a
                INNER JOIN map_cost_center_hierarchy b
                ON a.cost_center = b.cost_center_gh
                    AND CURRENT_DATE BETWEEN COALESCE(b.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(b.end_effective_date, '9999-12-31'::date)
                WHERE EXISTS (
                    SELECT 1
                    FROM m_title c
                    WHERE a.title_id = c.title_id
                    AND c.is_head
                ) AND CURRENT_DATE BETWEEN COALESCE(a.start_effective_date, '2023-01-01'::date)
                                    AND COALESCE(a.end_effective_date, '9999-12-31'::date)
            ) a
            WHERE a.\"BP\" = ?
            ORDER BY \"BP\", em_cost_center, user_type;
        ";
    }


    public static function getRoleUserCcDotsQuery()
    {
        return "
            SELECT *
            FROM
                (SELECT b.\"BP\",
                    c.\"email\",
                    c.\"name\",
                    a.cost_center,
                    a.approval1,
                    CASE
                    WHEN NOT a.approval1 = a.approval2 THEN
                    a.approval2
                    END approval2,
                    NULL approval3,
                    NULL approval4,
                    NULL approval5
                FROM
                    (
                        SELECT
                            a.cost_center,
                            COALESCE(COALESCE(a.cost_center_dh, a.cost_center_gh), a.cost_center) approval1,
                            cost_center_gh approval2
                        FROM map_cost_center_hierarchy a
                    ) a
                    INNER JOIN map_employee_title b
                        ON a.cost_center = b.cost_center
                    INNER JOIN m_employee_general_info c
                        ON b.\"BP\" = c.\"BP\"
                WHERE
                    COALESCE(b.end_effective_date, NOW() + INTERVAL '1 day') > NOW()
                    AND b.start_effective_date < NOW()
                ) a
            WHERE
                NOT (approval1 IS NULL AND approval2 IS NULL)
                AND LOWER(a.\"email\") = LOWER(?);
        ";
    }

}
