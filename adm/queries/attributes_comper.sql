WITH current_attributes AS (
    SELECT 
        data_base,
        host_name,
        service_name,
        schema_name,
        object_id,
        table_name,
        column_id,
        column_name,
        data_type,
        data_length,
        MAX(date_collect) AS date_collect
    FROM administracao.catalog_table_content
    GROUP BY data_base, host_name, service_name, schema_name, object_id, table_name, column_id, column_name, data_type, data_length
),
old_attributes AS (
    SELECT 
        data_base,
        host_name,
        service_name,
        schema_name,
        object_id,
        table_name,
        column_id,
        column_name,
        data_type,
        data_length,
        MAX(date_collect) AS date_collect
    FROM administracao.catalog_table_content_comper
    GROUP BY data_base, host_name, service_name, schema_name, object_id, table_name, column_id, column_name, data_type, data_length
),
attribute_changes AS (
    -- Atributos adicionados
    SELECT 
        'Adicionado' AS change_type,
        current_attributes.column_name 
            || ' (' 
            || current_attributes.data_type 
            || ', ' 
            || current_attributes.data_length 
            || ')' AS object_name,
        NULL::text AS new_name,
        current_attributes.date_collect,
        now() AS date_processing,
        current_attributes.data_base,
        current_attributes.host_name,
        current_attributes.service_name,
        current_attributes.schema_name,
        current_attributes.object_id,
        current_attributes.table_name,
        current_attributes.column_id
    FROM current_attributes
    LEFT JOIN old_attributes
           ON current_attributes.data_base    = old_attributes.data_base
          AND current_attributes.host_name    = old_attributes.host_name
          AND current_attributes.service_name = old_attributes.service_name
          AND current_attributes.schema_name  = old_attributes.schema_name
          AND current_attributes.object_id    = old_attributes.object_id
          AND current_attributes.column_id    = old_attributes.column_id
    WHERE old_attributes.data_base IS NULL

    UNION ALL

    -- Atributos removidos
    SELECT 
        'Removido' AS change_type,
        old_attributes.column_name 
            || ' (' 
            || old_attributes.data_type 
            || ', ' 
            || old_attributes.data_length 
            || ')' AS object_name,
        NULL::text AS new_name,
        old_attributes.date_collect,
        now() AS date_processing,
        old_attributes.data_base,
        old_attributes.host_name,
        old_attributes.service_name,
        old_attributes.schema_name,
        old_attributes.object_id,
        old_attributes.table_name,
        old_attributes.column_id
    FROM old_attributes
    LEFT JOIN current_attributes
           ON current_attributes.data_base    = old_attributes.data_base
          AND current_attributes.host_name    = old_attributes.host_name
          AND current_attributes.service_name = old_attributes.service_name
          AND current_attributes.schema_name  = old_attributes.schema_name
          AND current_attributes.object_id    = old_attributes.object_id
          AND current_attributes.column_id    = old_attributes.column_id
    WHERE current_attributes.data_base IS NULL

    UNION ALL

    -- Atributos alterados (nome, tipo ou tamanho)
    SELECT 
        'Alterado' AS change_type,
        old_attributes.column_name 
            || ' (' 
            || old_attributes.data_type 
            || ', ' 
            || old_attributes.data_length 
            || ')' AS object_name,
        current_attributes.column_name 
            || ' (' 
            || current_attributes.data_type 
            || ', ' 
            || current_attributes.data_length 
            || ')' AS new_name,
        current_attributes.date_collect,
        now() AS date_processing,
        current_attributes.data_base,
        current_attributes.host_name,
        current_attributes.service_name,
        current_attributes.schema_name,
        current_attributes.object_id,
        current_attributes.table_name,
        current_attributes.column_id
    FROM current_attributes
    INNER JOIN old_attributes
            ON current_attributes.data_base    = old_attributes.data_base
           AND current_attributes.host_name    = old_attributes.host_name
           AND current_attributes.service_name = old_attributes.service_name
           AND current_attributes.schema_name  = old_attributes.schema_name
           AND current_attributes.object_id    = old_attributes.object_id
           AND current_attributes.column_id    = old_attributes.column_id
    WHERE (current_attributes.column_name <> old_attributes.column_name)
       OR (current_attributes.data_type   <> old_attributes.data_type)
       OR (current_attributes.data_length <> old_attributes.data_length)
),
final_data AS (
    SELECT
        CASE 
           WHEN ac.change_type = 'Adicionado'
                AND hist.change_type = 'Nova tabela'
             THEN 'Nova tabela, adicionado'
           ELSE ac.change_type
        END AS change_type,
        ac.object_name,
        ac.new_name,
        ac.date_collect,
        ac.date_processing,
        ac.data_base,
        ac.host_name,
        ac.service_name,
        ac.schema_name,
        ac.object_id,
        ac.table_name,
        ac.column_id
    FROM attribute_changes ac
    LEFT JOIN administracao.catalog_table_hist hist
           ON ac.data_base       = hist.data_base
          AND ac.host_name       = hist.host_name
          AND ac.service_name    = hist.service_name
          AND ac.schema_name     = hist.schema_name
          AND ac.object_id       = hist.object_id
          AND ac.table_name      = hist.object_name  -- em catalog_table_hist, "object_name" representa o nome da tabela
          AND ac.date_collect    = hist.date_collect
)
INSERT INTO administracao.catalog_attribute_hist
(
    change_type,
    object_name,
    new_name,
    date_collect,
    date_processing,
    data_base,
    host_name,
    service_name,
    schema_name,
    object_id,
    table_name,
    column_id
)
SELECT
    fd.change_type,
    fd.object_name,
    fd.new_name,
    fd.date_collect,
    fd.date_processing,
    fd.data_base,
    fd.host_name,
    fd.service_name,
    fd.schema_name,
    fd.object_id,
    fd.table_name,
    fd.column_id
FROM final_data fd
WHERE NOT EXISTS (
    SELECT 1
    FROM administracao.catalog_attribute_hist hist
    WHERE hist.change_type      = fd.change_type
      AND hist.object_name      = fd.object_name
      AND (
             (hist.new_name IS NULL AND fd.new_name IS NULL) 
          OR (hist.new_name = fd.new_name)
          )
      AND hist.date_collect     = fd.date_collect
      AND hist.date_processing  = fd.date_processing
      AND hist.data_base        = fd.data_base
      AND hist.host_name        = fd.host_name
      AND hist.service_name     = fd.service_name
      AND hist.schema_name      = fd.schema_name
      AND hist.object_id        = fd.object_id
      AND hist.table_name       = fd.table_name
      AND hist.column_id        = fd.column_id
)
