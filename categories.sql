-- PostgreSQL database dump
--

-- Dumped from database version 13.11
-- Dumped by pg_dump version 13.11

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
-- Name: check_parent_category(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.check_parent_category() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    is_loop BOOLEAN;
BEGIN
    IF NEW.parent_id = NEW.id THEN
        RAISE EXCEPTION 'Invalid parent category selected';
    END IF;

    -- Kiểm tra xem danh mục cha đã là con của danh mục hiện tại chưa (xung đột vòng lặp vô tận)
    WITH RECURSIVE category_path AS (
        SELECT id, parent_id
        FROM categories
        WHERE id = NEW.parent_id
        UNION ALL
        SELECT c.id, c.parent_id
        FROM categories c
        JOIN category_path cp ON c.id = cp.parent_id
    )
    SELECT EXISTS (
        SELECT 1
        FROM category_path
        WHERE id = NEW.id
    ) INTO STRICT is_loop;

    IF is_loop THEN
        RAISE EXCEPTION 'Category loop detected';
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.check_parent_category() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    parent_id integer,
    CONSTRAINT check_parent_not_self CHECK ((parent_id <> id))
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, parent_id) FROM stdin;
117	Danh mục 1	\N
1	Danh mục 1	\N
2	Danh mục 2	1
3	Danh mục 3	1
4	Danh mục 4	2
5	Danh mục 5	3
6	Danh mục 6	2
7	Danh mục 7	\N
8	Danh mục 8	7
9	Danh mục 9	7
10	Danh mục 10	8
\.


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 117, true);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: categories before_insert_update_category; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER before_insert_update_category BEFORE INSERT OR UPDATE ON public.categories FOR EACH ROW EXECUTE FUNCTION public.check_parent_category();


--
-- Name: categories categories_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES public.categories(id);


--
-- PostgreSQL database dump complete
--
