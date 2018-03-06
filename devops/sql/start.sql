--
-- PostgreSQL database dump
--

-- Dumped from database version 10.2 (Debian 10.2-1.pgdg90+1)
-- Dumped by pg_dump version 10.2 (Debian 10.2-1.pgdg90+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: category; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE category (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    tax_percentage numeric(9,2) NOT NULL
);


--
-- Name: category_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE category_id_seq OWNED BY category.id;


--
-- Name: product; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE product (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    price numeric(9,2) NOT NULL,
    category_id integer NOT NULL
);


--
-- Name: product_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE product_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: product_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE product_id_seq OWNED BY product.id;


--
-- Name: category id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY category ALTER COLUMN id SET DEFAULT nextval('category_id_seq'::regclass);


--
-- Name: product id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY product ALTER COLUMN id SET DEFAULT nextval('product_id_seq'::regclass);


--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: -
--

COPY category (id, name, tax_percentage) FROM stdin;
2	Celulares e Smartphone	39.80
6	Livros	11.00
3	Jogos	28.00
\.


--
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: -
--

COPY product (id, name, price, category_id) FROM stdin;
2	Smartphone Samsung Galaxy J7	1397.10	2
3	iPhone 8 Dourado 64GB	3519.12	2
4	iPhone 7 32GB Preto Matte	3199.00	2
5	Box Percy Jackson e os Olimpianos (5 Volumes)	79.90	6
6	O Encontro dos Clássicos: Game of Thrones & Senhor dos Anéis	179.00	6
7	Livro Origem	11.00	6
8	God Of War - PS4	199.00	3
9	FIFA 18 - PS4	198.90	3
10	The Last Of Us Remasterizado - PS4	59.90	3
\.


--
-- Name: category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('category_id_seq', 6, true);


--
-- Name: product_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('product_id_seq', 10, true);


--
-- Name: category category_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: product product_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- Name: category_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX category_id_uindex ON category USING btree (id);


--
-- Name: product_id_uindex; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX product_id_uindex ON product USING btree (id);


--
-- Name: product product_category_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_category_fk FOREIGN KEY (category_id) REFERENCES category(id);


--
-- PostgreSQL database dump complete
--

