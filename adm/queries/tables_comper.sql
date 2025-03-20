INSERT INTO administracao.catalog_table_hist (
    change_type,
    object_name,
    new_name,
    date_collect,
    date_processing,
    data_base,
    host_name,
    service_name,
    schema_name,
    object_id
)
WITH current_tables AS (
    SELECT 
        data_base,
        host_name,
        service_name,
        schema_name,
        object_id,
        table_name,
        MAX(date_collect) AS date_collect
    FROM administracao.catalog_table_content
    GROUP BY data_base, host_name, service_name, schema_name, object_id, table_name
),
old_tables AS (
    SELECT 
        data_base,
        host_name,
        service_name,
        schema_name,
        object_id,
        table_name,
        MAX(date_collect) AS date_collect
    FROM administracao.catalog_table_content_comper
    GROUP BY data_base, host_name, service_name, schema_name, object_id, table_name
),
dados AS (
    -- Tabelas adicionadas na carga atual
    SELECT 
        'Nova tabela' AS change_type,
        current_tables.table_name AS object_name,
        NULL::text AS new_name,
        current_tables.date_collect,
        now() AS date_processing,
        current_tables.data_base,
        current_tables.host_name,
        current_tables.service_name,
        current_tables.schema_name,
        current_tables.object_id
    FROM current_tables
    LEFT JOIN old_tables
        ON current_tables.data_base    = old_tables.data_base
       AND current_tables.host_name    = old_tables.host_name
       AND current_tables.service_name = old_tables.service_name
       AND current_tables.schema_name  = old_tables.schema_name
       AND current_tables.object_id    = old_tables.object_id
    WHERE old_tables.data_base IS NULL

    UNION ALL

    -- Tabelas removidas (presentes na carga anterior e ausentes na atual)
    SELECT 
        'Tabela removida' AS change_type,
        old_tables.table_name AS object_name,
        NULL::text AS new_name,
        old_tables.date_collect,
        now() AS date_processing,
        old_tables.data_base,
        old_tables.host_name,
        old_tables.service_name,
        old_tables.schema_name,
        old_tables.object_id
    FROM old_tables
    LEFT JOIN current_tables
        ON current_tables.data_base    = old_tables.data_base
       AND current_tables.host_name    = old_tables.host_name
       AND current_tables.service_name = old_tables.service_name
       AND current_tables.schema_name  = old_tables.schema_name
       AND current_tables.object_id    = old_tables.object_id
    WHERE current_tables.data_base IS NULL

    UNION ALL

    -- Tabelas que sofreram alteração de nome
    SELECT 
        'Renomeado' AS change_type,
        old_tables.table_name AS object_name,
        current_tables.table_name AS new_name,
        current_tables.date_collect,
        now() AS date_processing,
        current_tables.data_base,
        current_tables.host_name,
        current_tables.service_name,
        current_tables.schema_name,
        current_tables.object_id
    FROM current_tables
    INNER JOIN old_tables
        ON current_tables.data_base    = old_tables.data_base
       AND current_tables.host_name    = old_tables.host_name
       AND current_tables.service_name = old_tables.service_name
       AND current_tables.schema_name  = old_tables.schema_name
       AND current_tables.object_id    = old_tables.object_id
    WHERE current_tables.table_name <> old_tables.table_name
)
SELECT d.*
FROM dados d
WHERE NOT EXISTS (
    SELECT 1
    FROM administracao.catalog_table_hist h
    WHERE 1=1
      AND h.change_type    = d.change_type
      AND h.object_name    = d.object_name
      AND h.new_name       = d.new_name
      AND h.date_collect   = d.date_collect
      AND h.date_processing= d.date_processing
      AND h.data_base      = d.data_base
      AND h.host_name      = d.host_name
      AND h.service_name   = d.service_name
      AND h.schema_name    = d.schema_name
      AND h.object_id      = d.object_id
)

