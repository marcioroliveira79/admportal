-- View: administracao.catalog_vw_lgpd_marcacao

-- DROP VIEW administracao.catalog_vw_lgpd_marcacao;

CREATE OR REPLACE VIEW administracao.catalog_vw_lgpd_marcacao
 AS
 SELECT tb.id,
    tb.data_base,
    tb.host_name,
    tb.service_name,
    tb.schema_name,
    tb.table_name,
    tb.table_comments,
    tb.column_name,
    tb.data_type,
    tb.data_length,
    tb.column_id,
    tb.column_comments,
    tb.date_collect,
    tb.ambiente,
    tb.is_nullable,
    tb.is_unique,
    tb.is_pk,
    tb.is_fk,
    tb.record_count,
    tb.table_creation_date,
    tb.table_last_ddl_time,
    tb.null_count,
    tb.contem_palavra,
    tb.palavra_relacionada,
    tb.classificacao_palavra,
    tb.contem_atributo,
    tb.atributo_relacionado,
    tb.classificacao_atributo,
    tb.lgpd_marcacao,
    tb.acao_lgpd,
    tb.lgpd_informacao,
    tb.existe_registro,
    tb.fk_lgpd_marcacao,
    tb.data_criacao_marcacao,
    tb.usuario_criador,
    tb.acao_lgpd_atual,
    tb.lgpd_informacao_atual,
    tb.nome_usuario_criador,
    tb.lgpd_definicao
   FROM ( SELECT c.id,
            c.data_base,
            c.host_name,
            c.service_name,
            c.schema_name,
            c.table_name,
            c.table_comments,
            c.column_name,
            c.data_type,
            c.data_length,
            c.column_id,
            c.column_comments,
            c.date_collect,
            c.ambiente,
            c.is_nullable,
            c.is_unique,
            c.is_pk,
            c.is_fk,
            c.record_count,
            c.table_creation_date,
            c.table_last_ddl_time,
            c.null_count,
                CASE
                    WHEN d.match_palavra IS NOT NULL THEN true
                    ELSE false
                END AS contem_palavra,
            d.match_palavra AS palavra_relacionada,
            cla_d.classificacao AS classificacao_palavra,
                CASE
                    WHEN a.match_atributo IS NOT NULL THEN true
                    ELSE false
                END AS contem_atributo,
            a.match_atributo AS atributo_relacionado,
            cla_a.classificacao AS classificacao_atributo,
                CASE
                    WHEN ex.cnt_excludente > 0 THEN false
                    ELSE c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text
                END AS lgpd_marcacao,
                CASE
                    WHEN ex.cnt_excludente > 0 THEN NULL::text
                    WHEN m.id IS NOT NULL THEN m.acao_lgpd::text
                    WHEN c.is_pk::text = 'Y'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN 'NÃO ANONIMIZAR'::text
                    WHEN c.is_fk::text = 'Y'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN 'NÃO ANONIMIZAR'::text
                    WHEN c.is_pk::text = 'N'::text AND c.is_fk::text = 'N'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN 'ANONIMIZAR'::text
                    ELSE "substring"(c.column_comments::text, '#LGPD#([^#]+)#'::text)
                END AS acao_lgpd,
                CASE
                    WHEN ex.cnt_excludente > 0 THEN NULL::text
                    WHEN m.id IS NOT NULL THEN m.lgpd_informacao::text
                    WHEN c.is_pk::text = 'Y'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN 'CHAVE PRIMARIA'::text
                    WHEN c.is_fk::text = 'Y'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN 'CHAVE ESTRANGEIRA'::text
                    WHEN c.is_pk::text = 'N'::text AND c.is_fk::text = 'N'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND cla_d.classificacao IS NOT NULL AND cla_a.classificacao IS NOT NULL AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN cla_a.classificacao::text
                    WHEN c.is_pk::text = 'N'::text AND c.is_fk::text = 'N'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND cla_d.classificacao IS NOT NULL AND cla_a.classificacao IS NULL AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN cla_d.classificacao::text
                    WHEN c.is_pk::text = 'N'::text AND c.is_fk::text = 'N'::text AND (d.match_palavra IS NOT NULL OR a.match_atributo IS NOT NULL) AND cla_d.classificacao IS NULL AND cla_a.classificacao IS NOT NULL AND ((c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text) = false OR c.column_comments IS NULL) THEN cla_a.classificacao::text
                    ELSE "substring"(c.column_comments::text, '#[^#]+#[^#]+#([^#]+)#'::text)
                END AS lgpd_informacao,
                CASE
                    WHEN m.id IS NOT NULL THEN true
                    ELSE false
                END AS existe_registro,
            m.id AS fk_lgpd_marcacao,
            m.data_criacao AS data_criacao_marcacao,
            m.fk_usuario_criador AS usuario_criador,
                CASE
                    WHEN c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text THEN "substring"(c.column_comments::text, '#[^#]+#([^#]+)#'::text)
                    ELSE NULL::text
                END AS acao_lgpd_atual,
                CASE
                    WHEN c.column_comments::text ~ '#[^#]+#[^#]+#[^#]+#'::text THEN "substring"(c.column_comments::text, '#[^#]+#[^#]+#([^#]+)#'::text)
                    ELSE NULL::text
                END AS lgpd_informacao_atual,
            (us.nome::text || ' '::text) || us.sobre_nome::text AS nome_usuario_criador,
                CASE
                    WHEN m.id IS NOT NULL THEN m.lgpd_definicao
                    ELSE
                    CASE
                        WHEN c.column_comments::text ~ '^#LGPD#[^#]+#[^#]+#[^#]+#$'::text THEN
                        CASE
                            WHEN "substring"(c.column_comments::text, '^#LGPD#[^#]+#[^#]+#([^#]+)#$'::text) = ANY (ARRAY['PESSOAL'::text, 'SENSÍVEL'::text, 'COMUM'::text]) THEN "substring"(c.column_comments::text, '^#LGPD#[^#]+#[^#]+#([^#]+)#$'::text)
                            ELSE NULL::text
                        END::character varying
                        WHEN c.column_comments::text ~ '^#LGPD#[^#]+#[^#]+#$'::text THEN m.lgpd_definicao
                        ELSE m.lgpd_definicao
                    END
                END AS lgpd_definicao
           FROM administracao.catalog_table_content c
             LEFT JOIN LATERAL ( SELECT string_agg(d_1.atributo::text, ', '::text) AS match_palavra,
                    max(d_1.fk_lgpd_classificacao) AS fk_lgpd_classificacao
                   FROM administracao.catalog_atributo_classificado_lgpd d_1
                  WHERE c.column_name::text ~* (('(^|_)'::text || d_1.atributo::text) || '(_|$)'::text) AND d_1.tipo_definicao::text = 'DICIONARIO'::text AND d_1.tipo_atributo::text = 'INCLUSIVO'::text) d ON true
             LEFT JOIN administracao.catalog_lgpd_classificacao cla_d ON cla_d.id = d.fk_lgpd_classificacao
             LEFT JOIN LATERAL ( SELECT string_agg(a_1.atributo::text, ', '::text) AS match_atributo,
                    max(a_1.fk_lgpd_classificacao) AS fk_lgpd_classificacao
                   FROM administracao.catalog_atributo_classificado_lgpd a_1
                  WHERE c.column_name::text = a_1.atributo::text AND a_1.tipo_atributo::text = 'INCLUSIVO'::text) a ON true
             LEFT JOIN administracao.catalog_lgpd_classificacao cla_a ON cla_a.id = a.fk_lgpd_classificacao
             LEFT JOIN administracao.catalog_lgpd_marcacao m ON m.data_base::text = c.data_base::text AND m.host_name::text = c.host_name::text AND m.service_name::text = c.service_name::text AND m.schema_name::text = c.schema_name::text AND m.table_name::text = c.table_name::text AND m.column_name::text = c.column_name::text AND m.ambiente::text = c.ambiente::text
             LEFT JOIN administracao.adm_usuario us ON us.id = m.fk_usuario_criador
             LEFT JOIN LATERAL ( SELECT count(*) AS cnt_excludente
                   FROM administracao.catalog_atributo_classificado_lgpd ex_1
                  WHERE c.column_name::text = ex_1.atributo::text AND ex_1.tipo_atributo::text = 'EXCLUDENTE'::text) ex ON true
          WHERE 1 = 1) tb;

ALTER TABLE administracao.catalog_vw_lgpd_marcacao
    OWNER TO postgres;

