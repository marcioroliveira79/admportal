--
-- PostgreSQL database dump
--

-- Dumped from database version 14.5
-- Dumped by pg_dump version 17.1

-- Started on 2025-01-29 15:25:30

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 6 (class 2615 OID 24579)
-- Name: administracao; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA administracao;


ALTER SCHEMA administracao OWNER TO postgres;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: OliveiM3
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO "OliveiM3";

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 210 (class 1259 OID 24580)
-- Name: adm_item_menu; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_item_menu (
    id integer NOT NULL,
    descricao character varying(50) NOT NULL,
    ajuda character varying(150) NOT NULL,
    link character varying(250) NOT NULL
);


ALTER TABLE administracao.adm_item_menu OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 24584)
-- Name: adm_log_acesso; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_log_acesso (
    id integer NOT NULL,
    fk_usuario integer NOT NULL,
    data_acesso timestamp without time zone NOT NULL,
    data_saida timestamp without time zone,
    ip_acesso character(20),
    session_id character varying(255)
);


ALTER TABLE administracao.adm_log_acesso OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 24588)
-- Name: adm_menu; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_menu (
    id integer NOT NULL,
    descricao character varying(50) NOT NULL,
    ajuda character varying(150) NOT NULL
);


ALTER TABLE administracao.adm_menu OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 24592)
-- Name: adm_perfil; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_perfil (
    id integer NOT NULL,
    descricao character varying(250) NOT NULL,
    nome character varying(50) NOT NULL
);


ALTER TABLE administracao.adm_perfil OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 24596)
-- Name: adm_perfil_menu; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_perfil_menu (
    id integer NOT NULL,
    fk_perfil integer NOT NULL,
    fk_menu integer NOT NULL,
    ordem integer
);


ALTER TABLE administracao.adm_perfil_menu OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 24613)
-- Name: adm_pseudo_tabela; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_pseudo_tabela (
    id integer NOT NULL,
    descricao character varying(250) NOT NULL,
    nome_tabela character varying(50) NOT NULL
);


ALTER TABLE administracao.adm_pseudo_tabela OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 24616)
-- Name: adm_pseudo_tabela_atributos; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_pseudo_tabela_atributos (
    id integer NOT NULL,
    fk_atributo integer NOT NULL,
    descricao character varying(250) NOT NULL,
    nome_item character varying(50) NOT NULL,
    valor_item character varying(250) NOT NULL
);


ALTER TABLE administracao.adm_pseudo_tabela_atributos OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 24600)
-- Name: adm_usuario; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_usuario (
    id integer NOT NULL,
    nome character varying(50) NOT NULL,
    sobre_nome character varying(150) NOT NULL,
    email character varying(250) NOT NULL,
    telefone character varying(15) NOT NULL,
    login character varying(250) NOT NULL,
    senha character varying(20) NOT NULL
);


ALTER TABLE administracao.adm_usuario OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 24605)
-- Name: adm_usuario_perfil; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.adm_usuario_perfil (
    id integer NOT NULL,
    fk_usuario integer NOT NULL,
    fk_perfil integer NOT NULL
);


ALTER TABLE administracao.adm_usuario_perfil OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 24583)
-- Name: item_menu_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.item_menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.item_menu_id_seq OWNER TO postgres;

--
-- TOC entry 3424 (class 0 OID 0)
-- Dependencies: 211
-- Name: item_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.item_menu_id_seq OWNED BY administracao.adm_item_menu.id;


--
-- TOC entry 213 (class 1259 OID 24587)
-- Name: log_acesso_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.log_acesso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.log_acesso_id_seq OWNER TO postgres;

--
-- TOC entry 3425 (class 0 OID 0)
-- Dependencies: 213
-- Name: log_acesso_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.log_acesso_id_seq OWNED BY administracao.adm_log_acesso.id;


--
-- TOC entry 215 (class 1259 OID 24591)
-- Name: menu_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.menu_id_seq OWNER TO postgres;

--
-- TOC entry 3426 (class 0 OID 0)
-- Dependencies: 215
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.menu_id_seq OWNED BY administracao.adm_menu.id;


--
-- TOC entry 217 (class 1259 OID 24595)
-- Name: perfil_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.perfil_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.perfil_id_seq OWNER TO postgres;

--
-- TOC entry 3427 (class 0 OID 0)
-- Dependencies: 217
-- Name: perfil_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.perfil_id_seq OWNED BY administracao.adm_perfil.id;


--
-- TOC entry 219 (class 1259 OID 24599)
-- Name: perfil_menu_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.perfil_menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.perfil_menu_id_seq OWNER TO postgres;

--
-- TOC entry 3428 (class 0 OID 0)
-- Dependencies: 219
-- Name: perfil_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.perfil_menu_id_seq OWNED BY administracao.adm_perfil_menu.id;


--
-- TOC entry 222 (class 1259 OID 24608)
-- Name: perfil_vw; Type: VIEW; Schema: administracao; Owner: postgres
--

CREATE VIEW administracao.perfil_vw AS
 SELECT us.nome,
    us.sobre_nome,
    us.login,
    pf.nome AS perfil,
    pf.descricao AS descricao_perfil,
    men.descricao AS menu,
    pm.ordem AS ordem_menu
   FROM ((((administracao.adm_usuario us
     JOIN administracao.adm_usuario_perfil upf ON ((us.id = upf.fk_usuario)))
     JOIN administracao.adm_perfil pf ON ((pf.id = upf.fk_perfil)))
     JOIN administracao.adm_perfil_menu pm ON ((pm.fk_perfil = pf.id)))
     JOIN administracao.adm_menu men ON ((men.id = pm.fk_menu)));


ALTER VIEW administracao.perfil_vw OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 24621)
-- Name: pseudo_tabela_atributos_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.pseudo_tabela_atributos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.pseudo_tabela_atributos_id_seq OWNER TO postgres;

--
-- TOC entry 3429 (class 0 OID 0)
-- Dependencies: 225
-- Name: pseudo_tabela_atributos_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.pseudo_tabela_atributos_id_seq OWNED BY administracao.adm_pseudo_tabela_atributos.id;


--
-- TOC entry 226 (class 1259 OID 24622)
-- Name: pseudo_tabela_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.pseudo_tabela_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.pseudo_tabela_id_seq OWNER TO postgres;

--
-- TOC entry 3430 (class 0 OID 0)
-- Dependencies: 226
-- Name: pseudo_tabela_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.pseudo_tabela_id_seq OWNED BY administracao.adm_pseudo_tabela.id;


--
-- TOC entry 227 (class 1259 OID 24623)
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.usuario_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.usuario_id_seq OWNER TO postgres;

--
-- TOC entry 3431 (class 0 OID 0)
-- Dependencies: 227
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.usuario_id_seq OWNED BY administracao.adm_usuario.id;


--
-- TOC entry 228 (class 1259 OID 24624)
-- Name: usuario_perfil_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.usuario_perfil_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE administracao.usuario_perfil_id_seq OWNER TO postgres;

--
-- TOC entry 3432 (class 0 OID 0)
-- Dependencies: 228
-- Name: usuario_perfil_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.usuario_perfil_id_seq OWNED BY administracao.adm_usuario_perfil.id;


--
-- TOC entry 3209 (class 2604 OID 24625)
-- Name: adm_item_menu id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_item_menu ALTER COLUMN id SET DEFAULT nextval('administracao.item_menu_id_seq'::regclass);


--
-- TOC entry 3210 (class 2604 OID 24626)
-- Name: adm_log_acesso id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_log_acesso ALTER COLUMN id SET DEFAULT nextval('administracao.log_acesso_id_seq'::regclass);


--
-- TOC entry 3211 (class 2604 OID 24627)
-- Name: adm_menu id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_menu ALTER COLUMN id SET DEFAULT nextval('administracao.menu_id_seq'::regclass);


--
-- TOC entry 3212 (class 2604 OID 24628)
-- Name: adm_perfil id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil ALTER COLUMN id SET DEFAULT nextval('administracao.perfil_id_seq'::regclass);


--
-- TOC entry 3213 (class 2604 OID 24629)
-- Name: adm_perfil_menu id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil_menu ALTER COLUMN id SET DEFAULT nextval('administracao.perfil_menu_id_seq'::regclass);


--
-- TOC entry 3216 (class 2604 OID 24630)
-- Name: adm_pseudo_tabela id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_pseudo_tabela ALTER COLUMN id SET DEFAULT nextval('administracao.pseudo_tabela_id_seq'::regclass);


--
-- TOC entry 3217 (class 2604 OID 24631)
-- Name: adm_pseudo_tabela_atributos id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_pseudo_tabela_atributos ALTER COLUMN id SET DEFAULT nextval('administracao.pseudo_tabela_atributos_id_seq'::regclass);


--
-- TOC entry 3214 (class 2604 OID 24632)
-- Name: adm_usuario id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario ALTER COLUMN id SET DEFAULT nextval('administracao.usuario_id_seq'::regclass);


--
-- TOC entry 3215 (class 2604 OID 24633)
-- Name: adm_usuario_perfil id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario_perfil ALTER COLUMN id SET DEFAULT nextval('administracao.usuario_perfil_id_seq'::regclass);


--
-- TOC entry 3400 (class 0 OID 24580)
-- Dependencies: 210
-- Data for Name: adm_item_menu; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_item_menu (id, descricao, ajuda, link) FROM stdin;
1	Conf. Sistema	Atributos de configuração do sistema	?action=configurar_sistema
\.


--
-- TOC entry 3402 (class 0 OID 24584)
-- Dependencies: 212
-- Data for Name: adm_log_acesso; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_log_acesso (id, fk_usuario, data_acesso, data_saida, ip_acesso, session_id) FROM stdin;
16	1	2019-10-24 11:44:00.647756	2019-10-24 11:44:19.752874	\N	sdtdfndmpijukr3vvb78p4h6g0
17	1	2019-10-24 11:44:38.863538	2019-10-24 11:47:34.925283	\N	sdtdfndmpijukr3vvb78p4h6g0
18	1	2019-10-24 11:50:47.923185	2019-10-24 11:51:19.132917	\N	kol3de1drcat1vda9i7lad3vu7
19	1	2019-10-24 11:54:20.907897	2019-10-24 13:38:49.268036	\N	sdtdfndmpijukr3vvb78p4h6g0
20	1	2025-01-28 17:59:54.707683	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
21	1	2025-01-28 18:00:07.580961	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
22	1	2025-01-28 18:01:10.887176	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
23	1	2025-01-28 18:01:39.909263	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
24	1	2025-01-28 18:02:34.549147	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
25	1	2025-01-28 18:03:25.744664	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
26	1	2025-01-28 18:04:54.61911	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
27	1	2025-01-28 18:05:07.672465	2025-01-28 18:07:55.253571	::1                 	sss1coefmlv2g6c4cl8tjp5acd
28	1	2025-01-28 18:09:07.078311	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
29	1	2025-01-28 18:18:38.434681	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
30	1	2025-01-28 18:19:56.138223	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
31	1	2025-01-28 18:23:17.976028	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
32	1	2025-01-29 09:16:09.361077	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
33	1	2025-01-29 09:49:25.345775	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
34	1	2025-01-29 09:50:27.63153	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
35	1	2025-01-29 09:52:37.759988	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
36	1	2025-01-29 14:14:51.415939	\N	::1                 	'sss1coefmlv2g6c4cl8tjp5acd'
\.


--
-- TOC entry 3404 (class 0 OID 24588)
-- Dependencies: 214
-- Data for Name: adm_menu; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_menu (id, descricao, ajuda) FROM stdin;
1	Conf. Menu	Menu para configurações de perfis
\.


--
-- TOC entry 3406 (class 0 OID 24592)
-- Dependencies: 216
-- Data for Name: adm_perfil; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_perfil (id, descricao, nome) FROM stdin;
1	Perfil de administracao do sistemas	administracao_sistema
2	Perfil de usuario	usuario
\.


--
-- TOC entry 3408 (class 0 OID 24596)
-- Dependencies: 218
-- Data for Name: adm_perfil_menu; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_perfil_menu (id, fk_perfil, fk_menu, ordem) FROM stdin;
1	1	1	1
\.


--
-- TOC entry 3412 (class 0 OID 24613)
-- Dependencies: 223
-- Data for Name: adm_pseudo_tabela; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_pseudo_tabela (id, descricao, nome_tabela) FROM stdin;
1	Tabela de parametros do sistema	parametros_sistema
\.


--
-- TOC entry 3413 (class 0 OID 24616)
-- Dependencies: 224
-- Data for Name: adm_pseudo_tabela_atributos; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_pseudo_tabela_atributos (id, fk_atributo, descricao, nome_item, valor_item) FROM stdin;
1	1	Tempo de sessão de login	time_session	14400
3	1	Url entidade	url_entidade	/index.php
2	1	Nome entidade	nome_entidade	Portal de administração de dados Unisys
\.


--
-- TOC entry 3410 (class 0 OID 24600)
-- Dependencies: 220
-- Data for Name: adm_usuario; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_usuario (id, nome, sobre_nome, email, telefone, login, senha) FROM stdin;
1	marcio	oliveira	marcio.r.oliveira79@gmail.com	61981306361	marcio	12345678
\.


--
-- TOC entry 3411 (class 0 OID 24605)
-- Dependencies: 221
-- Data for Name: adm_usuario_perfil; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

COPY administracao.adm_usuario_perfil (id, fk_usuario, fk_perfil) FROM stdin;
1	1	1
\.


--
-- TOC entry 3433 (class 0 OID 0)
-- Dependencies: 211
-- Name: item_menu_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.item_menu_id_seq', 1, true);


--
-- TOC entry 3434 (class 0 OID 0)
-- Dependencies: 213
-- Name: log_acesso_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.log_acesso_id_seq', 36, true);


--
-- TOC entry 3435 (class 0 OID 0)
-- Dependencies: 215
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.menu_id_seq', 1, true);


--
-- TOC entry 3436 (class 0 OID 0)
-- Dependencies: 217
-- Name: perfil_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.perfil_id_seq', 2, true);


--
-- TOC entry 3437 (class 0 OID 0)
-- Dependencies: 219
-- Name: perfil_menu_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.perfil_menu_id_seq', 1, true);


--
-- TOC entry 3438 (class 0 OID 0)
-- Dependencies: 225
-- Name: pseudo_tabela_atributos_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.pseudo_tabela_atributos_id_seq', 3, true);


--
-- TOC entry 3439 (class 0 OID 0)
-- Dependencies: 226
-- Name: pseudo_tabela_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.pseudo_tabela_id_seq', 1, true);


--
-- TOC entry 3440 (class 0 OID 0)
-- Dependencies: 227
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.usuario_id_seq', 1, true);


--
-- TOC entry 3441 (class 0 OID 0)
-- Dependencies: 228
-- Name: usuario_perfil_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.usuario_perfil_id_seq', 1, true);


--
-- TOC entry 3219 (class 2606 OID 24635)
-- Name: adm_item_menu item_menu_descricao; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_item_menu
    ADD CONSTRAINT item_menu_descricao UNIQUE (descricao);


--
-- TOC entry 3221 (class 2606 OID 24637)
-- Name: adm_item_menu item_menu_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_item_menu
    ADD CONSTRAINT item_menu_pk PRIMARY KEY (id);


--
-- TOC entry 3223 (class 2606 OID 24639)
-- Name: adm_log_acesso log_acesso_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_log_acesso
    ADD CONSTRAINT log_acesso_pk PRIMARY KEY (id);


--
-- TOC entry 3225 (class 2606 OID 24641)
-- Name: adm_menu menu_descricao; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_menu
    ADD CONSTRAINT menu_descricao UNIQUE (descricao);


--
-- TOC entry 3227 (class 2606 OID 24643)
-- Name: adm_menu menu_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_menu
    ADD CONSTRAINT menu_pk PRIMARY KEY (id);


--
-- TOC entry 3233 (class 2606 OID 24645)
-- Name: adm_perfil_menu perfil_menu_idx; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil_menu
    ADD CONSTRAINT perfil_menu_idx UNIQUE (fk_perfil, fk_menu, ordem);


--
-- TOC entry 3235 (class 2606 OID 24647)
-- Name: adm_perfil_menu perfil_menu_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil_menu
    ADD CONSTRAINT perfil_menu_pk PRIMARY KEY (id);


--
-- TOC entry 3229 (class 2606 OID 24649)
-- Name: adm_perfil perfil_nome; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil
    ADD CONSTRAINT perfil_nome UNIQUE (nome);


--
-- TOC entry 3231 (class 2606 OID 24651)
-- Name: adm_perfil perfil_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil
    ADD CONSTRAINT perfil_pk PRIMARY KEY (id);


--
-- TOC entry 3253 (class 2606 OID 24653)
-- Name: adm_pseudo_tabela_atributos pseudo_tabela_atributos_pkey; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_pseudo_tabela_atributos
    ADD CONSTRAINT pseudo_tabela_atributos_pkey PRIMARY KEY (id);


--
-- TOC entry 3249 (class 2606 OID 24655)
-- Name: adm_pseudo_tabela pseudo_tabela_nome_tabela_key; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_pseudo_tabela
    ADD CONSTRAINT pseudo_tabela_nome_tabela_key UNIQUE (nome_tabela);


--
-- TOC entry 3251 (class 2606 OID 24657)
-- Name: adm_pseudo_tabela pseudo_tabela_pkey; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_pseudo_tabela
    ADD CONSTRAINT pseudo_tabela_pkey PRIMARY KEY (id);


--
-- TOC entry 3237 (class 2606 OID 24659)
-- Name: adm_usuario usuario_email; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario
    ADD CONSTRAINT usuario_email UNIQUE (email);


--
-- TOC entry 3239 (class 2606 OID 24661)
-- Name: adm_usuario usuario_login; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario
    ADD CONSTRAINT usuario_login UNIQUE (login);


--
-- TOC entry 3245 (class 2606 OID 24663)
-- Name: adm_usuario_perfil usuario_perfil_nome; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario_perfil
    ADD CONSTRAINT usuario_perfil_nome UNIQUE (fk_usuario, fk_perfil);


--
-- TOC entry 3247 (class 2606 OID 24665)
-- Name: adm_usuario_perfil usuario_perfil_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario_perfil
    ADD CONSTRAINT usuario_perfil_pk PRIMARY KEY (id);


--
-- TOC entry 3241 (class 2606 OID 24667)
-- Name: adm_usuario usuario_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario
    ADD CONSTRAINT usuario_pk PRIMARY KEY (id);


--
-- TOC entry 3243 (class 2606 OID 24669)
-- Name: adm_usuario usuario_telefone; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario
    ADD CONSTRAINT usuario_telefone UNIQUE (telefone);


--
-- TOC entry 3254 (class 2606 OID 24670)
-- Name: adm_log_acesso ref_acesso_usuario; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_log_acesso
    ADD CONSTRAINT ref_acesso_usuario FOREIGN KEY (fk_usuario) REFERENCES administracao.adm_usuario(id);


--
-- TOC entry 3255 (class 2606 OID 24675)
-- Name: adm_perfil_menu ref_perfil_menu_menu; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil_menu
    ADD CONSTRAINT ref_perfil_menu_menu FOREIGN KEY (fk_menu) REFERENCES administracao.adm_menu(id);


--
-- TOC entry 3256 (class 2606 OID 24680)
-- Name: adm_perfil_menu ref_perfil_menu_perfil; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_perfil_menu
    ADD CONSTRAINT ref_perfil_menu_perfil FOREIGN KEY (fk_perfil) REFERENCES administracao.adm_perfil(id);


--
-- TOC entry 3259 (class 2606 OID 24685)
-- Name: adm_pseudo_tabela_atributos ref_psdo_atributos; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_pseudo_tabela_atributos
    ADD CONSTRAINT ref_psdo_atributos FOREIGN KEY (fk_atributo) REFERENCES administracao.adm_pseudo_tabela(id);


--
-- TOC entry 3257 (class 2606 OID 24690)
-- Name: adm_usuario_perfil ref_usuario_perfil_perfil; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario_perfil
    ADD CONSTRAINT ref_usuario_perfil_perfil FOREIGN KEY (fk_perfil) REFERENCES administracao.adm_perfil(id);


--
-- TOC entry 3258 (class 2606 OID 24695)
-- Name: adm_usuario_perfil ref_usuario_perfil_usuario; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.adm_usuario_perfil
    ADD CONSTRAINT ref_usuario_perfil_usuario FOREIGN KEY (fk_usuario) REFERENCES administracao.adm_usuario(id);


--
-- TOC entry 3423 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: OliveiM3
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2025-01-29 15:25:31

--
-- PostgreSQL database dump complete
--

