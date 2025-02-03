--
-- PostgreSQL database dump
--

-- Dumped from database version 12.0
-- Dumped by pg_dump version 12.0

-- Started on 2020-07-15 16:39:26 -03

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 6034 (class 1262 OID 16393)
-- Name: sgc; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE sgc WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'pt_BR.UTF-8' LC_CTYPE = 'pt_BR.UTF-8';


ALTER DATABASE sgc OWNER TO postgres;

\connect sgc

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 7 (class 2615 OID 16395)
-- Name: administracao; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA administracao;


ALTER SCHEMA administracao OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 222 (class 1259 OID 16581)
-- Name: item_menu; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.item_menu (
    id integer NOT NULL,
    descricao character varying(50) NOT NULL,
    ajuda character varying(150) NOT NULL,
    link character varying(250) NOT NULL
);


ALTER TABLE administracao.item_menu OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16579)
-- Name: item_menu_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.item_menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.item_menu_id_seq OWNER TO postgres;

--
-- TOC entry 6035 (class 0 OID 0)
-- Dependencies: 221
-- Name: item_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.item_menu_id_seq OWNED BY administracao.item_menu.id;


--
-- TOC entry 220 (class 1259 OID 16568)
-- Name: log_acesso; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.log_acesso (
    id integer NOT NULL,
    fk_usuario integer NOT NULL,
    data_acesso timestamp without time zone NOT NULL,
    data_saida timestamp without time zone,
    ip_acesso character(20),
    session_id character varying(255)
);


ALTER TABLE administracao.log_acesso OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16566)
-- Name: log_acesso_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.log_acesso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.log_acesso_id_seq OWNER TO postgres;

--
-- TOC entry 6036 (class 0 OID 0)
-- Dependencies: 219
-- Name: log_acesso_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.log_acesso_id_seq OWNED BY administracao.log_acesso.id;


--
-- TOC entry 212 (class 1259 OID 16496)
-- Name: menu; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.menu (
    id integer NOT NULL,
    descricao character varying(50) NOT NULL,
    ajuda character varying(150) NOT NULL
);


ALTER TABLE administracao.menu OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 16494)
-- Name: menu_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.menu_id_seq OWNER TO postgres;

--
-- TOC entry 6037 (class 0 OID 0)
-- Dependencies: 211
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.menu_id_seq OWNED BY administracao.menu.id;


--
-- TOC entry 210 (class 1259 OID 16486)
-- Name: perfil; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.perfil (
    id integer NOT NULL,
    descricao character varying(250) NOT NULL,
    nome character varying(50) NOT NULL
);


ALTER TABLE administracao.perfil OWNER TO postgres;

--
-- TOC entry 209 (class 1259 OID 16484)
-- Name: perfil_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.perfil_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.perfil_id_seq OWNER TO postgres;

--
-- TOC entry 6038 (class 0 OID 0)
-- Dependencies: 209
-- Name: perfil_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.perfil_id_seq OWNED BY administracao.perfil.id;


--
-- TOC entry 216 (class 1259 OID 16538)
-- Name: perfil_menu; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.perfil_menu (
    id integer NOT NULL,
    fk_perfil integer NOT NULL,
    fk_menu integer NOT NULL,
    ordem integer
);


ALTER TABLE administracao.perfil_menu OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 16536)
-- Name: perfil_menu_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.perfil_menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.perfil_menu_id_seq OWNER TO postgres;

--
-- TOC entry 6039 (class 0 OID 0)
-- Dependencies: 215
-- Name: perfil_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.perfil_menu_id_seq OWNED BY administracao.perfil_menu.id;


--
-- TOC entry 208 (class 1259 OID 16469)
-- Name: usuario; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.usuario (
    id integer NOT NULL,
    nome character varying(50) NOT NULL,
    sobre_nome character varying(150) NOT NULL,
    email character varying(250) NOT NULL,
    telefone character varying(15) NOT NULL,
    login character varying(250) NOT NULL,
    senha character varying(20) NOT NULL
);


ALTER TABLE administracao.usuario OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 16506)
-- Name: usuario_perfil; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.usuario_perfil (
    id integer NOT NULL,
    fk_usuario integer NOT NULL,
    fk_perfil integer NOT NULL
);


ALTER TABLE administracao.usuario_perfil OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16561)
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
   FROM ((((administracao.usuario us
     JOIN administracao.usuario_perfil upf ON ((us.id = upf.fk_usuario)))
     JOIN administracao.perfil pf ON ((pf.id = upf.fk_perfil)))
     JOIN administracao.perfil_menu pm ON ((pm.fk_perfil = pf.id)))
     JOIN administracao.menu men ON ((men.id = pm.fk_menu)));


ALTER TABLE administracao.perfil_vw OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 16416)
-- Name: pseudo_tabela; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.pseudo_tabela (
    id integer NOT NULL,
    descricao character varying(250) NOT NULL,
    nome_tabela character varying(50) NOT NULL
);


ALTER TABLE administracao.pseudo_tabela OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 16426)
-- Name: pseudo_tabela_atributos; Type: TABLE; Schema: administracao; Owner: postgres
--

CREATE TABLE administracao.pseudo_tabela_atributos (
    id integer NOT NULL,
    fk_atributo integer NOT NULL,
    descricao character varying(250) NOT NULL,
    nome_item character varying(50) NOT NULL,
    valor_item character varying(250) NOT NULL
);


ALTER TABLE administracao.pseudo_tabela_atributos OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 16424)
-- Name: pseudo_tabela_atributos_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.pseudo_tabela_atributos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.pseudo_tabela_atributos_id_seq OWNER TO postgres;

--
-- TOC entry 6040 (class 0 OID 0)
-- Dependencies: 205
-- Name: pseudo_tabela_atributos_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.pseudo_tabela_atributos_id_seq OWNED BY administracao.pseudo_tabela_atributos.id;


--
-- TOC entry 203 (class 1259 OID 16414)
-- Name: pseudo_tabela_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.pseudo_tabela_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.pseudo_tabela_id_seq OWNER TO postgres;

--
-- TOC entry 6041 (class 0 OID 0)
-- Dependencies: 203
-- Name: pseudo_tabela_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.pseudo_tabela_id_seq OWNED BY administracao.pseudo_tabela.id;


--
-- TOC entry 207 (class 1259 OID 16467)
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.usuario_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.usuario_id_seq OWNER TO postgres;

--
-- TOC entry 6042 (class 0 OID 0)
-- Dependencies: 207
-- Name: usuario_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.usuario_id_seq OWNED BY administracao.usuario.id;


--
-- TOC entry 213 (class 1259 OID 16504)
-- Name: usuario_perfil_id_seq; Type: SEQUENCE; Schema: administracao; Owner: postgres
--

CREATE SEQUENCE administracao.usuario_perfil_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE administracao.usuario_perfil_id_seq OWNER TO postgres;

--
-- TOC entry 6043 (class 0 OID 0)
-- Dependencies: 213
-- Name: usuario_perfil_id_seq; Type: SEQUENCE OWNED BY; Schema: administracao; Owner: postgres
--

ALTER SEQUENCE administracao.usuario_perfil_id_seq OWNED BY administracao.usuario_perfil.id;


--
-- TOC entry 5840 (class 2604 OID 16584)
-- Name: item_menu id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.item_menu ALTER COLUMN id SET DEFAULT nextval('administracao.item_menu_id_seq'::regclass);


--
-- TOC entry 5839 (class 2604 OID 16571)
-- Name: log_acesso id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.log_acesso ALTER COLUMN id SET DEFAULT nextval('administracao.log_acesso_id_seq'::regclass);


--
-- TOC entry 5836 (class 2604 OID 16499)
-- Name: menu id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.menu ALTER COLUMN id SET DEFAULT nextval('administracao.menu_id_seq'::regclass);


--
-- TOC entry 5835 (class 2604 OID 16489)
-- Name: perfil id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil ALTER COLUMN id SET DEFAULT nextval('administracao.perfil_id_seq'::regclass);


--
-- TOC entry 5838 (class 2604 OID 16541)
-- Name: perfil_menu id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil_menu ALTER COLUMN id SET DEFAULT nextval('administracao.perfil_menu_id_seq'::regclass);


--
-- TOC entry 5832 (class 2604 OID 16419)
-- Name: pseudo_tabela id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.pseudo_tabela ALTER COLUMN id SET DEFAULT nextval('administracao.pseudo_tabela_id_seq'::regclass);


--
-- TOC entry 5833 (class 2604 OID 16429)
-- Name: pseudo_tabela_atributos id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.pseudo_tabela_atributos ALTER COLUMN id SET DEFAULT nextval('administracao.pseudo_tabela_atributos_id_seq'::regclass);


--
-- TOC entry 5834 (class 2604 OID 16472)
-- Name: usuario id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario ALTER COLUMN id SET DEFAULT nextval('administracao.usuario_id_seq'::regclass);


--
-- TOC entry 5837 (class 2604 OID 16509)
-- Name: usuario_perfil id; Type: DEFAULT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario_perfil ALTER COLUMN id SET DEFAULT nextval('administracao.usuario_perfil_id_seq'::regclass);


--
-- TOC entry 6028 (class 0 OID 16581)
-- Dependencies: 222
-- Data for Name: item_menu; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.item_menu (id, descricao, ajuda, link) VALUES (1, 'Conf. Sistema', 'Atributos de configuração do sistema', '?action=configurar_sistema');


--
-- TOC entry 6026 (class 0 OID 16568)
-- Dependencies: 220
-- Data for Name: log_acesso; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.log_acesso (id, fk_usuario, data_acesso, data_saida, ip_acesso, session_id) VALUES (16, 1, '2019-10-24 11:44:00.647756', '2019-10-24 11:44:19.752874', NULL, 'sdtdfndmpijukr3vvb78p4h6g0');
INSERT INTO administracao.log_acesso (id, fk_usuario, data_acesso, data_saida, ip_acesso, session_id) VALUES (17, 1, '2019-10-24 11:44:38.863538', '2019-10-24 11:47:34.925283', NULL, 'sdtdfndmpijukr3vvb78p4h6g0');
INSERT INTO administracao.log_acesso (id, fk_usuario, data_acesso, data_saida, ip_acesso, session_id) VALUES (18, 1, '2019-10-24 11:50:47.923185', '2019-10-24 11:51:19.132917', NULL, 'kol3de1drcat1vda9i7lad3vu7');
INSERT INTO administracao.log_acesso (id, fk_usuario, data_acesso, data_saida, ip_acesso, session_id) VALUES (19, 1, '2019-10-24 11:54:20.907897', '2019-10-24 13:38:49.268036', NULL, 'sdtdfndmpijukr3vvb78p4h6g0');


--
-- TOC entry 6020 (class 0 OID 16496)
-- Dependencies: 212
-- Data for Name: menu; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.menu (id, descricao, ajuda) VALUES (1, 'Conf. Menu', 'Menu para configurações de perfis');


--
-- TOC entry 6018 (class 0 OID 16486)
-- Dependencies: 210
-- Data for Name: perfil; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.perfil (id, descricao, nome) VALUES (1, 'Perfil de administracao do sistemas', 'administracao_sistema');
INSERT INTO administracao.perfil (id, descricao, nome) VALUES (2, 'Perfil de usuario', 'usuario');


--
-- TOC entry 6024 (class 0 OID 16538)
-- Dependencies: 216
-- Data for Name: perfil_menu; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.perfil_menu (id, fk_perfil, fk_menu, ordem) VALUES (1, 1, 1, 1);


--
-- TOC entry 6012 (class 0 OID 16416)
-- Dependencies: 204
-- Data for Name: pseudo_tabela; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.pseudo_tabela (id, descricao, nome_tabela) VALUES (1, 'Tabela de parametros do sistema', 'parametros_sistema');


--
-- TOC entry 6014 (class 0 OID 16426)
-- Dependencies: 206
-- Data for Name: pseudo_tabela_atributos; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.pseudo_tabela_atributos (id, fk_atributo, descricao, nome_item, valor_item) VALUES (1, 1, 'Tempo de sessão de login', 'time_session', '14400');
INSERT INTO administracao.pseudo_tabela_atributos (id, fk_atributo, descricao, nome_item, valor_item) VALUES (2, 1, 'Nome entidade', 'nome_entidade', 'SGC - Sistemas Gerêncial de Chamados');
INSERT INTO administracao.pseudo_tabela_atributos (id, fk_atributo, descricao, nome_item, valor_item) VALUES (3, 1, 'Url entidade', 'url_entidade', '/index.php');


--
-- TOC entry 6016 (class 0 OID 16469)
-- Dependencies: 208
-- Data for Name: usuario; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.usuario (id, nome, sobre_nome, email, telefone, login, senha) VALUES (1, 'marcio', 'oliveira', 'marcio.r.oliveira79@gmail.com', '61981306361', 'marcio', '12345678');


--
-- TOC entry 6022 (class 0 OID 16506)
-- Dependencies: 214
-- Data for Name: usuario_perfil; Type: TABLE DATA; Schema: administracao; Owner: postgres
--

INSERT INTO administracao.usuario_perfil (id, fk_usuario, fk_perfil) VALUES (1, 1, 1);


--
-- TOC entry 6044 (class 0 OID 0)
-- Dependencies: 221
-- Name: item_menu_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.item_menu_id_seq', 1, true);


--
-- TOC entry 6045 (class 0 OID 0)
-- Dependencies: 219
-- Name: log_acesso_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.log_acesso_id_seq', 19, true);


--
-- TOC entry 6046 (class 0 OID 0)
-- Dependencies: 211
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.menu_id_seq', 1, true);


--
-- TOC entry 6047 (class 0 OID 0)
-- Dependencies: 209
-- Name: perfil_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.perfil_id_seq', 2, true);


--
-- TOC entry 6048 (class 0 OID 0)
-- Dependencies: 215
-- Name: perfil_menu_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.perfil_menu_id_seq', 1, true);


--
-- TOC entry 6049 (class 0 OID 0)
-- Dependencies: 205
-- Name: pseudo_tabela_atributos_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.pseudo_tabela_atributos_id_seq', 3, true);


--
-- TOC entry 6050 (class 0 OID 0)
-- Dependencies: 203
-- Name: pseudo_tabela_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.pseudo_tabela_id_seq', 1, true);


--
-- TOC entry 6051 (class 0 OID 0)
-- Dependencies: 207
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.usuario_id_seq', 1, true);


--
-- TOC entry 6052 (class 0 OID 0)
-- Dependencies: 213
-- Name: usuario_perfil_id_seq; Type: SEQUENCE SET; Schema: administracao; Owner: postgres
--

SELECT pg_catalog.setval('administracao.usuario_perfil_id_seq', 1, true);


--
-- TOC entry 5874 (class 2606 OID 16588)
-- Name: item_menu item_menu_descricao; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.item_menu
    ADD CONSTRAINT item_menu_descricao UNIQUE (descricao);


--
-- TOC entry 5876 (class 2606 OID 16586)
-- Name: item_menu item_menu_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.item_menu
    ADD CONSTRAINT item_menu_pk PRIMARY KEY (id);


--
-- TOC entry 5872 (class 2606 OID 16573)
-- Name: log_acesso log_acesso_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.log_acesso
    ADD CONSTRAINT log_acesso_pk PRIMARY KEY (id);


--
-- TOC entry 5860 (class 2606 OID 16503)
-- Name: menu menu_descricao; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.menu
    ADD CONSTRAINT menu_descricao UNIQUE (descricao);


--
-- TOC entry 5862 (class 2606 OID 16501)
-- Name: menu menu_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.menu
    ADD CONSTRAINT menu_pk PRIMARY KEY (id);


--
-- TOC entry 5868 (class 2606 OID 16545)
-- Name: perfil_menu perfil_menu_idx; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil_menu
    ADD CONSTRAINT perfil_menu_idx UNIQUE (fk_perfil, fk_menu, ordem);


--
-- TOC entry 5870 (class 2606 OID 16543)
-- Name: perfil_menu perfil_menu_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil_menu
    ADD CONSTRAINT perfil_menu_pk PRIMARY KEY (id);


--
-- TOC entry 5856 (class 2606 OID 16493)
-- Name: perfil perfil_nome; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil
    ADD CONSTRAINT perfil_nome UNIQUE (nome);


--
-- TOC entry 5858 (class 2606 OID 16491)
-- Name: perfil perfil_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil
    ADD CONSTRAINT perfil_pk PRIMARY KEY (id);


--
-- TOC entry 5846 (class 2606 OID 16434)
-- Name: pseudo_tabela_atributos pseudo_tabela_atributos_pkey; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.pseudo_tabela_atributos
    ADD CONSTRAINT pseudo_tabela_atributos_pkey PRIMARY KEY (id);


--
-- TOC entry 5842 (class 2606 OID 16423)
-- Name: pseudo_tabela pseudo_tabela_nome_tabela_key; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.pseudo_tabela
    ADD CONSTRAINT pseudo_tabela_nome_tabela_key UNIQUE (nome_tabela);


--
-- TOC entry 5844 (class 2606 OID 16421)
-- Name: pseudo_tabela pseudo_tabela_pkey; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.pseudo_tabela
    ADD CONSTRAINT pseudo_tabela_pkey PRIMARY KEY (id);


--
-- TOC entry 5848 (class 2606 OID 16481)
-- Name: usuario usuario_email; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario
    ADD CONSTRAINT usuario_email UNIQUE (email);


--
-- TOC entry 5850 (class 2606 OID 16479)
-- Name: usuario usuario_login; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario
    ADD CONSTRAINT usuario_login UNIQUE (login);


--
-- TOC entry 5864 (class 2606 OID 16513)
-- Name: usuario_perfil usuario_perfil_nome; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario_perfil
    ADD CONSTRAINT usuario_perfil_nome UNIQUE (fk_usuario, fk_perfil);


--
-- TOC entry 5866 (class 2606 OID 16511)
-- Name: usuario_perfil usuario_perfil_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario_perfil
    ADD CONSTRAINT usuario_perfil_pk PRIMARY KEY (id);


--
-- TOC entry 5852 (class 2606 OID 16477)
-- Name: usuario usuario_pk; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario
    ADD CONSTRAINT usuario_pk PRIMARY KEY (id);


--
-- TOC entry 5854 (class 2606 OID 16483)
-- Name: usuario usuario_telefone; Type: CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario
    ADD CONSTRAINT usuario_telefone UNIQUE (telefone);


--
-- TOC entry 5882 (class 2606 OID 16574)
-- Name: log_acesso ref_acesso_usuario; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.log_acesso
    ADD CONSTRAINT ref_acesso_usuario FOREIGN KEY (fk_usuario) REFERENCES administracao.usuario(id);


--
-- TOC entry 5881 (class 2606 OID 16551)
-- Name: perfil_menu ref_perfil_menu_menu; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil_menu
    ADD CONSTRAINT ref_perfil_menu_menu FOREIGN KEY (fk_menu) REFERENCES administracao.menu(id);


--
-- TOC entry 5880 (class 2606 OID 16546)
-- Name: perfil_menu ref_perfil_menu_perfil; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.perfil_menu
    ADD CONSTRAINT ref_perfil_menu_perfil FOREIGN KEY (fk_perfil) REFERENCES administracao.perfil(id);


--
-- TOC entry 5877 (class 2606 OID 16435)
-- Name: pseudo_tabela_atributos ref_psdo_atributos; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.pseudo_tabela_atributos
    ADD CONSTRAINT ref_psdo_atributos FOREIGN KEY (fk_atributo) REFERENCES administracao.pseudo_tabela(id);


--
-- TOC entry 5879 (class 2606 OID 16519)
-- Name: usuario_perfil ref_usuario_perfil_perfil; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario_perfil
    ADD CONSTRAINT ref_usuario_perfil_perfil FOREIGN KEY (fk_perfil) REFERENCES administracao.perfil(id);


--
-- TOC entry 5878 (class 2606 OID 16514)
-- Name: usuario_perfil ref_usuario_perfil_usuario; Type: FK CONSTRAINT; Schema: administracao; Owner: postgres
--

ALTER TABLE ONLY administracao.usuario_perfil
    ADD CONSTRAINT ref_usuario_perfil_usuario FOREIGN KEY (fk_usuario) REFERENCES administracao.usuario(id);


-- Completed on 2020-07-15 16:39:28 -03

--
-- PostgreSQL database dump complete
--

