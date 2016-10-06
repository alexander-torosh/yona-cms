--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.8
-- Dumped by pg_dump version 9.4.8
-- Started on 2016-10-05 15:36:30

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 1 (class 3079 OID 11855)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2223 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 174 (class 1259 OID 183027)
-- Name: admin_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE admin_user (
    id integer NOT NULL,
    role character varying(255) DEFAULT 'journalist'::character varying NOT NULL,
    login character varying(50),
    email character varying(50),
    name character varying(50),
    password character varying(255),
    active smallint DEFAULT 0 NOT NULL
);


ALTER TABLE admin_user OWNER TO postgres;

--
-- TOC entry 173 (class 1259 OID 183025)
-- Name: admin_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE admin_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE admin_user_id_seq OWNER TO postgres;

--
-- TOC entry 2224 (class 0 OID 0)
-- Dependencies: 173
-- Name: admin_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE admin_user_id_seq OWNED BY admin_user.id;


--
-- TOC entry 175 (class 1259 OID 183036)
-- Name: cms_configuration; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cms_configuration (
    key character varying(50) NOT NULL,
    value character varying(255) NOT NULL
);


ALTER TABLE cms_configuration OWNER TO postgres;

--
-- TOC entry 176 (class 1259 OID 183039)
-- Name: cms_javascript; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cms_javascript (
    id character varying(20) NOT NULL,
    text text
);


ALTER TABLE cms_javascript OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 183047)
-- Name: language; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE language (
    id integer NOT NULL,
    iso character varying(10) NOT NULL,
    locale character varying(10),
    name character varying(20),
    short_name character varying(10),
    url character varying(20),
    sortorder integer,
    "primary" character varying(255) DEFAULT '0'::character varying
);


ALTER TABLE language OWNER TO postgres;

--
-- TOC entry 177 (class 1259 OID 183045)
-- Name: language_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE language_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE language_id_seq OWNER TO postgres;

--
-- TOC entry 2225 (class 0 OID 0)
-- Dependencies: 177
-- Name: language_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE language_id_seq OWNED BY language.id;


--
-- TOC entry 180 (class 1259 OID 183054)
-- Name: menu; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE menu (
    id integer NOT NULL,
    root character varying(255) DEFAULT 'top'::character varying NOT NULL,
    parent_id integer,
    work_title character varying(255),
    depth smallint DEFAULT 0 NOT NULL,
    left_key integer,
    right_key integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE menu OWNER TO postgres;

--
-- TOC entry 179 (class 1259 OID 183052)
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE menu_id_seq OWNER TO postgres;

--
-- TOC entry 2226 (class 0 OID 0)
-- Dependencies: 179
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE menu_id_seq OWNED BY menu.id;


--
-- TOC entry 182 (class 1259 OID 183065)
-- Name: menu_translate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE menu_translate (
    id integer NOT NULL,
    foreign_id integer NOT NULL,
    lang character varying(20),
    key character varying(255),
    value text
);


ALTER TABLE menu_translate OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 183063)
-- Name: menu_translate_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE menu_translate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE menu_translate_id_seq OWNER TO postgres;

--
-- TOC entry 2227 (class 0 OID 0)
-- Dependencies: 181
-- Name: menu_translate_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE menu_translate_id_seq OWNED BY menu_translate.id;


--
-- TOC entry 184 (class 1259 OID 183074)
-- Name: page; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE page (
    id integer NOT NULL,
    slug character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE page OWNER TO postgres;

--
-- TOC entry 183 (class 1259 OID 183072)
-- Name: page_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE page_id_seq OWNER TO postgres;

--
-- TOC entry 2228 (class 0 OID 0)
-- Dependencies: 183
-- Name: page_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE page_id_seq OWNED BY page.id;


--
-- TOC entry 186 (class 1259 OID 183080)
-- Name: page_translate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE page_translate (
    id integer NOT NULL,
    foreign_id integer NOT NULL,
    lang character varying(20),
    key character varying(255),
    value text
);


ALTER TABLE page_translate OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 183078)
-- Name: page_translate_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE page_translate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE page_translate_id_seq OWNER TO postgres;

--
-- TOC entry 2229 (class 0 OID 0)
-- Dependencies: 185
-- Name: page_translate_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE page_translate_id_seq OWNED BY page_translate.id;


--
-- TOC entry 190 (class 1259 OID 183099)
-- Name: publication; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE publication (
    id integer NOT NULL,
    type_id integer,
    slug character varying(255),
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    date timestamp without time zone,
    preview_inner character varying(255) DEFAULT '1'::character varying,
    preview_src character varying(255)
);


ALTER TABLE publication OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 183097)
-- Name: publication_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE publication_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE publication_id_seq OWNER TO postgres;

--
-- TOC entry 2230 (class 0 OID 0)
-- Dependencies: 189
-- Name: publication_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE publication_id_seq OWNED BY publication.id;


--
-- TOC entry 192 (class 1259 OID 183109)
-- Name: publication_translate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE publication_translate (
    id integer NOT NULL,
    foreign_id integer NOT NULL,
    lang character varying(20),
    key character varying(255),
    value text
);


ALTER TABLE publication_translate OWNER TO postgres;

--
-- TOC entry 191 (class 1259 OID 183107)
-- Name: publication_translate_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE publication_translate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE publication_translate_id_seq OWNER TO postgres;

--
-- TOC entry 2231 (class 0 OID 0)
-- Dependencies: 191
-- Name: publication_translate_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE publication_translate_id_seq OWNED BY publication_translate.id;


--
-- TOC entry 188 (class 1259 OID 183089)
-- Name: publication_type; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE publication_type (
    id integer NOT NULL,
    slug character varying(50),
    "limit" integer,
    format character varying(255),
    display_date character varying(255) DEFAULT '0'::character varying
);


ALTER TABLE publication_type OWNER TO postgres;

--
-- TOC entry 187 (class 1259 OID 183087)
-- Name: publication_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE publication_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE publication_type_id_seq OWNER TO postgres;

--
-- TOC entry 2232 (class 0 OID 0)
-- Dependencies: 187
-- Name: publication_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE publication_type_id_seq OWNED BY publication_type.id;


--
-- TOC entry 194 (class 1259 OID 183118)
-- Name: publication_type_translate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE publication_type_translate (
    id integer NOT NULL,
    foreign_id integer NOT NULL,
    lang character varying(20),
    key character varying(255),
    value text
);


ALTER TABLE publication_type_translate OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 183116)
-- Name: publication_type_translate_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE publication_type_translate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE publication_type_translate_id_seq OWNER TO postgres;

--
-- TOC entry 2233 (class 0 OID 0)
-- Dependencies: 193
-- Name: publication_type_translate_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE publication_type_translate_id_seq OWNED BY publication_type_translate.id;


--
-- TOC entry 196 (class 1259 OID 183127)
-- Name: seo_manager; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE seo_manager (
    id integer NOT NULL,
    url character varying(255),
    head_title character varying(500),
    meta_description character varying(500),
    meta_keywords character varying(500),
    seo_text text,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE seo_manager OWNER TO postgres;

--
-- TOC entry 195 (class 1259 OID 183125)
-- Name: seo_manager_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE seo_manager_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE seo_manager_id_seq OWNER TO postgres;

--
-- TOC entry 2234 (class 0 OID 0)
-- Dependencies: 195
-- Name: seo_manager_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE seo_manager_id_seq OWNED BY seo_manager.id;


--
-- TOC entry 198 (class 1259 OID 183136)
-- Name: translate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE translate (
    id integer NOT NULL,
    lang character varying(20),
    phrase character varying(500),
    translation character varying(500)
);


ALTER TABLE translate OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 183134)
-- Name: translate_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE translate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE translate_id_seq OWNER TO postgres;

--
-- TOC entry 2235 (class 0 OID 0)
-- Dependencies: 197
-- Name: translate_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE translate_id_seq OWNED BY translate.id;


--
-- TOC entry 200 (class 1259 OID 183145)
-- Name: tree_category; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tree_category (
    id integer NOT NULL,
    root character varying(255) DEFAULT 'articles'::character varying NOT NULL,
    parent_id integer,
    slug character varying(255),
    depth smallint DEFAULT 0 NOT NULL,
    left_key integer,
    right_key integer,
    created_at timestamp without time zone,
    updated_at timestamp without time zone
);


ALTER TABLE tree_category OWNER TO postgres;

--
-- TOC entry 199 (class 1259 OID 183143)
-- Name: tree_category_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tree_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tree_category_id_seq OWNER TO postgres;

--
-- TOC entry 2236 (class 0 OID 0)
-- Dependencies: 199
-- Name: tree_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tree_category_id_seq OWNED BY tree_category.id;


--
-- TOC entry 202 (class 1259 OID 183156)
-- Name: tree_category_translate; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tree_category_translate (
    id integer NOT NULL,
    foreign_id integer NOT NULL,
    lang character varying(20),
    key character varying(255),
    value text
);


ALTER TABLE tree_category_translate OWNER TO postgres;

--
-- TOC entry 201 (class 1259 OID 183154)
-- Name: tree_category_translate_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tree_category_translate_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tree_category_translate_id_seq OWNER TO postgres;

--
-- TOC entry 2237 (class 0 OID 0)
-- Dependencies: 201
-- Name: tree_category_translate_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tree_category_translate_id_seq OWNED BY tree_category_translate.id;


--
-- TOC entry 203 (class 1259 OID 183163)
-- Name: widget; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE widget (
    id character varying(255) NOT NULL,
    title character varying(255),
    html text
);


ALTER TABLE widget OWNER TO postgres;

--
-- TOC entry 1985 (class 2604 OID 183030)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY admin_user ALTER COLUMN id SET DEFAULT nextval('admin_user_id_seq'::regclass);


--
-- TOC entry 1988 (class 2604 OID 183050)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY language ALTER COLUMN id SET DEFAULT nextval('language_id_seq'::regclass);


--
-- TOC entry 1990 (class 2604 OID 183057)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY menu ALTER COLUMN id SET DEFAULT nextval('menu_id_seq'::regclass);


--
-- TOC entry 1993 (class 2604 OID 183068)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY menu_translate ALTER COLUMN id SET DEFAULT nextval('menu_translate_id_seq'::regclass);


--
-- TOC entry 1994 (class 2604 OID 183077)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY page ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- TOC entry 1995 (class 2604 OID 183083)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY page_translate ALTER COLUMN id SET DEFAULT nextval('page_translate_id_seq'::regclass);


--
-- TOC entry 1998 (class 2604 OID 183102)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication ALTER COLUMN id SET DEFAULT nextval('publication_id_seq'::regclass);


--
-- TOC entry 2000 (class 2604 OID 183112)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_translate ALTER COLUMN id SET DEFAULT nextval('publication_translate_id_seq'::regclass);


--
-- TOC entry 1996 (class 2604 OID 183092)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_type ALTER COLUMN id SET DEFAULT nextval('publication_type_id_seq'::regclass);


--
-- TOC entry 2001 (class 2604 OID 183121)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_type_translate ALTER COLUMN id SET DEFAULT nextval('publication_type_translate_id_seq'::regclass);


--
-- TOC entry 2002 (class 2604 OID 183130)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY seo_manager ALTER COLUMN id SET DEFAULT nextval('seo_manager_id_seq'::regclass);


--
-- TOC entry 2003 (class 2604 OID 183139)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY translate ALTER COLUMN id SET DEFAULT nextval('translate_id_seq'::regclass);


--
-- TOC entry 2004 (class 2604 OID 183148)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tree_category ALTER COLUMN id SET DEFAULT nextval('tree_category_id_seq'::regclass);


--
-- TOC entry 2007 (class 2604 OID 183159)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tree_category_translate ALTER COLUMN id SET DEFAULT nextval('tree_category_translate_id_seq'::regclass);


--
-- TOC entry 2186 (class 0 OID 183027)
-- Dependencies: 174
-- Data for Name: admin_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY admin_user (id, role, login, email, name, password, active) FROM stdin;
1	admin	admin	web@wezoom.net	Admin Name	$2y$10$IgvGXdrkaRpuXnQLcpva3ebuRdNqbcY7NvlS9aluVQIgHWLf1bIMa	1
2	admin	yona	yona@wezoom.net	Yona CMS User	$2y$10$2UUYmTf4f13el.b5K69WmeijY6E/nY4.hRYaokNe/lfyfvJ3Bz05O	1
\.


--
-- TOC entry 2238 (class 0 OID 0)
-- Dependencies: 173
-- Name: admin_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('admin_user_id_seq', 3, false);


--
-- TOC entry 2187 (class 0 OID 183036)
-- Dependencies: 175
-- Data for Name: cms_configuration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY cms_configuration (key, value) FROM stdin;
ADMIN_EMAIL	webmaster@localhost
DEBUG_MODE	1
DISPLAY_CHANGELOG	1
PROFILER	1
TECHNICAL_WORKS	0
WIDGETS_CACHE	1
\.


--
-- TOC entry 2188 (class 0 OID 183039)
-- Dependencies: 176
-- Data for Name: cms_javascript; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY cms_javascript (id, text) FROM stdin;
body	<!-- custom javascript code or any html -->
head	<!-- custom javascript code or any html -->
\.


--
-- TOC entry 2190 (class 0 OID 183047)
-- Dependencies: 178
-- Data for Name: language; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY language (id, iso, locale, name, short_name, url, sortorder, "primary") FROM stdin;
1	ru	ru_RU	Русский	Рус	ru	3	0
2	en	en_EN	English	Eng	en	1	1
3	uk	uk_UA	Українська	Укр	uk	2	0
\.


--
-- TOC entry 2239 (class 0 OID 0)
-- Dependencies: 177
-- Name: language_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('language_id_seq', 4, false);


--
-- TOC entry 2192 (class 0 OID 183054)
-- Dependencies: 180
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY menu (id, root, parent_id, work_title, depth, left_key, right_key, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 2240 (class 0 OID 0)
-- Dependencies: 179
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('menu_id_seq', 1, false);


--
-- TOC entry 2194 (class 0 OID 183065)
-- Dependencies: 182
-- Data for Name: menu_translate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY menu_translate (id, foreign_id, lang, key, value) FROM stdin;
\.


--
-- TOC entry 2241 (class 0 OID 0)
-- Dependencies: 181
-- Name: menu_translate_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('menu_translate_id_seq', 1, false);


--
-- TOC entry 2196 (class 0 OID 183074)
-- Dependencies: 184
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY page (id, slug, created_at, updated_at) FROM stdin;
1	index	2014-08-03 15:18:47	2015-06-18 16:00:29
2	contacts	2014-08-03 22:25:13	2015-06-18 16:08:00
\.


--
-- TOC entry 2242 (class 0 OID 0)
-- Dependencies: 183
-- Name: page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('page_id_seq', 3, false);


--
-- TOC entry 2198 (class 0 OID 183080)
-- Dependencies: 186
-- Data for Name: page_translate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY page_translate (id, foreign_id, lang, key, value) FROM stdin;
1	1	ru	title	Главная
2	1	ru	meta_title	Главная
3	1	ru	meta_description	meta-описание главной страницы
4	1	ru	meta_keywords	
5	1	ru	text	<h1>Yona CMS</h1>\r\n<p>Yona CMS - система управления контентом с открытым исходным кодом. Написана на <a href="http://phalconphp.com/" target="_blank">Phalcon PHP Framework</a>&nbsp;(версия 1.3.x).</p>\r\n<p>Имеет удобную модульную структуру. Предназначена для разработки как простых сайтов, так и крупных порталов и веб-приложений. Благодаря простой конфигурации и архитектуре, может быть легко модифицирована под любую задачу.</p>\r\n<p>Официальный репозиторий на&nbsp;<a href="https://github.com/oleksandr-torosh/yona-cms" target="_blank">Github</a></p>\r\n<h2>Подзаголовок</h2>\r\n<p>Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;Съешь еще этих мягких французских булок да выпей чаю.&nbsp;</p>\r\n<h3>Под-подзаголовок</h3>\r\n<p>Список:</p>\r\n<ul>\r\n<li>Первый&nbsp;пункт</li>\r\n<li>Второй пукт<br />\r\n<ul>\r\n<li>Вложенный уровень второго пункта</li>\r\n<li>Еще один</li>\r\n</ul>\r\n</li>\r\n<li>Третий пункт</li>\r\n</ul>\r\n<p>Таблица</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Заглавие</th><th>Заглавие</th><th>Заглавие</th></tr>\r\n<tr>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n</tr>\r\n<tr>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n<td>Текст в ячейке</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Числовой список:</p>\r\n<ol>\r\n<li>Первый</li>\r\n<li>Второй</li>\r\n<li>Третий</li>\r\n</ol>
6	1	en	title	Homepage
7	1	en	* TRIAL * 	Homepage
8	1	en	meta_description	meta-description of homepage
9	1	en	meta_keywords	
10	1	en	text	<h1>Yona CMS</h1>\r\n<p>Yona CMS - open source content management system. Written in <a href="http://phalconphp.com/" target="_blank">Phalcon PHP Framework</a>&nbsp;(version 1.3.x).</p>\r\n<p>Has a convenient modular structure. It is intended for the design of simple sites, and major portals and web applications. Thanks to its simple configuration and architecture, can be easily modified for any task.</p>\r\n<p>The official repository on&nbsp;<a href="https://github.com/oleksandr-torosh/yona-cms" target="_blank">Github</a></p>\r\n<h2>Subtitle</h2>\r\n<p>Proin aliquet eros vel magna semper facilisis. Nunc tellus urna, bibendum vitae malesuada vel, molestie non lectus. Suspendisse sit amet ante arcu. Maecenas interdum eu neque eu dapibus. Sed maximus elementum tortor at dapibus. Phasellus rhoncus odio vel suscipit dapibus. Nullam sed luctus mauris. Nunc blandit vitae nisl at malesuada. Sed ac est ut diam hendrerit sodales quis et massa. Proin aliquet vitae massa luctus ultricies. Nullam accumsan leo nibh, non varius tortor elementum auctor. Fusce sollicitudin a dui porttitor euismod. Ut at iaculis neque, nec finibus diam. Integer pharetra vehicula urna vitae imperdiet.</p>\r\n<h3>sub-subtitle</h3>\r\n<p>List:</p>\r\n<ul>\r\n<li>First item</li>\r\n<li>Second item<br />\r\n<ul>\r\n<li>Inner level of second item</li>\r\n<li>Another one</li>\r\n</ul>\r\n</li>\r\n<li>Third item</li>\r\n</ul>\r\n<p>Table</p>\r\n<table class="table" style="width: 100%;">\r\n<tbody>\r\n<tr><th>Header</th><th>Header</th><th>Header</th></tr>\r\n<tr>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n</tr>\r\n<tr>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n<td>Text in cell</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Decimal list:</p>\r\n<ol>\r\n<li>First</li>\r\n<li>Second</li>\r\n<li>Third</li>\r\n</ol>
11	2	ru	title	* TRIAL 
12	2	ru	meta_title	* TRIAL 
13	2	ru	meta_description	
14	2	ru	meta_keywords	
15	2	ru	text	<h2>Контакты</h2>\r\n<p><a href="http://yonacms.com">http://yonacms.com</a></p>
16	2	en	title	Contacts
17	2	en	meta_title	Contacts
18	2	en	meta_description	
19	2	en	meta_keywords	
20	2	en	text	<h1>Contacts</h1>\r\n<p><a href="http://yonacms.com">http://yonacms.com</a></p>
21	1	uk	title	Головна
22	1	uk	meta_title	Головна
23	1	uk	meta_description	meta-description головної сторінки
24	1	uk	meta_keywords	
25	1	uk	text	* TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL 
26	2	uk	title	Контакти
27	2	uk	meta_title	Контакти
28	2	uk	meta_description	
29	2	uk	meta_keywords	
30	2	uk	text	<h1>Контакти</h1>\r\n<p><a href="http://yonacms.com">http://yonacms.com</a></p>
\.


--
-- TOC entry 2243 (class 0 OID 0)
-- Dependencies: 185
-- Name: page_translate_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('page_translate_id_seq', 31, false);


--
-- TOC entry 2202 (class 0 OID 183099)
-- Dependencies: 190
-- Data for Name: publication; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY publication (id, type_id, slug, created_at, updated_at, date, preview_inner, preview_src) FROM stdin;
1	1	phalcon-132-released	2014-08-22 10:33:26	2015-06-26 16:48:36	2014-08-19 00:00:00	0	* TRIAL * TRIAL * TRIAL * TRIAL 
2	1	phalcon-community-hangout	2014-08-22 10:42:08	2015-06-26 16:48:44	2014-08-21 00:00:00	1	img/original/publication/0/2.jpg
3	2	builtwith-phalcon	2014-11-05 18:00:20	2015-06-26 16:48:53	2014-11-05 00:00:00	1	* TRIAL * TRIAL * TRIAL * TRIAL 
4	2	* TRIAL * TRIA	2014-11-06 18:23:17	2015-06-26 16:49:02	2014-11-06 00:00:00	0	img/original/publication/0/4.jpg
5	1	new-modular-widgets-system	2015-04-29 10:42:49	2015-06-30 17:12:13	2015-06-05 14:32:44	0	* TRIAL * TRIAL * TRIAL * TRIAL 
\.


--
-- TOC entry 2244 (class 0 OID 0)
-- Dependencies: 189
-- Name: publication_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('publication_id_seq', 6, false);


--
-- TOC entry 2204 (class 0 OID 183109)
-- Dependencies: 192
-- Data for Name: publication_translate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY publication_translate (id, foreign_id, lang, key, value) FROM stdin;
1	1	ru	title	Релиз Phalcon 1.3.2
2	1	ru	meta_title	Релиз Phalcon 1.3.2
3	1	ru	* TRIAL * TRIAL 	
4	1	ru	meta_keywords	
5	1	ru	text	<p>Релиз Phalcon 1.3.2. Дальше текст на английском...</p>\r\n<p>We are today releasing the much awaited 1.3.2 version.&nbsp;</p>\r\n<p>This version has a ton of contributions from our community and fixes to the framework. We thank everyone that has worked on this release, especially with their contributions both to 1.3.2 and our work in progress 2.0.0.</p>\r\n<p>Many thanks to dreamsxin, <a href="https://github.com/mruz">mruz</a>, <a href="https://github.com/kjdev">kjdev</a>, <a href="https://github.com/Cinderella-Man">Cinderella-Man</a>, <a href="https://github.com/andreadelfino">andreadelfino</a>, <a href="https://github.com/kfll">kfll</a>, <a href="https://github.com/brandonlamb">brandonlamb</a>, <a href="https://github.com/zacek">zacek</a>, <a href="https://github.com/joni">joni</a>, <a href="https://github.com/wandersonwhcr">wandersonwhcr</a>, <a href="https://github.com/kevinhatry">kevinhatry</a>, <a href="https://github.com/alkana">alkana</a> and many others that have contributed either on <a href="https://github.com/phalcon/cphalcon">Github or through discussion in our </a><a href="http://forum.phalconphp.com/">forum</a>.</p>\r\n<p>The changelog can be found <a href="https://github.com/phalcon/cphalcon/blob/master/CHANGELOG">here</a>.</p>\r\n<p>We also have a number of pull requests that have not made it to 1.3.2 but will be included to 1.3.3. We need to make sure that the fix or feature that each pull request offers are present both in 1.3.3 but also in 2.0.0</p>\r\n<p>A big thank you once again to our community! You guys are awesome!</p>\r\n<p>&lt;3 Phalcon Team</p>
6	2	ru	title	Видеовстреча сообщества Phalcon
7	2	ru	meta_title	Видеовстреча сообщества Phalcon
8	2	ru	meta_description	
9	2	ru	meta_keywords	
10	2	ru	text	<p>Видеовстреча сообщества Phalcon.&nbsp;Дальше текст на английском...</p>\r\n<p>Yesterday (2014-04-05) we had our first Phalcon community hangout. The main purpose of the hangout was to meet the community, discuss about what Phalcon is and what our future steps are, and hear news, concerns, success stories from the community itself.</p>\r\n<p>We are excited to announce that the first Phalcon community hangout was a great success!</p>\r\n<p>We had an awesome turnout from all around the world, with members of the community filling the hangout (10 concurrent users) and more viewing online, asking questions and interacting with the team.</p>\r\n<p>We talked about where we are, where we came from and what the future steps are with Zephir and Phalcon 2.0. Contributions, bugs and NFRs were also topics in our discussion, as well as who are team and how Phalcon is funded.</p>\r\n<p>More hangouts will be scheduled in the near future, hopefully making this a regular event for our community. We will also cater for members of the community that are not English speakers, by creating hangouts for Spanish speaking, Russian etc. The goal is to engage as many members as possible!</p>\r\n<p>The love and trust you all have shown to our framework is what drives us to make it better, push performance, introduce more features and make Phalcon the best PHP framework there is.&nbsp;</p>\r\n<p>For those that missed it, the video is below.</p>
11	1	en	title	Phalcon 1.3.2 Released
12	1	en	meta_title	* TRIAL * TRIAL * TRIA
13	1	en	meta_description	
14	1	en	meta_keywords	
15	1	en	text	<p>We are today releasing the much awaited 1.3.2 version.&nbsp;</p>\r\n<p>This version has a ton of contributions from our community and fixes to the framework. We thank everyone that has worked on this release, especially with their contributions both to 1.3.2 and our work in progress 2.0.0.</p>\r\n<p>Many thanks to dreamsxin, <a href="https://github.com/mruz">mruz</a>, <a href="https://github.com/kjdev">kjdev</a>, <a href="https://github.com/Cinderella-Man">Cinderella-Man</a>, <a href="https://github.com/andreadelfino">andreadelfino</a>, <a href="https://github.com/kfll">kfll</a>, <a href="https://github.com/brandonlamb">brandonlamb</a>, <a href="https://github.com/zacek">zacek</a>, <a href="https://github.com/joni">joni</a>, <a href="https://github.com/wandersonwhcr">wandersonwhcr</a>, <a href="https://github.com/kevinhatry">kevinhatry</a>, <a href="https://github.com/alkana">alkana</a> and many others that have contributed either on <a href="https://github.com/phalcon/cphalcon">Github or through discussion in our </a><a href="http://forum.phalconphp.com/">forum</a>.</p>\r\n<p>The changelog can be found <a href="https://github.com/phalcon/cphalcon/blob/master/CHANGELOG">here</a>.</p>\r\n<p>We also have a number of pull requests that have not made it to 1.3.2 but will be included to 1.3.3. We need to make sure that the fix or feature that each pull request offers are present both in 1.3.3 but also in 2.0.0</p>\r\n<p>A big thank you once again to our community! You guys are awesome!</p>\r\n<p>&lt;3 Phalcon Team</p>
16	1	uk	title	Реліз Phalcon 1.3.2
17	1	uk	meta_title	* TRIAL * TRIAL * T
18	1	uk	meta_description	
19	1	uk	meta_keywords	
20	1	uk	text	<p>Реліз Phalcon 1.3.2. Далі текст англійською...</p>\r\n<p>We are today releasing the much awaited 1.3.2 version.&nbsp;</p>\r\n<p>This version has a ton of contributions from our community and fixes to the framework. We thank everyone that has worked on this release, especially with their contributions both to 1.3.2 and our work in progress 2.0.0.</p>\r\n<p>Many thanks to dreamsxin, <a href="https://github.com/mruz">mruz</a>, <a href="https://github.com/kjdev">kjdev</a>, <a href="https://github.com/Cinderella-Man">Cinderella-Man</a>, <a href="https://github.com/andreadelfino">andreadelfino</a>, <a href="https://github.com/kfll">kfll</a>, <a href="https://github.com/brandonlamb">brandonlamb</a>, <a href="https://github.com/zacek">zacek</a>, <a href="https://github.com/joni">joni</a>, <a href="https://github.com/wandersonwhcr">wandersonwhcr</a>, <a href="https://github.com/kevinhatry">kevinhatry</a>, <a href="https://github.com/alkana">alkana</a> and many others that have contributed either on <a href="https://github.com/phalcon/cphalcon">Github or through discussion in our </a><a href="http://forum.phalconphp.com/">forum</a>.</p>\r\n<p>The changelog can be found <a href="https://github.com/phalcon/cphalcon/blob/master/CHANGELOG">here</a>.</p>\r\n<p>We also have a number of pull requests that have not made it to 1.3.2 but will be included to 1.3.3. We need to make sure that the fix or feature that each pull request offers are present both in 1.3.3 but also in 2.0.0</p>\r\n<p>A big thank you once again to our community! You guys are awesome!</p>\r\n<p>&lt;3 Phalcon Team</p>
21	2	en	title	* TRIAL * TRIAL * TRIAL *
22	2	en	meta_title	* TRIAL * TRIAL * TRIAL *
23	2	en	meta_description	
24	2	en	meta_keywords	
53	4	en	meta_description	
54	4	en	meta_keywords	
55	4	en	text	* TRIAL * TRIAL * TRIAL * 
56	4	uk	title	* TRIAL * TR
25	2	en	text	<p>Yesterday (2014-04-05) we had our first Phalcon community hangout. The main purpose of the hangout was to meet the community, discuss about what Phalcon is and what our future steps are, and hear news, concerns, success stories from the community itself.</p>\r\n<p>We are excited to announce that the first Phalcon community hangout was a great success!</p>\r\n<p>We had an awesome turnout from all around the world, with members of the community filling the hangout (10 concurrent users) and more viewing online, asking questions and interacting with the team.</p>\r\n<p>We talked about where we are, where we came from and what the future steps are with Zephir and Phalcon 2.0. Contributions, bugs and NFRs were also topics in our discussion, as well as who are team and how Phalcon is funded.</p>\r\n<p>More hangouts will be scheduled in the near future, hopefully making this a regular event for our community. We will also cater for members of the community that are not English speakers, by creating hangouts for Spanish speaking, Russian etc. The goal is to engage as many members as possible!</p>\r\n<p>The love and trust you all have shown to our framework is what drives us to make it better, push performance, introduce more features and make Phalcon the best PHP framework there is.&nbsp;</p>\r\n<p>For those that missed it, the video is below.</p>
26	2	uk	title	Відеозустріч спільноти Phalcon
27	2	uk	meta_title	Відеозустріч спільноти Phalcon
28	2	uk	meta_description	
29	2	uk	* TRIAL * TRI	
30	2	uk	text	* TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL *
31	3	ru	title	BuiltWith Phalcon
32	3	ru	meta_title	BuiltWith Phalcon
33	3	ru	meta_description	
34	3	ru	* TRIAL * TRI	
35	3	ru	text	* TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * 
36	4	ru	title	* TRIAL * TRI
37	4	ru	meta_title	Вторая статья
38	4	ru	meta_description	
39	4	ru	meta_keywords	
40	4	ru	text	<p>Текст второй статьи</p>
41	3	en	title	BuiltWith Phalcon
42	3	en	* TRIAL * 	BuiltWith Phalcon
43	3	en	* TRIAL * TRIAL 	
44	3	en	* TRIAL * TRI	
45	3	en	text	* TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * 
46	3	uk	title	BuiltWith Phalcon
47	3	uk	meta_title	* TRIAL * TRIAL *
48	3	uk	meta_description	
49	3	uk	meta_keywords	
50	3	uk	text	* TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * TRIAL * 
51	4	en	title	Second article
52	4	en	meta_title	* TRIAL * TRIA
57	4	uk	* TRIAL * 	Друга стаття
58	4	uk	meta_description	
59	4	uk	meta_keywords	
60	4	uk	text	<p>Текст другої статті</p>
61	5	en	title	New modular widgets system
62	5	en	meta_title	New widgets system
63	5	en	meta_description	
64	5	en	meta_keywords	
65	5	en	text	<p>Here is the new features of YonaCMS - "System of modular widgets".</p>\r\n<p>Now, in any of your modules, you can create dynamic widgets with their business logic and templates. Forget about dozens of separate helper and the need to do the same routine operations! Also, this scheme will maintain cleanliness and order in the code for your project.</p>\r\n<p>Call each widget can be produced directly from the template Volt with the transfer set of parameters. Each widget is automatically cached and does not lead to additional load on the database. Caching can be disabled in the administrative panel, see Admin -&gt; Settings, option "Widgets caching". Automatic regeneration of the cache is carried out after 60 seconds.</p>\r\n<p>As an example of such a call is made to the widget template's main page /app/modules/Index/views/index.volt</p>\r\n<pre>{{Helper.widget ('Publication'). LastNews ()}}</pre>\r\n<p><br />Files widget:<br />/app/modules/Publication/Widget/PublicationWidget.php - inherits \\ Application \\ Widget \\ AbstractWidget<br />/app/modules/Publication/views/widget/last-news.volt - template output</p>\r\n<p>The main class of the widget - \\ Application \\ Widget \\ Proxy<br />It is possible to set the default value for time caching.</p>\r\n<p>This system will be very useful for developers who have a lot of individual information units, as well as those who want to keep your code clean and easy tool to use.</p>
66	5	ru	title	* TRIAL * TRIAL * TRIAL * TRIAL 
67	5	ru	meta_title	* TRIAL * TRIAL * TRIA
68	5	ru	meta_description	
69	5	ru	meta_keywords	
70	5	ru	text	<p>Представляем вам новый функционал от YonaCMS - "Систему модульных виджетов".</p>\r\n<p>Теперь в любом из ваших модулей вы можете создать динамические виджеты со своей бизнес-логикой и шаблонами. Забудьте о десятках отдельных хелперов и необходимости делать одни и те же рутинные операции! Также эта схема позволит поддерживать чистоту и порядок в коде вашего проекта.</p>\r\n<p>Вызов каждого виджета может быть произведен непосредственно с шаблона Volt с передачей набора параметров. Каждый виджет автоматически кешируется и не влечет дополнительной нагрузки на базу данных. Кеширование можно отключить в административной панели в разделе Admin -&gt; Settings, опция "Widgets caching". Автоматическая перегенерация кеша осуществляется через 60 секунд.</p>\r\n<p>В качестве примера сделан вызов такого виджета в шаблоне главной страницы /app/modules/Index/views/index.volt</p>\r\n<pre>{{ helper.widget('Publication').lastNews() }}</pre>\r\n<p><br />Файлы виджета:<br />/app/modules/Publication/Widget/PublicationWidget.php - наследует класс \\Application\\Widget\\AbstractWidget<br />/app/modules/Publication/views/widget/last-news.volt - шаблон вывода</p>\r\n<p>Основной класс системы виджетов - \\Application\\Widget\\Proxy<br />В нем можно установить дефолтное значение времени кеширования.</p>\r\n<p>Данная система будет очень полезна для разработчиков, которые имеют много отдельных информационных блоков, а также тем, кто хочет поддерживать свой код в чистоте и пользоваться удобным инструментом.</p>
71	5	uk	title	Нова система модульних віджетів
72	5	uk	meta_title	Нова система віджетів
73	5	uk	meta_description	
74	5	uk	meta_keywords	
75	5	uk	text	<p>Представляємо вам новий функціонал від YonaCMS - "Систему модульних віджетів".</p>\r\n<p>Тепер в будь-якому з ваших модулів ви можете створити динамічні віджети з власною&nbsp;бізнес-логікою і шаблонами. Забудьте про десятки окремих хелперів та необхідності робити одні і ті ж самі рутинні операції! Також ця схема дозволить підтримувати чистоту і порядок у коді вашого проекту.</p>\r\n<p>Виклик кожного віджета може бути проведений безпосередньо з шаблону Volt з передачею набору параметрів. Кожен віджет автоматично кешуєтся і не тягне додаткового навантаження на базу даних. Кешування можна відключити в адміністративній панелі в розділі Admin -&gt; Settings, опція "Widgets caching". Автоматична перегенерація кеша здійснюється через 60 секунд.</p>\r\n<p>Як приклад зроблений виклик такого віджета в шаблоні головної сторінки /app/modules/Index/views/index.volt</p>\r\n<pre>{{Helper.widget ('Publication'). LastNews ()}}</pre>\r\n<p><br />Файли віджету:<br />/app/modules/Publication/Widget/PublicationWidget.php - успадковує клас \\ Application \\ Widget \\ AbstractWidget<br />/app/modules/Publication/views/widget/last-news.volt - шаблон виводу</p>\r\n<p>Основний клас системи віджетів - \\ Application \\ Widget \\ Proxy<br />У ньому можна встановити дефолтне значення часу кешування.</p>\r\n<p>Дана система буде дуже корисною для розробників, які мають багато окремих інформаційних блоків, а також тим, хто хоче підтримувати свій код в чистоті і користуватися зручним інструментом.</p>
\.


--
-- TOC entry 2245 (class 0 OID 0)
-- Dependencies: 191
-- Name: publication_translate_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('publication_translate_id_seq', 76, false);


--
-- TOC entry 2200 (class 0 OID 183089)
-- Dependencies: 188
-- Data for Name: publication_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY publication_type (id, slug, "limit", format, display_date) FROM stdin;
1	news	10	grid	1
2	articles	10	list	0
\.


--
-- TOC entry 2246 (class 0 OID 0)
-- Dependencies: 187
-- Name: publication_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('publication_type_id_seq', 3, false);


--
-- TOC entry 2206 (class 0 OID 183118)
-- Dependencies: 194
-- Data for Name: publication_type_translate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY publication_type_translate (id, foreign_id, lang, key, value) FROM stdin;
1	1	ru	* TRIAL * 	Новости
2	1	ru	meta_description	
3	1	ru	meta_keywords	
4	1	ru	* TRIAL 	
54	1	en	title	News
55	1	en	head_title	News
56	1	en	meta_description	
57	1	en	meta_keywords	
58	1	en	* TRIAL 	
59	1	uk	title	Новини
60	1	uk	head_title	Новини
61	1	uk	* TRIAL * TRIAL 	
62	1	uk	* TRIAL * TRI	
63	1	uk	seo_text	
64	1	ru	title	Новости
65	2	ru	title	Статьи
66	2	ru	* TRIAL * 	Статьи
67	2	ru	* TRIAL * TRIAL 	
68	2	ru	meta_keywords	
69	2	ru	seo_text	
70	2	en	title	* TRIAL 
71	2	en	head_title	Articles
72	2	en	meta_description	
73	2	en	meta_keywords	
74	2	en	* TRIAL 	
75	2	uk	title	Статті
76	2	uk	head_title	Статті
77	2	uk	meta_description	
78	2	uk	meta_keywords	
79	2	uk	seo_text	
\.


--
-- TOC entry 2247 (class 0 OID 0)
-- Dependencies: 193
-- Name: publication_type_translate_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('publication_type_translate_id_seq', 80, false);


--
-- TOC entry 2208 (class 0 OID 183127)
-- Dependencies: 196
-- Data for Name: seo_manager; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY seo_manager (id, url, head_title, meta_description, meta_keywords, seo_text, created_at, updated_at) FROM stdin;
1	/news	Latest News	Greate latest and fresh news!	news, latest news, fresh news	<p>Presenting your attention the latest news!</p>	2014-09-30 10:39:23	2015-07-02 11:28:57
2	/contacts.html	Yona CMS Contacts				2015-05-21 16:33:14	2015-07-02 11:19:40
\.


--
-- TOC entry 2248 (class 0 OID 0)
-- Dependencies: 195
-- Name: seo_manager_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('seo_manager_id_seq', 3, false);


--
-- TOC entry 2210 (class 0 OID 183136)
-- Dependencies: 198
-- Data for Name: translate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY translate (id, lang, phrase, translation) FROM stdin;
1	ru	Ошибка валидации формы	* TRIAL * TRIAL * TRIA
2	ru	Подробнее	Подробнее
3	ru	Назад к перечню публикаций	Назад к перечню публикаций
4	ru	SITE NAME	Yona CMS Русская версия
5	ru	Главная	Главная
6	ru	Новости	Новости
7	ru	Контакты	Контакты
8	en	Ошибка валидации формы	Form validation fails
9	en	* TRIAL *	Read more
10	en	Назад к перечню публикаций	Back to the publications list
11	en	SITE NAME	Yona CMS
12	en	Главная	Home
13	en	Новости	News
14	en	Контакты	Contacts
15	uk	Ошибка валидации формы	* TRIAL * TRIAL * TRIAL
16	uk	Подробнее	Детальніше
17	uk	Назад к перечню публикаций	* TRIAL * TRIAL * TRIAL * TRIAL * 
18	uk	SITE NAME	Yona CMS Українська версія
19	uk	Главная	Головна
20	uk	Новости	Новини
21	uk	* TRIAL 	* TRIAL 
22	ru	Статьи	Статьи
23	en	Статьи	Articles
24	uk	Статьи	Статті
25	en	Home	Home
26	en	News	News
27	en	Articles	* TRIAL 
28	en	Contacts	Contacts
29	en	Admin	Admin
30	en	YonaCms Admin Panel	* TRIAL * TRIAL * T
31	en	Back к перечню публикаций	Back to publications list
32	en	Страница №	Page num.
33	ru	Home	Главная
34	ru	News	Новости
35	ru	Articles	Статьи
36	ru	* TRIAL 	Контакты
37	ru	Admin	Админка
38	ru	YonaCms Admin Panel	* TRIAL * TRIAL * TRIAL * TRIAL
39	ru	Back к перечню публикаций	* TRIAL * TRIAL * TRIAL * 
40	ru	Страница №	Страница №
41	uk	Home	Головна
42	uk	News	Новини
43	uk	Articles	Статті
44	uk	Contacts	Контакти
45	uk	Admin	Адмінка
46	uk	* TRIAL * TRIAL * T	Адміністративна панель YonaCms
47	uk	Back к перечню публикаций	Назад до переліку публікацій
48	uk	Страница №	Сторінка №
49	en	Полная версия	Full version
50	en	Мобильная версия	Mobile version
51	en	Services	Services
52	en	Printing	Printing
53	en	Design	Design
54	ru	* TRIAL * TRI	* TRIAL * TRI
55	ru	* TRIAL * TRIAL 	Мобильная версия
56	ru	Services	Services
57	ru	* TRIAL 	Printing
58	ru	Design	Design
59	uk	* TRIAL * TRI	Повна версія
60	uk	Мобильная версия	* TRIAL * TRIAL
61	uk	Services	Services
62	uk	Printing	Printing
63	uk	Design	Design
64	en	Latest news	* TRIAL * T
65	ru	* TRIAL * T	* TRIAL * TRIAL *
66	uk	* TRIAL * T	Останні новини
67	en	* TRIAL * TRIAL *	* TRIAL * TRIAL *
68	en	Back to publications list	Back to publications list
69	uk	* TRIAL * TRIAL *	* TRIAL * TRIAL * T
70	uk	* TRIAL * TRIAL * TRIAL *	* TRIAL * TRIAL * TRIAL * TRIAL * 
71	ru	Entries not found	* TRIAL * TRIAL *
72	ru	Back to publications list	Обратно к перечню публикаций
\.


--
-- TOC entry 2249 (class 0 OID 0)
-- Dependencies: 197
-- Name: translate_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('translate_id_seq', 73, false);


--
-- TOC entry 2212 (class 0 OID 183145)
-- Dependencies: 200
-- Data for Name: tree_category; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tree_category (id, root, parent_id, slug, depth, left_key, right_key, created_at, updated_at) FROM stdin;
15	* TRIAL 	\N	computers	1	2	7	2015-05-19 16:46:38	2015-05-20 13:31:24
16	articles	\N	software	1	14	21	2015-05-19 16:47:32	2015-05-20 13:31:25
17	articles	\N	gadgets	1	8	13	2015-05-19 16:47:45	2015-05-20 13:31:25
18	* TRIAL 	16	microsoft	2	17	18	2015-05-19 17:23:44	2015-05-20 13:31:25
19	articles	16	oracle	2	19	20	2015-05-19 17:24:00	2015-05-20 13:31:25
20	* TRIAL 	16	google	2	15	16	2015-05-19 17:24:24	2015-05-20 13:31:25
21	* TRIAL 	15	netbooks	2	3	4	2015-05-19 17:24:49	2015-05-20 13:31:25
22	* TRIAL 	15	laptops	2	5	6	2015-05-19 17:30:49	2015-05-20 13:31:25
23	* TRIAL 	17	smartpfone	2	9	10	2015-05-19 17:32:06	2015-05-20 13:31:25
24	articles	17	tablet	2	11	12	2015-05-19 17:32:53	2015-05-20 13:31:25
25	news	\N	world	1	2	3	2015-05-19 17:33:04	2015-05-20 15:24:45
26	news	\N	business	1	6	11	2015-05-19 17:33:11	2015-05-20 15:24:45
27	news	\N	politics	1	4	5	2015-05-19 17:33:16	2015-05-20 15:24:45
28	news	26	real-estate	2	7	8	2015-05-19 17:33:30	2015-05-20 15:24:45
29	news	26	investitions	2	9	10	2015-05-19 17:33:54	2015-05-20 15:24:45
30	news	\N	life	1	12	17	2015-05-20 15:24:05	2015-05-20 15:24:45
31	news	30	health	2	13	14	2015-05-20 15:24:22	2015-05-20 15:24:45
32	news	30	family	2	15	16	2015-05-20 15:24:42	2015-05-20 15:24:45
\.


--
-- TOC entry 2250 (class 0 OID 0)
-- Dependencies: 199
-- Name: tree_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tree_category_id_seq', 33, false);


--
-- TOC entry 2214 (class 0 OID 183156)
-- Dependencies: 202
-- Data for Name: tree_category_translate; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tree_category_translate (id, foreign_id, lang, key, value) FROM stdin;
14	15	en	title	* TRIAL *
15	16	en	title	Software
16	17	en	title	Gadgets
17	18	en	title	Microsoft
18	19	en	title	Oracle
19	20	en	title	Google
20	21	en	title	* TRIAL 
21	22	en	title	Laptops
22	23	en	title	Smartpfone
23	24	en	title	Tablet
24	25	en	title	World
25	26	en	title	* TRIAL 
26	27	en	title	Politics
27	28	en	title	Real estate
28	29	en	title	* TRIAL * TR
29	30	en	title	Life
30	31	en	title	Health
31	32	en	title	Family
\.


--
-- TOC entry 2251 (class 0 OID 0)
-- Dependencies: 201
-- Name: tree_category_translate_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tree_category_translate_id_seq', 32, false);


--
-- TOC entry 2215 (class 0 OID 183163)
-- Dependencies: 203
-- Data for Name: widget; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY widget (id, title, html) FROM stdin;
phone	Phone in header	<div class="phone">+1 (001) 555-44-33</div>
\.


--
-- TOC entry 2011 (class 2606 OID 183170)
-- Name: primary key; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY admin_user
    ADD CONSTRAINT "primary key" PRIMARY KEY (id);


--
-- TOC entry 2013 (class 2606 OID 183174)
-- Name: primary key1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cms_configuration
    ADD CONSTRAINT "primary key1" PRIMARY KEY (key);


--
-- TOC entry 2042 (class 2606 OID 183201)
-- Name: primary key10; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY publication_translate
    ADD CONSTRAINT "primary key10" PRIMARY KEY (id);


--
-- TOC entry 2046 (class 2606 OID 183205)
-- Name: primary key11; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY publication_type_translate
    ADD CONSTRAINT "primary key11" PRIMARY KEY (id);


--
-- TOC entry 2048 (class 2606 OID 183209)
-- Name: primary key12; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY seo_manager
    ADD CONSTRAINT "primary key12" PRIMARY KEY (id);


--
-- TOC entry 2052 (class 2606 OID 183212)
-- Name: primary key13; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY translate
    ADD CONSTRAINT "primary key13" PRIMARY KEY (id);


--
-- TOC entry 2055 (class 2606 OID 183215)
-- Name: primary key14; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tree_category
    ADD CONSTRAINT "primary key14" PRIMARY KEY (id);


--
-- TOC entry 2060 (class 2606 OID 183219)
-- Name: primary key15; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tree_category_translate
    ADD CONSTRAINT "primary key15" PRIMARY KEY (id);


--
-- TOC entry 2062 (class 2606 OID 183223)
-- Name: primary key16; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY widget
    ADD CONSTRAINT "primary key16" PRIMARY KEY (id);


--
-- TOC entry 2015 (class 2606 OID 183176)
-- Name: primary key2; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY cms_javascript
    ADD CONSTRAINT "primary key2" PRIMARY KEY (id);


--
-- TOC entry 2018 (class 2606 OID 183178)
-- Name: primary key3; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY language
    ADD CONSTRAINT "primary key3" PRIMARY KEY (id);


--
-- TOC entry 2021 (class 2606 OID 183181)
-- Name: primary key4; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT "primary key4" PRIMARY KEY (id);


--
-- TOC entry 2025 (class 2606 OID 183185)
-- Name: primary key5; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY menu_translate
    ADD CONSTRAINT "primary key5" PRIMARY KEY (id);


--
-- TOC entry 2027 (class 2606 OID 183188)
-- Name: primary key6; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT "primary key6" PRIMARY KEY (id);


--
-- TOC entry 2032 (class 2606 OID 183191)
-- Name: primary key7; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY page_translate
    ADD CONSTRAINT "primary key7" PRIMARY KEY (id);


--
-- TOC entry 2034 (class 2606 OID 183195)
-- Name: primary key8; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY publication_type
    ADD CONSTRAINT "primary key8" PRIMARY KEY (id);


--
-- TOC entry 2037 (class 2606 OID 183198)
-- Name: primary key9; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY publication
    ADD CONSTRAINT "primary key9" PRIMARY KEY (id);


--
-- TOC entry 2008 (class 1259 OID 183171)
-- Name: email; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX email ON admin_user USING btree (email);


--
-- TOC entry 2023 (class 1259 OID 183186)
-- Name: foreign_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX foreign_id ON menu_translate USING btree (foreign_id);


--
-- TOC entry 2029 (class 1259 OID 183192)
-- Name: foreign_id1; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX foreign_id1 ON page_translate USING btree (foreign_id);


--
-- TOC entry 2039 (class 1259 OID 183202)
-- Name: foreign_id2; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX foreign_id2 ON publication_translate USING btree (foreign_id);


--
-- TOC entry 2043 (class 1259 OID 183206)
-- Name: foreign_id3; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX foreign_id3 ON publication_type_translate USING btree (foreign_id);


--
-- TOC entry 2057 (class 1259 OID 183220)
-- Name: foreign_id4; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX foreign_id4 ON tree_category_translate USING btree (foreign_id);


--
-- TOC entry 2016 (class 1259 OID 183179)
-- Name: iso; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX iso ON language USING btree (iso);


--
-- TOC entry 2030 (class 1259 OID 183193)
-- Name: lang; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX lang ON page_translate USING btree (lang);


--
-- TOC entry 2040 (class 1259 OID 183203)
-- Name: lang1; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX lang1 ON publication_translate USING btree (lang);


--
-- TOC entry 2044 (class 1259 OID 183207)
-- Name: lang2; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX lang2 ON publication_type_translate USING btree (lang);


--
-- TOC entry 2050 (class 1259 OID 183213)
-- Name: lang3; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX lang3 ON translate USING btree (lang);


--
-- TOC entry 2058 (class 1259 OID 183221)
-- Name: lang4; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX lang4 ON tree_category_translate USING btree (lang);


--
-- TOC entry 2009 (class 1259 OID 183172)
-- Name: login; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX login ON admin_user USING btree (login);


--
-- TOC entry 2019 (class 1259 OID 183183)
-- Name: parent_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX parent_id ON menu USING btree (parent_id);


--
-- TOC entry 2053 (class 1259 OID 183217)
-- Name: parent_id1; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX parent_id1 ON tree_category USING btree (parent_id);


--
-- TOC entry 2022 (class 1259 OID 183182)
-- Name: slug; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX slug ON menu USING btree (work_title);


--
-- TOC entry 2028 (class 1259 OID 183189)
-- Name: slug1; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX slug1 ON page USING btree (slug);


--
-- TOC entry 2035 (class 1259 OID 183196)
-- Name: slug2; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX slug2 ON publication_type USING btree (slug);


--
-- TOC entry 2056 (class 1259 OID 183216)
-- Name: slug3; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX slug3 ON tree_category USING btree (slug);


--
-- TOC entry 2038 (class 1259 OID 183199)
-- Name: type_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX type_id ON publication USING btree (type_id);


--
-- TOC entry 2049 (class 1259 OID 183210)
-- Name: url; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX url ON seo_manager USING btree (url);


--
-- TOC entry 2063 (class 2606 OID 183224)
-- Name: menu_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT menu_ibfk_1 FOREIGN KEY (parent_id) REFERENCES menu(id);


--
-- TOC entry 2064 (class 2606 OID 183229)
-- Name: menu_translate_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY menu_translate
    ADD CONSTRAINT menu_translate_ibfk_1 FOREIGN KEY (foreign_id) REFERENCES menu(id);


--
-- TOC entry 2065 (class 2606 OID 183234)
-- Name: page_translate_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY page_translate
    ADD CONSTRAINT page_translate_ibfk_1 FOREIGN KEY (foreign_id) REFERENCES page(id);


--
-- TOC entry 2066 (class 2606 OID 183239)
-- Name: page_translate_ibfk_2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY page_translate
    ADD CONSTRAINT page_translate_ibfk_2 FOREIGN KEY (lang) REFERENCES language(iso);


--
-- TOC entry 2067 (class 2606 OID 183244)
-- Name: publication_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication
    ADD CONSTRAINT publication_ibfk_1 FOREIGN KEY (type_id) REFERENCES publication_type(id);


--
-- TOC entry 2068 (class 2606 OID 183249)
-- Name: publication_translate_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_translate
    ADD CONSTRAINT publication_translate_ibfk_1 FOREIGN KEY (foreign_id) REFERENCES publication(id);


--
-- TOC entry 2069 (class 2606 OID 183254)
-- Name: publication_translate_ibfk_2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_translate
    ADD CONSTRAINT publication_translate_ibfk_2 FOREIGN KEY (lang) REFERENCES language(iso);


--
-- TOC entry 2070 (class 2606 OID 183259)
-- Name: publication_type_translate_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_type_translate
    ADD CONSTRAINT publication_type_translate_ibfk_1 FOREIGN KEY (foreign_id) REFERENCES publication_type(id);


--
-- TOC entry 2071 (class 2606 OID 183264)
-- Name: publication_type_translate_ibfk_2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY publication_type_translate
    ADD CONSTRAINT publication_type_translate_ibfk_2 FOREIGN KEY (lang) REFERENCES language(iso);


--
-- TOC entry 2072 (class 2606 OID 183269)
-- Name: translate_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY translate
    ADD CONSTRAINT translate_ibfk_1 FOREIGN KEY (lang) REFERENCES language(iso);


--
-- TOC entry 2073 (class 2606 OID 183274)
-- Name: tree_category_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tree_category
    ADD CONSTRAINT tree_category_ibfk_1 FOREIGN KEY (parent_id) REFERENCES tree_category(id);


--
-- TOC entry 2074 (class 2606 OID 183279)
-- Name: tree_category_translate_ibfk_1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tree_category_translate
    ADD CONSTRAINT tree_category_translate_ibfk_1 FOREIGN KEY (foreign_id) REFERENCES tree_category(id);


--
-- TOC entry 2075 (class 2606 OID 183284)
-- Name: tree_category_translate_ibfk_2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tree_category_translate
    ADD CONSTRAINT tree_category_translate_ibfk_2 FOREIGN KEY (lang) REFERENCES language(iso);


--
-- TOC entry 2222 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2016-10-05 15:36:30

--
-- PostgreSQL database dump complete
--

