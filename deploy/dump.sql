toc.dat                                                                                             100600  004000  002000  00000135052 12167500070 007306  0                                                                                                    ustar00                                                                                                                                                                                                                                                        PGDMP       1    3                q            rekada    9.1.8    9.1.8 �    
           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                       false         
           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                       false         
           1262    26751    rekada    DATABASE     x   CREATE DATABASE rekada WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'ru_RU.UTF-8' LC_CTYPE = 'ru_RU.UTF-8';
    DROP DATABASE rekada;
             xu    false                     2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
             pgsql    false         
           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                  pgsql    false    5         
           0    0    public    ACL     �   REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM pgsql;
GRANT ALL ON SCHEMA public TO pgsql;
GRANT ALL ON SCHEMA public TO PUBLIC;
                  pgsql    false    5         �            3079    11955    plpgsql 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;
    DROP EXTENSION plpgsql;
                  false         
           0    0    EXTENSION plpgsql    COMMENT     @   COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';
                       false    191         �            3079    26752    ltree 	   EXTENSION     9   CREATE EXTENSION IF NOT EXISTS ltree WITH SCHEMA public;
    DROP EXTENSION ltree;
                  false    5         
           0    0    EXTENSION ltree    COMMENT     Q   COMMENT ON EXTENSION ltree IS 'data type for hierarchical tree-like structures';
                       false    192                    1255    28530 %   get_calculated_page_node_path(bigint)    FUNCTION     %  CREATE FUNCTION get_calculated_page_node_path(param_page_id bigint) RETURNS ltree
    LANGUAGE sql
    AS $_$
SELECT
  CASE WHEN p.id_parent IS NULL THEN
    p.id::text::ltree
    ELSE
      get_calculated_page_node_path(p.id_parent) || p.id::text
    END
FROM page AS p
WHERE p.id = $1;
$_$;
 J   DROP FUNCTION public.get_calculated_page_node_path(param_page_id bigint);
       public       xu    false    5    586                    1255    28531    trig_update_page_node_path()    FUNCTION       CREATE FUNCTION trig_update_page_node_path() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF TG_OP = 'UPDATE' THEN
            IF (COALESCE(OLD.id_parent, 0) != COALESCE(NEW.id_parent, 0) OR NEW.id != OLD.id) THEN
                -- update all nodes that are children of this one including this one
                UPDATE page
                SET path = get_calculated_page_node_path(id)
                WHERE OLD.path @> page.path;
            END IF;

        ELSIF TG_OP = 'INSERT' THEN
            UPDATE page SET path = get_calculated_page_node_path(NEW.id) WHERE page.id = NEW.id;

            IF (COALESCE(NEW."name", '') = '') THEN
                UPDATE page SET "name" = urltranslit(NEW.title) WHERE page.id = NEW.id;
            END IF;
            IF (COALESCE(NEW."page_title", '') = '') THEN
                UPDATE page SET "page_title" = NEW.title WHERE page.id = NEW.id;
            END IF;
            IF (COALESCE(NEW."entity", '') = '') THEN
                UPDATE page SET "entity" = TG_TABLE_NAME WHERE page.id = NEW.id;
            END IF;

            IF (COALESCE(NEW."page_title", '') = '') THEN
                UPDATE page SET page_url = urltranslit(NEW.title) WHERE page.id = NEW.id;
            END IF;

        END IF;

        RETURN NEW;
    END
    $$;
 3   DROP FUNCTION public.trig_update_page_node_path();
       public       xu    false    5    678                    1255    28529    urltranslit(text)    FUNCTION     #  CREATE FUNCTION urltranslit(text) RETURNS text
    LANGUAGE sql
    AS $_$
SELECT
regexp_replace(
	replace(
		replace(
			replace(
				replace(
					replace(
						replace(
							replace(
                translate(
                  lower($1),
                  ' абвгдеёзийклмнопрстуфхыэъь',
                  '-abvgdeezijklmnoprstufhye'
                ), 'ж',	'zh'
							), 'ц',	'ts'
						), 'ч',	'ch'
					), 'ш',	'sh'
				), 'щ',	'sch'
			), 'ю', 'yu'
		), 'я',	'ya'
	)
	,
	'[^a-z0-9-]+',
	'',
	'g'
)
$_$;
 (   DROP FUNCTION public.urltranslit(text);
       public       xu    false    5         �            1259    28294    page    TABLE     :  CREATE TABLE page (
    id bigint NOT NULL,
    is_published boolean DEFAULT false,
    title character varying(255) NOT NULL,
    content text,
    page_url character varying(255),
    page_title character varying(255),
    page_description text,
    page_keywords text,
    "order" integer DEFAULT 0,
    name character varying(255),
    is_locked boolean DEFAULT false,
    id_parent bigint,
    path ltree,
    entity character varying(255),
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);
    DROP TABLE public.page;
       public         xu    false    2414    2415    2416    2417    2418    586    5         �            1259    28403    brand    TABLE     _   CREATE TABLE brand (
    id_parent bigint,
    image character varying(255)
)
INHERITS (page);
    DROP TABLE public.brand;
       public         xu    false    586    5    168         �            1259    28384    category    TABLE     b   CREATE TABLE category (
    id_parent bigint,
    image character varying(255)
)
INHERITS (page);
    DROP TABLE public.category;
       public         xu    false    168    5    586         �            1259    28340    color    TABLE     �   CREATE TABLE color (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    hex character varying(7) NOT NULL
);
    DROP TABLE public.color;
       public         xu    false    5         �            1259    28338    color_id_seq    SEQUENCE     n   CREATE SEQUENCE color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.color_id_seq;
       public       xu    false    171    5         	
           0    0    color_id_seq    SEQUENCE OWNED BY     /   ALTER SEQUENCE color_id_seq OWNED BY color.id;
            public       xu    false    170         �            1259    28366    country    TABLE     ]   CREATE TABLE country (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);
    DROP TABLE public.country;
       public         xu    false    5         �            1259    28364    country_id_seq    SEQUENCE     p   CREATE SEQUENCE country_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.country_id_seq;
       public       xu    false    177    5         

           0    0    country_id_seq    SEQUENCE OWNED BY     3   ALTER SEQUENCE country_id_seq OWNED BY country.id;
            public       xu    false    176         �            1259    28374    feedback    TABLE       CREATE TABLE feedback (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    content text,
    created_at timestamp(0) without time zone DEFAULT now()
);
    DROP TABLE public.feedback;
       public         xu    false    2431    5         �            1259    28372    feedback_id_seq    SEQUENCE     q   CREATE SEQUENCE feedback_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.feedback_id_seq;
       public       xu    false    179    5         
           0    0    feedback_id_seq    SEQUENCE OWNED BY     5   ALTER SEQUENCE feedback_id_seq OWNED BY feedback.id;
            public       xu    false    178         �            1259    28490    gallery    TABLE     !  CREATE TABLE gallery (
    id integer NOT NULL,
    is_published boolean DEFAULT false,
    title character varying(255) NOT NULL,
    image character varying(255),
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);
    DROP TABLE public.gallery;
       public         xu    false    2458    2459    2460    5         �            1259    28488    gallery_id_seq    SEQUENCE     p   CREATE SEQUENCE gallery_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.gallery_id_seq;
       public       xu    false    186    5         
           0    0    gallery_id_seq    SEQUENCE OWNED BY     3   ALTER SEQUENCE gallery_id_seq OWNED BY gallery.id;
            public       xu    false    185         �            1259    28504    gallerymain    TABLE     T  CREATE TABLE gallerymain (
    id integer NOT NULL,
    is_published boolean DEFAULT false,
    title character varying(255) NOT NULL,
    text text,
    image character varying(255),
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now(),
    url character varying(255)
);
    DROP TABLE public.gallerymain;
       public         xu    false    2462    2463    2464    5         �            1259    28502    gallerymain_id_seq    SEQUENCE     t   CREATE SEQUENCE gallerymain_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.gallerymain_id_seq;
       public       xu    false    5    188         
           0    0    gallerymain_id_seq    SEQUENCE OWNED BY     ;   ALTER SEQUENCE gallerymain_id_seq OWNED BY gallerymain.id;
            public       xu    false    187         �            1259    28318    news    TABLE     �   CREATE TABLE news (
    id_parent bigint,
    preview text,
    published_at timestamp(0) without time zone DEFAULT now()
)
INHERITS (page);
    DROP TABLE public.news;
       public         xu    false    2425    5    586    168         �            1259    28292    page_id_seq    SEQUENCE     m   CREATE SEQUENCE page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.page_id_seq;
       public       xu    false    5    168         
           0    0    page_id_seq    SEQUENCE OWNED BY     -   ALTER SEQUENCE page_id_seq OWNED BY page.id;
            public       xu    false    167         �            1259    28278 	   page_text    TABLE     �  CREATE TABLE page_text (
    id integer NOT NULL,
    mark character varying(255) NOT NULL,
    "group" character varying(255) NOT NULL,
    group_title character varying(255) DEFAULT NULL::character varying,
    "position" character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    content text,
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);
    DROP TABLE public.page_text;
       public         xu    false    2410    2411    2412    5         �            1259    28276    page_text_id_seq    SEQUENCE     r   CREATE SEQUENCE page_text_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.page_text_id_seq;
       public       xu    false    5    166         
           0    0    page_text_id_seq    SEQUENCE OWNED BY     7   ALTER SEQUENCE page_text_id_seq OWNED BY page_text.id;
            public       xu    false    165         �            1259    28358    pattern    TABLE     ]   CREATE TABLE pattern (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);
    DROP TABLE public.pattern;
       public         xu    false    5         �            1259    28356    pattern_id_seq    SEQUENCE     p   CREATE SEQUENCE pattern_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.pattern_id_seq;
       public       xu    false    175    5         
           0    0    pattern_id_seq    SEQUENCE OWNED BY     3   ALTER SEQUENCE pattern_id_seq OWNED BY pattern.id;
            public       xu    false    174         �            1259    28422    product    TABLE     �  CREATE TABLE product (
    id_parent bigint,
    article character varying(255) NOT NULL,
    cost numeric(10,2),
    image character varying(255),
    id_surface integer NOT NULL,
    id_pattern integer,
    id_country integer NOT NULL,
    is_action boolean DEFAULT false,
    is_new boolean DEFAULT false,
    is_hit boolean DEFAULT false,
    width numeric(10,1),
    height numeric(10,1),
    depth numeric(10,1)
)
INHERITS (page);
    DROP TABLE public.product;
       public         xu    false    2450    2451    2452    5    586    168         �            1259    28461    product_color    TABLE     �  CREATE TABLE product_color (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    image character varying(255),
    id_color integer NOT NULL,
    id_product integer NOT NULL,
    content text,
    id_surface integer NOT NULL,
    cost numeric(10,2),
    is_published boolean DEFAULT false,
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);
 !   DROP TABLE public.product_color;
       public         xu    false    2454    2455    2456    5         �            1259    28459    product_color_id_seq    SEQUENCE     v   CREATE SEQUENCE product_color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 +   DROP SEQUENCE public.product_color_id_seq;
       public       xu    false    184    5         
           0    0    product_color_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE product_color_id_seq OWNED BY product_color.id;
            public       xu    false    183         �            1259    28252    role    TABLE     P   CREATE TABLE role (
    id integer NOT NULL,
    name character varying(255)
);
    DROP TABLE public.role;
       public         xu    false    5         �            1259    28250    role_id_seq    SEQUENCE     m   CREATE SEQUENCE role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.role_id_seq;
       public       xu    false    5    162         
           0    0    role_id_seq    SEQUENCE OWNED BY     -   ALTER SEQUENCE role_id_seq OWNED BY role.id;
            public       xu    false    161         �            1259    28518    settings    TABLE     �   CREATE TABLE settings (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    name character varying(255),
    value character varying(511)
);
    DROP TABLE public.settings;
       public         xu    false    5         �            1259    28516    settings_id_seq    SEQUENCE     q   CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.settings_id_seq;
       public       xu    false    190    5         
           0    0    settings_id_seq    SEQUENCE OWNED BY     5   ALTER SEQUENCE settings_id_seq OWNED BY settings.id;
            public       xu    false    189         �            1259    28350    surface    TABLE     ]   CREATE TABLE surface (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);
    DROP TABLE public.surface;
       public         xu    false    5         �            1259    28348    surface_id_seq    SEQUENCE     p   CREATE SEQUENCE surface_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.surface_id_seq;
       public       xu    false    173    5         
           0    0    surface_id_seq    SEQUENCE OWNED BY     3   ALTER SEQUENCE surface_id_seq OWNED BY surface.id;
            public       xu    false    172         �            1259    28260    users    TABLE     �   CREATE TABLE users (
    id bigint NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(63) NOT NULL,
    email character varying(511),
    id_role integer
);
    DROP TABLE public.users;
       public         xu    false    5         �            1259    28258    users_id_seq    SEQUENCE     n   CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public       xu    false    164    5         
           0    0    users_id_seq    SEQUENCE OWNED BY     /   ALTER SEQUENCE users_id_seq OWNED BY users.id;
            public       xu    false    163         �	           2604    28406    id    DEFAULT     U   ALTER TABLE ONLY brand ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);
 7   ALTER TABLE public.brand ALTER COLUMN id DROP DEFAULT;
       public       xu    false    181    181    167         �	           2604    28407    is_published    DEFAULT     D   ALTER TABLE ONLY brand ALTER COLUMN is_published SET DEFAULT false;
 A   ALTER TABLE public.brand ALTER COLUMN is_published DROP DEFAULT;
       public       xu    false    181    181         �	           2604    28408    order    DEFAULT     ;   ALTER TABLE ONLY brand ALTER COLUMN "order" SET DEFAULT 0;
 <   ALTER TABLE public.brand ALTER COLUMN "order" DROP DEFAULT;
       public       xu    false    181    181         �	           2604    28409 	   is_locked    DEFAULT     A   ALTER TABLE ONLY brand ALTER COLUMN is_locked SET DEFAULT false;
 >   ALTER TABLE public.brand ALTER COLUMN is_locked DROP DEFAULT;
       public       xu    false    181    181         �	           2604    28410 
   created_at    DEFAULT     B   ALTER TABLE ONLY brand ALTER COLUMN created_at SET DEFAULT now();
 ?   ALTER TABLE public.brand ALTER COLUMN created_at DROP DEFAULT;
       public       xu    false    181    181         �	           2604    28411 
   updated_at    DEFAULT     B   ALTER TABLE ONLY brand ALTER COLUMN updated_at SET DEFAULT now();
 ?   ALTER TABLE public.brand ALTER COLUMN updated_at DROP DEFAULT;
       public       xu    false    181    181         �	           2604    28387    id    DEFAULT     X   ALTER TABLE ONLY category ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);
 :   ALTER TABLE public.category ALTER COLUMN id DROP DEFAULT;
       public       xu    false    180    167    180         �	           2604    28388    is_published    DEFAULT     G   ALTER TABLE ONLY category ALTER COLUMN is_published SET DEFAULT false;
 D   ALTER TABLE public.category ALTER COLUMN is_published DROP DEFAULT;
       public       xu    false    180    180         �	           2604    28389    order    DEFAULT     >   ALTER TABLE ONLY category ALTER COLUMN "order" SET DEFAULT 0;
 ?   ALTER TABLE public.category ALTER COLUMN "order" DROP DEFAULT;
       public       xu    false    180    180         �	           2604    28390 	   is_locked    DEFAULT     D   ALTER TABLE ONLY category ALTER COLUMN is_locked SET DEFAULT false;
 A   ALTER TABLE public.category ALTER COLUMN is_locked DROP DEFAULT;
       public       xu    false    180    180         �	           2604    28391 
   created_at    DEFAULT     E   ALTER TABLE ONLY category ALTER COLUMN created_at SET DEFAULT now();
 B   ALTER TABLE public.category ALTER COLUMN created_at DROP DEFAULT;
       public       xu    false    180    180         �	           2604    28392 
   updated_at    DEFAULT     E   ALTER TABLE ONLY category ALTER COLUMN updated_at SET DEFAULT now();
 B   ALTER TABLE public.category ALTER COLUMN updated_at DROP DEFAULT;
       public       xu    false    180    180         z	           2604    28343    id    DEFAULT     V   ALTER TABLE ONLY color ALTER COLUMN id SET DEFAULT nextval('color_id_seq'::regclass);
 7   ALTER TABLE public.color ALTER COLUMN id DROP DEFAULT;
       public       xu    false    170    171    171         }	           2604    28369    id    DEFAULT     Z   ALTER TABLE ONLY country ALTER COLUMN id SET DEFAULT nextval('country_id_seq'::regclass);
 9   ALTER TABLE public.country ALTER COLUMN id DROP DEFAULT;
       public       xu    false    176    177    177         ~	           2604    28377    id    DEFAULT     \   ALTER TABLE ONLY feedback ALTER COLUMN id SET DEFAULT nextval('feedback_id_seq'::regclass);
 :   ALTER TABLE public.feedback ALTER COLUMN id DROP DEFAULT;
       public       xu    false    179    178    179         �	           2604    28493    id    DEFAULT     Z   ALTER TABLE ONLY gallery ALTER COLUMN id SET DEFAULT nextval('gallery_id_seq'::regclass);
 9   ALTER TABLE public.gallery ALTER COLUMN id DROP DEFAULT;
       public       xu    false    186    185    186         �	           2604    28507    id    DEFAULT     b   ALTER TABLE ONLY gallerymain ALTER COLUMN id SET DEFAULT nextval('gallerymain_id_seq'::regclass);
 =   ALTER TABLE public.gallerymain ALTER COLUMN id DROP DEFAULT;
       public       xu    false    187    188    188         s	           2604    28321    id    DEFAULT     T   ALTER TABLE ONLY news ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);
 6   ALTER TABLE public.news ALTER COLUMN id DROP DEFAULT;
       public       xu    false    169    169    167         t	           2604    28322    is_published    DEFAULT     C   ALTER TABLE ONLY news ALTER COLUMN is_published SET DEFAULT false;
 @   ALTER TABLE public.news ALTER COLUMN is_published DROP DEFAULT;
       public       xu    false    169    169         u	           2604    28323    order    DEFAULT     :   ALTER TABLE ONLY news ALTER COLUMN "order" SET DEFAULT 0;
 ;   ALTER TABLE public.news ALTER COLUMN "order" DROP DEFAULT;
       public       xu    false    169    169         v	           2604    28324 	   is_locked    DEFAULT     @   ALTER TABLE ONLY news ALTER COLUMN is_locked SET DEFAULT false;
 =   ALTER TABLE public.news ALTER COLUMN is_locked DROP DEFAULT;
       public       xu    false    169    169         w	           2604    28325 
   created_at    DEFAULT     A   ALTER TABLE ONLY news ALTER COLUMN created_at SET DEFAULT now();
 >   ALTER TABLE public.news ALTER COLUMN created_at DROP DEFAULT;
       public       xu    false    169    169         x	           2604    28326 
   updated_at    DEFAULT     A   ALTER TABLE ONLY news ALTER COLUMN updated_at SET DEFAULT now();
 >   ALTER TABLE public.news ALTER COLUMN updated_at DROP DEFAULT;
       public       xu    false    169    169         m	           2604    28297    id    DEFAULT     T   ALTER TABLE ONLY page ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);
 6   ALTER TABLE public.page ALTER COLUMN id DROP DEFAULT;
       public       xu    false    168    167    168         i	           2604    28281    id    DEFAULT     ^   ALTER TABLE ONLY page_text ALTER COLUMN id SET DEFAULT nextval('page_text_id_seq'::regclass);
 ;   ALTER TABLE public.page_text ALTER COLUMN id DROP DEFAULT;
       public       xu    false    166    165    166         |	           2604    28361    id    DEFAULT     Z   ALTER TABLE ONLY pattern ALTER COLUMN id SET DEFAULT nextval('pattern_id_seq'::regclass);
 9   ALTER TABLE public.pattern ALTER COLUMN id DROP DEFAULT;
       public       xu    false    175    174    175         �	           2604    28425    id    DEFAULT     W   ALTER TABLE ONLY product ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);
 9   ALTER TABLE public.product ALTER COLUMN id DROP DEFAULT;
       public       xu    false    182    167    182         �	           2604    28426    is_published    DEFAULT     F   ALTER TABLE ONLY product ALTER COLUMN is_published SET DEFAULT false;
 C   ALTER TABLE public.product ALTER COLUMN is_published DROP DEFAULT;
       public       xu    false    182    182         �	           2604    28427    order    DEFAULT     =   ALTER TABLE ONLY product ALTER COLUMN "order" SET DEFAULT 0;
 >   ALTER TABLE public.product ALTER COLUMN "order" DROP DEFAULT;
       public       xu    false    182    182         �	           2604    28428 	   is_locked    DEFAULT     C   ALTER TABLE ONLY product ALTER COLUMN is_locked SET DEFAULT false;
 @   ALTER TABLE public.product ALTER COLUMN is_locked DROP DEFAULT;
       public       xu    false    182    182         �	           2604    28429 
   created_at    DEFAULT     D   ALTER TABLE ONLY product ALTER COLUMN created_at SET DEFAULT now();
 A   ALTER TABLE public.product ALTER COLUMN created_at DROP DEFAULT;
       public       xu    false    182    182         �	           2604    28430 
   updated_at    DEFAULT     D   ALTER TABLE ONLY product ALTER COLUMN updated_at SET DEFAULT now();
 A   ALTER TABLE public.product ALTER COLUMN updated_at DROP DEFAULT;
       public       xu    false    182    182         �	           2604    28464    id    DEFAULT     f   ALTER TABLE ONLY product_color ALTER COLUMN id SET DEFAULT nextval('product_color_id_seq'::regclass);
 ?   ALTER TABLE public.product_color ALTER COLUMN id DROP DEFAULT;
       public       xu    false    183    184    184         g	           2604    28255    id    DEFAULT     T   ALTER TABLE ONLY role ALTER COLUMN id SET DEFAULT nextval('role_id_seq'::regclass);
 6   ALTER TABLE public.role ALTER COLUMN id DROP DEFAULT;
       public       xu    false    161    162    162         �	           2604    28521    id    DEFAULT     \   ALTER TABLE ONLY settings ALTER COLUMN id SET DEFAULT nextval('settings_id_seq'::regclass);
 :   ALTER TABLE public.settings ALTER COLUMN id DROP DEFAULT;
       public       xu    false    190    189    190         {	           2604    28353    id    DEFAULT     Z   ALTER TABLE ONLY surface ALTER COLUMN id SET DEFAULT nextval('surface_id_seq'::regclass);
 9   ALTER TABLE public.surface ALTER COLUMN id DROP DEFAULT;
       public       xu    false    173    172    173         h	           2604    28263    id    DEFAULT     V   ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public       xu    false    163    164    164         �	          0    28403    brand 
   TABLE DATA               �   COPY brand (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, image) FROM stdin;
    public       xu    false    181    2560       2550.dat �	          0    28384    category 
   TABLE DATA               �   COPY category (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, image) FROM stdin;
    public       xu    false    180    2560       2549.dat �	          0    28340    color 
   TABLE DATA               (   COPY color (id, title, hex) FROM stdin;
    public       xu    false    171    2560       2540.dat 
           0    0    color_id_seq    SEQUENCE SET     3   SELECT pg_catalog.setval('color_id_seq', 8, true);
            public       xu    false    170         �	          0    28366    country 
   TABLE DATA               %   COPY country (id, title) FROM stdin;
    public       xu    false    177    2560       2546.dat 
           0    0    country_id_seq    SEQUENCE SET     5   SELECT pg_catalog.setval('country_id_seq', 2, true);
            public       xu    false    176         �	          0    28374    feedback 
   TABLE DATA               H   COPY feedback (id, name, email, phone, content, created_at) FROM stdin;
    public       xu    false    179    2560       2548.dat 
           0    0    feedback_id_seq    SEQUENCE SET     7   SELECT pg_catalog.setval('feedback_id_seq', 19, true);
            public       xu    false    178         �	          0    28490    gallery 
   TABLE DATA               R   COPY gallery (id, is_published, title, image, created_at, updated_at) FROM stdin;
    public       xu    false    186    2560       2555.dat 
           0    0    gallery_id_seq    SEQUENCE SET     6   SELECT pg_catalog.setval('gallery_id_seq', 1, false);
            public       xu    false    185         �	          0    28504    gallerymain 
   TABLE DATA               a   COPY gallerymain (id, is_published, title, text, image, created_at, updated_at, url) FROM stdin;
    public       xu    false    188    2560       2557.dat 
           0    0    gallerymain_id_seq    SEQUENCE SET     9   SELECT pg_catalog.setval('gallerymain_id_seq', 5, true);
            public       xu    false    187         �	          0    28318    news 
   TABLE DATA               �   COPY news (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, preview, published_at) FROM stdin;
    public       xu    false    169    2560       2538.dat �	          0    28294    page 
   TABLE DATA               �   COPY page (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at) FROM stdin;
    public       xu    false    168    2560       2537.dat 
           0    0    page_id_seq    SEQUENCE SET     3   SELECT pg_catalog.setval('page_id_seq', 58, true);
            public       xu    false    167         �	          0    28278 	   page_text 
   TABLE DATA               p   COPY page_text (id, mark, "group", group_title, "position", title, content, created_at, updated_at) FROM stdin;
    public       xu    false    166    2560       2535.dat 
           0    0    page_text_id_seq    SEQUENCE SET     7   SELECT pg_catalog.setval('page_text_id_seq', 3, true);
            public       xu    false    165         �	          0    28358    pattern 
   TABLE DATA               %   COPY pattern (id, title) FROM stdin;
    public       xu    false    175    2560       2544.dat 
           0    0    pattern_id_seq    SEQUENCE SET     6   SELECT pg_catalog.setval('pattern_id_seq', 11, true);
            public       xu    false    174         �	          0    28422    product 
   TABLE DATA               )  COPY product (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, article, cost, image, id_surface, id_pattern, id_country, is_action, is_new, is_hit, width, height, depth) FROM stdin;
    public       xu    false    182    2560       2551.dat �	          0    28461    product_color 
   TABLE DATA               �   COPY product_color (id, title, image, id_color, id_product, content, id_surface, cost, is_published, created_at, updated_at) FROM stdin;
    public       xu    false    184    2560       2553.dat 
           0    0    product_color_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('product_color_id_seq', 30, true);
            public       xu    false    183         �	          0    28252    role 
   TABLE DATA               !   COPY role (id, name) FROM stdin;
    public       xu    false    162    2560       2531.dat 
           0    0    role_id_seq    SEQUENCE SET     2   SELECT pg_catalog.setval('role_id_seq', 3, true);
            public       xu    false    161         �	          0    28518    settings 
   TABLE DATA               3   COPY settings (id, title, name, value) FROM stdin;
    public       xu    false    190    2560       2559.dat  
           0    0    settings_id_seq    SEQUENCE SET     6   SELECT pg_catalog.setval('settings_id_seq', 5, true);
            public       xu    false    189         �	          0    28350    surface 
   TABLE DATA               %   COPY surface (id, title) FROM stdin;
    public       xu    false    173    2560       2542.dat !
           0    0    surface_id_seq    SEQUENCE SET     5   SELECT pg_catalog.setval('surface_id_seq', 4, true);
            public       xu    false    172         �	          0    28260    users 
   TABLE DATA               @   COPY users (id, username, password, email, id_role) FROM stdin;
    public       xu    false    164    2560       2533.dat "
           0    0    users_id_seq    SEQUENCE SET     3   SELECT pg_catalog.setval('users_id_seq', 1, true);
            public       xu    false    163         �	           2606    28416 
   brand_pkey 
   CONSTRAINT     G   ALTER TABLE ONLY brand
    ADD CONSTRAINT brand_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.brand DROP CONSTRAINT brand_pkey;
       public         xu    false    181    181    2561         �	           2606    28397    category_pkey 
   CONSTRAINT     M   ALTER TABLE ONLY category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.category DROP CONSTRAINT category_pkey;
       public         xu    false    180    180    2561         �	           2606    28347    color_hex_key 
   CONSTRAINT     F   ALTER TABLE ONLY color
    ADD CONSTRAINT color_hex_key UNIQUE (hex);
 =   ALTER TABLE ONLY public.color DROP CONSTRAINT color_hex_key;
       public         xu    false    171    171    2561         �	           2606    28345 
   color_pkey 
   CONSTRAINT     G   ALTER TABLE ONLY color
    ADD CONSTRAINT color_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.color DROP CONSTRAINT color_pkey;
       public         xu    false    171    171    2561         �	           2606    28371    country_pkey 
   CONSTRAINT     K   ALTER TABLE ONLY country
    ADD CONSTRAINT country_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.country DROP CONSTRAINT country_pkey;
       public         xu    false    177    177    2561         �	           2606    28383    feedback_pkey 
   CONSTRAINT     M   ALTER TABLE ONLY feedback
    ADD CONSTRAINT feedback_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.feedback DROP CONSTRAINT feedback_pkey;
       public         xu    false    179    179    2561         �	           2606    28501    gallery_pkey 
   CONSTRAINT     K   ALTER TABLE ONLY gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.gallery DROP CONSTRAINT gallery_pkey;
       public         xu    false    186    186    2561         �	           2606    28515    gallerymain_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY gallerymain
    ADD CONSTRAINT gallerymain_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.gallerymain DROP CONSTRAINT gallerymain_pkey;
       public         xu    false    188    188    2561         �	           2606    28332 	   news_pkey 
   CONSTRAINT     E   ALTER TABLE ONLY news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);
 8   ALTER TABLE ONLY public.news DROP CONSTRAINT news_pkey;
       public         xu    false    169    169    2561         �	           2606    28309    page_name_key 
   CONSTRAINT     F   ALTER TABLE ONLY page
    ADD CONSTRAINT page_name_key UNIQUE (name);
 <   ALTER TABLE ONLY public.page DROP CONSTRAINT page_name_key;
       public         xu    false    168    168    2561         �	           2606    28311    page_path_key 
   CONSTRAINT     F   ALTER TABLE ONLY page
    ADD CONSTRAINT page_path_key UNIQUE (path);
 <   ALTER TABLE ONLY public.page DROP CONSTRAINT page_path_key;
       public         xu    false    168    168    2561         �	           2606    28307 	   page_pkey 
   CONSTRAINT     E   ALTER TABLE ONLY page
    ADD CONSTRAINT page_pkey PRIMARY KEY (id);
 8   ALTER TABLE ONLY public.page DROP CONSTRAINT page_pkey;
       public         xu    false    168    168    2561         �	           2606    28291    page_text_mark_key 
   CONSTRAINT     P   ALTER TABLE ONLY page_text
    ADD CONSTRAINT page_text_mark_key UNIQUE (mark);
 F   ALTER TABLE ONLY public.page_text DROP CONSTRAINT page_text_mark_key;
       public         xu    false    166    166    2561         �	           2606    28289    page_text_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY page_text
    ADD CONSTRAINT page_text_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.page_text DROP CONSTRAINT page_text_pkey;
       public         xu    false    166    166    2561         �	           2606    28363    pattern_pkey 
   CONSTRAINT     K   ALTER TABLE ONLY pattern
    ADD CONSTRAINT pattern_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.pattern DROP CONSTRAINT pattern_pkey;
       public         xu    false    175    175    2561         �	           2606    28472    product_color_pkey 
   CONSTRAINT     W   ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_pkey;
       public         xu    false    184    184    2561         �	           2606    28438    product_pkey 
   CONSTRAINT     K   ALTER TABLE ONLY product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.product DROP CONSTRAINT product_pkey;
       public         xu    false    182    182    2561         �	           2606    28257 	   role_pkey 
   CONSTRAINT     E   ALTER TABLE ONLY role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);
 8   ALTER TABLE ONLY public.role DROP CONSTRAINT role_pkey;
       public         xu    false    162    162    2561         �	           2606    28528    settings_name_key 
   CONSTRAINT     N   ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_name_key UNIQUE (name);
 D   ALTER TABLE ONLY public.settings DROP CONSTRAINT settings_name_key;
       public         xu    false    190    190    2561         �	           2606    28526    settings_pkey 
   CONSTRAINT     M   ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.settings DROP CONSTRAINT settings_pkey;
       public         xu    false    190    190    2561         �	           2606    28355    surface_pkey 
   CONSTRAINT     K   ALTER TABLE ONLY surface
    ADD CONSTRAINT surface_pkey PRIMARY KEY (id);
 >   ALTER TABLE ONLY public.surface DROP CONSTRAINT surface_pkey;
       public         xu    false    173    173    2561         �	           2606    28268 
   users_pkey 
   CONSTRAINT     G   ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public         xu    false    164    164    2561         �	           2606    28270    users_username_key 
   CONSTRAINT     P   ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public         xu    false    164    164    2561         �	           1259    28317    page_path_idx    INDEX     6   CREATE INDEX page_path_idx ON page USING gist (path);
 !   DROP INDEX public.page_path_idx;
       public         xu    false    1548    168    2561         �	           2620    28535    trig_update_brand_node_path    TRIGGER     �   CREATE TRIGGER trig_update_brand_node_path AFTER INSERT OR UPDATE OF id, id_parent ON brand FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();
 :   DROP TRIGGER trig_update_brand_node_path ON public.brand;
       public       xu    false    181    276    181    181    2561         �	           2620    28534    trig_update_category_node_path    TRIGGER     �   CREATE TRIGGER trig_update_category_node_path AFTER INSERT OR UPDATE OF id, id_parent ON category FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();
 @   DROP TRIGGER trig_update_category_node_path ON public.category;
       public       xu    false    180    180    180    276    2561         �	           2620    28533    trig_update_news_node_path    TRIGGER     �   CREATE TRIGGER trig_update_news_node_path AFTER INSERT OR UPDATE OF id, id_parent ON news FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();
 8   DROP TRIGGER trig_update_news_node_path ON public.news;
       public       xu    false    169    169    169    276    2561         �	           2620    28532    trig_update_page_node_path    TRIGGER     �   CREATE TRIGGER trig_update_page_node_path AFTER INSERT OR UPDATE OF id, id_parent ON page FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();
 8   DROP TRIGGER trig_update_page_node_path ON public.page;
       public       xu    false    276    168    168    168    2561         �	           2620    28536    trig_update_product_node_path    TRIGGER     �   CREATE TRIGGER trig_update_product_node_path AFTER INSERT OR UPDATE OF id, id_parent ON product FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();
 >   DROP TRIGGER trig_update_product_node_path ON public.product;
       public       xu    false    276    182    182    182    2561         �	           2606    28417    brand_id_parent_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY brand
    ADD CONSTRAINT brand_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES category(id) ON DELETE CASCADE;
 D   ALTER TABLE ONLY public.brand DROP CONSTRAINT brand_id_parent_fkey;
       public       xu    false    180    181    2497    2561         �	           2606    28398    category_id_parent_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY category
    ADD CONSTRAINT category_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES page(id) ON DELETE CASCADE;
 J   ALTER TABLE ONLY public.category DROP CONSTRAINT category_id_parent_fkey;
       public       xu    false    2481    168    180    2561         �	           2606    28333    news_id_parent_fkey    FK CONSTRAINT     |   ALTER TABLE ONLY news
    ADD CONSTRAINT news_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES page(id) ON DELETE CASCADE;
 B   ALTER TABLE ONLY public.news DROP CONSTRAINT news_id_parent_fkey;
       public       xu    false    2481    168    169    2561         �	           2606    28312    page_id_parent_fkey    FK CONSTRAINT     |   ALTER TABLE ONLY page
    ADD CONSTRAINT page_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES page(id) ON DELETE CASCADE;
 B   ALTER TABLE ONLY public.page DROP CONSTRAINT page_id_parent_fkey;
       public       xu    false    2481    168    168    2561         �	           2606    28473    product_color_id_color_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_id_color_fkey FOREIGN KEY (id_color) REFERENCES color(id) ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_id_color_fkey;
       public       xu    false    184    2487    171    2561         �	           2606    28478    product_color_id_product_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_id_product_fkey FOREIGN KEY (id_product) REFERENCES product(id) ON DELETE CASCADE;
 U   ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_id_product_fkey;
       public       xu    false    184    2501    182    2561         �	           2606    28483    product_color_id_surface_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_id_surface_fkey FOREIGN KEY (id_surface) REFERENCES surface(id) ON DELETE CASCADE;
 U   ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_id_surface_fkey;
       public       xu    false    173    184    2489    2561         �	           2606    28449    product_id_country_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_country_fkey FOREIGN KEY (id_country) REFERENCES country(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_country_fkey;
       public       xu    false    177    2493    182    2561         �	           2606    28454    product_id_parent_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES brand(id) ON DELETE CASCADE;
 H   ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_parent_fkey;
       public       xu    false    182    2499    181    2561         �	           2606    28444    product_id_pattern_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_pattern_fkey FOREIGN KEY (id_pattern) REFERENCES pattern(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_pattern_fkey;
       public       xu    false    2491    182    175    2561         �	           2606    28439    product_id_surface_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_surface_fkey FOREIGN KEY (id_surface) REFERENCES surface(id) ON DELETE CASCADE;
 I   ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_surface_fkey;
       public       xu    false    2489    182    173    2561         �	           2606    28271    users_id_role_fkey    FK CONSTRAINT     h   ALTER TABLE ONLY users
    ADD CONSTRAINT users_id_role_fkey FOREIGN KEY (id_role) REFERENCES role(id);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_id_role_fkey;
       public       xu    false    2466    164    162    2561                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              2550.dat                                                                                            100600  004000  002000  00000037002 12167500070 007110  0                                                                                                    ustar00                                                                                                                                                                                                                                                        32	t	Estima	<p>Компания Рекада больше 10 лет занимается продажей российской керамогранитной плитки. Одним из первых наших партнеров стала компания Кератон, выпускающая керамогранит под известной в России маркой Estima. Продукция Эстима сегодня представлена практически во всех магазинах строительных и отделочных материалов. И это неудивительно &mdash; плитка Estima сумела всем доказать возможность российской продукции конкурировать со знаменитыми европейскими марками.</p>\r\n\r\n<p>Estima производит керамогранитную плитку на основе самых передовых технологий. Материал обладает всеми свойствами продукции категории люкс: сверхпрочность, глубина окраски, многообразие цвета и фактуры. При этом стоимость этой плитки ниже любых европейских аналогов.<br />\r\nКератон выпускают все виды изделий из керамогранита. Сферы его использования различны: отделка интерьера и экстерьера зданий, напольная облицовка, строительство и вентилируемые фасады. Керамогранитная плитка торговой марки Estima достаточно широко применяется при возведении и реставрации зданий, отделке интерьеров и фасадов. Сегодня керамогранит Эстима можно часто увидеть на фасадах торговых и деловых центров, административных зданий, гостиниц и роскошных в своем убранстве станций метрополитена; материал широко применяется для облицовки стен на вокзалах, в ресторанах и, конечно же, в частных интерьерах.</p>\r\n\r\n<h2>Эстетика и практичность &mdash; идеальное сочетание</h2>\r\n\r\n<p>Значительное разнообразие дизайнерских изысков и расцветок материала позволят придать любому помещению или фасаду здания современный и элегантный вид. Керамогранитная плитка Estima сочетает в себе надежность, долговечность и приемлемую цену &mdash; будь то облицовочный материал для интерьера или керамогранит для фасадов. При этом эстетичность продукции была и остается для производителя на первом месте.</p>\r\n\r\n<p>Каждая коллекция может отличаться фактурой поверхности плитки: гладкая, &laquo;под дерево&raquo;, с характерными для натурального камня неровностями&hellip; Дизайнеры компании Estima всегда готовы удивлять российских покупателей не только богатством цветовой палитры, но и многочисленными техниками нанесения рисунка. В каждой коллекции представлены варианты, которые способны &laquo;раствориться&raquo; в общем колористическом решении интерьера, и концептуальные варианты, доминирующие в декоре.</p>\r\n\r\n<p>Продажа технической, фасадной и интерьерной керамогранитной плитки Estima осуществляется оптом и в розницу. Регулярное пополнение запасов, налаженная работа логистического центра, ответственное отношение к срокам доставки являются для наших партнеров серьезными доводами в пользу сотрудничества с нашей компанией. И если оптовикам важно быть уверенными в наличии нужного объема товара на складе, то для частных клиентов в сотрудничестве с &laquo;Рекадой&raquo; важнее богатейший выбор вариантов плитки из керамогранита.</p>\r\n	estima	Estima			10	estima	f	13	1.13.32	brand	2013-07-01 13:41:00	2013-07-01 13:41:00	
34	t	Kerama-Marazzi	<p>Завод керамогранита Kerama Marazzi является одним из крупнейших производителей керамогранита в России. В Ступино ежемесячно выпускается и реализуется не менее 500 тысяч кв.м. керамогранита. Коллекции керамогранита Марацци отражают все современные тренды в керамогранитной моде.<br />\r\nНапример, очень популярна на рынке коллекция керамогранита Керама Марацци Монблан, залогом успеха которой являются глубокие яркие цвета, создающие атмосферу дорогой выделки.<br />\r\nТакже обратите ваше внимание на новую французскую коллекию Керама Марацци, которая отличается необычной формой плит (400*800) и дизайном.</p>\r\n	kerama-marazzi	Kerama-Marazzi			10	kerama-marazzi	f	13	1.13.34	brand	2013-07-01 14:38:21	2013-07-01 14:38:21	
57	t	Рек		rek				70	rekomendatsii	f	13	1.13.57	brand	2013-07-09 16:46:22	2013-07-09 16:46:22	
58	t	Рек		rek				100	rekomendatsii	f	29	1.29.58	brand	2013-07-09 16:46:54	2013-07-09 16:46:54	
39	t	Гранит		granit	Гранит			0	rossiya	f	28	1.28.39	brand	2013-07-01 15:26:05	2013-07-01 15:26:05	
40	t	Мрамор		mramor	Мрамор			10	italiya	f	28	1.28.40	brand	2013-07-01 15:26:20	2013-07-01 15:26:20	
41	t	Травертин		travertin	Травертин			20	kitaj	f	28	1.28.41	brand	2013-07-01 15:27:13	2013-07-01 15:27:13	
49	t	Россия		rossiya	Россия			10	rossiya	f	31	1.31.49	brand	2013-07-01 15:46:04	2013-07-01 15:46:04	
43	t	Панно	<p>Одним из самых интересных вариантов украшения помещений сегодня считается использование декоративных изделий из натурального или искусственного камня. Компания Рекада представляет огромный выбор оригинальных панно из керамогранита, с помощью которых можно полностью изменить дизайн интерьера.<br />\r\nСтильные, необычные панно из керамогранита &mdash; это не просто элемент украшения, но и гарантированная долговечность покрытия. Ведь используемый при изготовлении панно керамогранит, являясь материалом искусственным, тем не менее, вобрал в себя все лучшие качества натурального камня, и в чем-то даже превзошел его. Износостойкость и прочность, экологичность и гигиеничность, абсолютная сопротивляемость внешним воздействиям, как атмосферным, так и температурным, делает панно из керамогранита идеальным материалом для украшения помещений при внутренней и внешней отделке.<br />\r\nИспользуя самое современное оборудование, имея в штате профессиональных опытных дизайнеров и мастеров, компания Рекада гарантирует, что каждый наш посетитель сможет выбрать панно по своему вкусу. Панно как нестандартное украшение помещений долгие годы будет радовать взоры, сохраняя свое очарование при любом стилевом решении интерьера.<br />\r\nТакже обратите внимание на столешницы из керамогранита, представленные в ассортименте в соответствующем разделе каталога.</p>\r\n	panno	Панно			30	panno	f	29	1.29.43	brand	2013-07-01 15:30:06	2013-07-01 15:30:06	
42	t	Ступени	<p>Как известно, керамогранит для лестниц &mdash; замечательный материал, позволяющий создавать функциональные и гармоничные архитектурные и интерьерные ансамбли.<br />\r\nЛестница (керамогранит) &mdash; прослужит верой и правдой не один год, благодаря прочности, отличным эксплуатационным характеристикам. Уникальные качества материала, а значит, и керамогранитных ступеней, обуславливают неограниченный срок службы этих изделий.<br />\r\nКерамогранитные ступени имеют физико-химические характеристики, оптимальные для эксплуатации любой интенсивности. Ступени из этого материала обладают высокой плотностью, не поглощают воду, имеют антиабразивную защиту. Эти свойства позволяют делать лестницы из керамогранита в местах, где эксплуатация усложнена такими факторами, как механические воздействия (многолюдные общественные места), влажный или холодный климат, резкие температурные перепады, прямой солнечный свет и многое другое. Ступени, сделанные из керамогранита, отличаются повышенной прочностью &mdash; их фактически невозможно расколоть.<br />\r\nА кроме того, ступени из керамогранита, а значит, и вся лестница, долго сохраняют свой первозданный эстетичный вид, не требуя при этом специальных процедур по уходу. А удобные насечки от скольжения, которые наносят на керамогранитные ступени, делают их удобными и безопасными.<br />\r\nЭлегантный и завершенный вид лестнице из керамогранита придадут специально выпускаемые для этого плинтуса, декоративные элементы и другие необходимые для окончательной отделки детали.<br />\r\nБогатая цветовая гамма, а также имитация природного рисунка гранита облегчают выбор типа поверхности и в некоторых случаях &mdash; формы ступеней, а значит и общего облика лестницы. Керамогранитные ступени делают полированными и матовыми, а их края могут быть скошенными или закругленными. Неслучайно идеально подобранный керамогранит для лестницы часто становится основным украшением интерьера.<br />\r\nУниверсальность применения и вариативность дизайнерских решений сделали керамогранитные изысканные лестницы невероятно популярными и в архитектурных проектах и в интерьерных дизайнерских разработках.<br />\r\nМы можем предложить большой выбор керамогранитных ступеней: составные, стандартные, сборные. Вся продукция, начиная со способа обработки материала и заканчивая дизайном готовых изделий, создается с помощью самых современных технологий.<br />\r\nЗаказанная в &laquo;Рекаде&raquo; лестница будет полностью соответствовать Вашим индивидуальным пожеланиям. Стоит отметить, что главным приоритетом в работе для нас всегда было и остается разумное соотношение цены и качества, поэтому каждый сможет заказать ступени для лестниц (керамогранит) по доступной для него стоимости.<br />\r\nТакже компания Рекада предлагает огромный выбор подоконников из керамогранита, которые вы можете найти в соответствующем разделе нашего каталога.</p>\r\n	stupeni	Ступени			0	stupeni	f	29	1.29.42	brand	2013-07-01 15:27:35	2013-07-01 15:27:35	
44	t	Из керамогранита		iz-keramogranita	Из керамогранита			0	iz-keramogranita	f	30	1.30.44	brand	2013-07-01 15:42:40	2013-07-01 15:42:40	
45	t	Стеклянная		steklyannaya	Стеклянная			10	steklyannaya	f	30	1.30.45	brand	2013-07-01 15:43:06	2013-07-01 15:43:06	
46	t	Смальта		smalta	Смальта			20	smalta	f	30	1.30.46	brand	2013-07-01 15:43:59	2013-07-01 15:43:59	
47	t	Из натурального камня		iz-naturalnogo-kamnya	Из натурального камня			30	iz-naturalnogo-kamnya	f	30	1.30.47	brand	2013-07-01 15:44:17	2013-07-01 15:44:17	
48	t	Италия		italiya	Италия			0	italiya	f	31	1.31.48	brand	2013-07-01 15:45:51	2013-07-01 15:45:51	
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              2549.dat                                                                                            100600  004000  002000  00000001660 12167500070 007121  0                                                                                                    ustar00                                                                                                                                                                                                                                                        28	t	Натуральный камень		naturalnyj-kamen	Натуральный камень			10	naturalnyj-kamen	f	1	1.28	category	2013-07-01 13:31:29	2013-07-01 13:31:29	
29	t	Изделия и услуги		izdeliya-i-uslugi	Изделия и услуги			20	izdeliya-i-uslugi	f	1	1.29	category	2013-07-01 13:32:15	2013-07-01 13:32:15	
30	t	Мозаика		mozaika	Мозаика			30	mozaika	f	1	1.30	category	2013-07-01 13:32:29	2013-07-01 13:32:29	
31	t	Плитка		plitka	Плитка			40	plitka	f	1	1.31	category	2013-07-01 13:32:41	2013-07-01 13:32:41	
13	t	Керамогранит	<h2>Популярные категории:</h2>\r\n\r\n<p><a href="http://kapitanov.fvds.ru/articles/keramogranit3030">30*30</a></p>\r\n\r\n<p><a href="http://kapitanov.fvds.ru/articles/keramogranit-6060">60*60</a></p>\r\n	keramogranit	Керамогранит			0	keramogranit	f	1	1.13	category	2013-07-01 11:36:55	2013-07-01 11:36:55	
\.


                                                                                2540.dat                                                                                            100600  004000  002000  00000000305 12167500070 007103  0                                                                                                    ustar00                                                                                                                                                                                                                                                        2	бежевые	#d2a37d
3	коричневые	#3a1c09
5	чёрные	#000003
7	зелёные	#13830c
6	синие	#0037dd
4	серые	#727678
1	белые	#dfdccc
8	красные	#c90000
\.


                                                                                                                                                                                                                                                                                                                           2546.dat                                                                                            100600  004000  002000  00000000043 12167500070 007110  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	Россия
2	Италия
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             2548.dat                                                                                            100600  004000  002000  00000003735 12167500070 007125  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fghfghfghfgh	2013-07-01 11:45:27
2	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fghfghfghfgh	2013-07-01 11:45:50
3	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fghfghfghfgh	2013-07-01 11:47:25
4	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fdgdfgdfgdfg	2013-07-01 11:48:05
5	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fdgdfgdfgdfg	2013-07-01 11:48:12
6	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fdgdfgdfgdfg	2013-07-01 11:50:48
7	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	fdgdfgdfgdfg	2013-07-01 11:52:22
8	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	dfgdfgdfgdfgdfgdfg	2013-07-01 12:04:28
9	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	dfgdfgdfgdfgdfgdfg	2013-07-01 12:07:46
10	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	dfgdfgdfgdfgdfgdfg	2013-07-01 12:09:21
11	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	dfgdfgdfgdfgdfgdfg	2013-07-01 12:10:35
12	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	dfgdfgdfgdfgdfgdfg	2013-07-01 12:11:10
13	Дмитрий Сергеевич Гроза	boxfrommars@gmail.com	+7 (961) 537 34 07	dfgdfgdfgdfgdfgdfg	2013-07-01 12:13:52
14	dhjk	hi.penki@gmail.com			2013-07-01 16:57:57
15		hi.penki@gmail.com			2013-07-01 16:59:08
16		hi.penki@gmail.com	915-344-44-95		2013-07-01 17:05:00
17	lu	boxfrommars@gmail.com	+7961	sfsdfsdfsdf sdf sdf sdf 	2013-07-01 21:46:39
18	lu	boxfrommars@gmail.com	+7961	sef ef wer we wer we r	2013-07-01 21:47:25
19	Дмитрий	boxfrommars@gmail.com	+7 (961) 537-3407	тест	2013-07-01 22:07:48
\.


                                   2555.dat                                                                                            100600  004000  002000  00000000005 12167500070 007106  0                                                                                                    ustar00                                                                                                                                                                                                                                                        \.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           2557.dat                                                                                            100600  004000  002000  00000002132 12167500070 007113  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	t	Керамогранит	<p>Оптовые поставки материала из наличия-</p>\r\n<p>Подбор вариантов для Вашего объекта-</p>\r\n<p>Индивидуальные скидки для подрядчиков-</p>\r\n<p id="keramo_nal"><a href="#">Узнать наличие&nbsp;&gt;&gt;</a></p>	keramogr (1).jpg	2013-05-17 06:48:16	2013-05-17 06:48:16	/keramogranit
2	t	Натуральный камень	<p>Оптовые поставки материала из наличия-</p>\r\n<p>Подбор вариантов для Вашего объекта-</p>\r\n<p>Индивидуальные скидки для подрядчиков-</p>\r\n<p id="keramo_nal"><a href="#">Узнать наличие&nbsp;&gt;&gt;</a></p>	naturkam (1).jpg	2013-05-17 07:17:31	2013-05-17 07:17:31	/naturalnyj-kamen
3	t	Изделия и услуги		izdelia (4).jpg	2013-07-01 16:01:33	2013-07-01 16:01:33	
4	t	Мозаика		mosaik (1).jpg	2013-07-01 16:02:16	2013-07-01 16:02:16	
5	t	Плитка		blueslide (1).jpg	2013-07-01 16:02:50	2013-07-01 16:02:50	
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                      2538.dat                                                                                            100600  004000  002000  00000003750 12167500070 007121  0                                                                                                    ustar00                                                                                                                                                                                                                                                        25	t	Свежая акция!	<p>Акция на полированный керамогранит в ноябре! Feeling offended by Gregor's delayed response in opening the door, the clerk warns him of the consequences of missing work. He adds that his recent performance has been unsatisfactory. Gregor disagrees and tells him that he will open the door shortly. Nobody on the other side of the door could understand a single word he uttered (Gregor was unaware of the fact that his voice has also transformed) and conclude that he is seriously ill. Finally, Gregor manages to unlock and open the door with his mouth. He apologizes to the office manager for the delay. Horrified by the sight of Gregor's appearance, the manager bolts out of the apartment, while Gregor's mother faints. Gregor tries to catch up with him but his father drives him back into the bedroom with a cane and a rolled newspaper. Gregor injures himself squeezing back through the doorway, and his father slams the door shut. Gregor, exhausted, falls asleep.</p>	svezhaya-aktsiya	Свежая акция!	\N	\N	0	svezhaya-aktsiya	f	9	1.9.25	news	2013-07-01 11:36:55	2013-07-01 11:36:55	Акция на полированный керамогранит в ноябре	2012-11-06 00:00:00
26	t	Расширение линейки	Расширение линейки полированного керамогранита 	rasshirenie-linejki	Расширение линейки	\N	\N	0	rasshirenie-linejki	f	9	1.9.26	news	2013-07-01 11:36:55	2013-07-01 11:36:55	Расширение линейки полированного керамогранита	2012-10-31 00:00:00
27	t	Спешите!	Специальная цена до 1 ноября - белый полированный керамогранит!	speshite	Спешите!	\N	\N	0	speshite	f	9	1.9.27	news	2013-07-01 11:36:55	2013-07-01 11:36:55	Специальная цена до 1 ноября - белый полированный керамогранит!	2012-10-15 00:00:00
\.


                        2537.dat                                                                                            100600  004000  002000  00000020100 12167500070 007104  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	t	Главная			Главная	\N	\N	0	main	t	\N	1	page	2013-07-01 11:36:55	2013-07-01 11:36:55
2	t	О компании		about	О компании	\N	\N	0	about	f	1	1.2	page	2013-07-01 11:36:55	2013-07-01 11:36:55
3	t	Контакты	<p><strong>Как добраться пешком от метро:</strong>&nbsp;Станция м. Нагатинская (первый вагон из центра).</p><p>От метро двигайтесь вдоль Варшавского шоссе по ходу движения транспорта 7-8 минут средним темпом (или можно проехать одну остановку на любом транспорте и далее идти вперед еще 1-2 минуты). 6-этажное здание бизнес-центра, расположенно вдоль шоссе (вдоль здания стоят высокие ели, на углу есть адресный указатель - &quot;Варшавское шоссе, д. 42&quot;).</p><p>Вход находится по центру фасада со стороны шоссе (круглые вращающиеся двери). Посетителям необходимо спуститься направо, вниз на цокольный этаж к гостевому ресепшн, назвать свою фамилию и сказать, что пришли в компанию &quot;Рекада&quot;. На лифте подняться на 5-й этаж, из лифтового холла повернуть налево, на двери офиса находится вывеска &quot;Рекада-Центр&quot;.</p>	contacts	Контакты	\N	\N	0	contacts	t	1	1.3	page	2013-07-01 11:36:55	2013-07-01 11:36:55
7	t	Керамогранит 40*40	<p>Керамогранит 400*400 - это &quot;новый&quot; керамогранит 300*300, т.е. является вариацией на тему &quot;тридцатки&quot;. Он является более современным материалом, его популярность увеличивается. Дело в том, что более крупный формат даёт ощущение внушительности и визуально расширяет пространство. При этом также недорог, удобен и практичен. Его специализация аналогична керамограниту 30х30.</p>\r\n	keramogranit4040				30	articles2	f	4	1.4.7	page	2013-07-01 11:36:55	2013-07-01 11:36:55
4	t	Рекомендации		articles	Рекомендации			0	articles	t	1	1.4	page	2013-07-01 11:36:55	2013-07-01 11:36:55
8	t	Галерея		gallery	Галерея	\N	\N	0	gallery	t	1	1.8	page	2013-07-01 11:36:55	2013-07-01 11:36:55
9	t	Новости		news	Новости	\N	\N	0	news	t	1	1.9	page	2013-07-01 11:36:55	2013-07-01 11:36:55
11	t	Карта сайта		map	Карта сайта	\N	\N	0	map	t	1	1.11	page	2013-07-01 11:36:55	2013-07-01 11:36:55
12	t	Поиск		search	Поиск	\N	\N	0	search	t	1	1.12	page	2013-07-01 11:36:55	2013-07-01 11:36:55
10	t	Заявка	<p>Спасибо за заявку.&nbsp; Наши менеджеры свяжутся с вами в течение 1 рабочего дня.</p>\r\n	feedback	Оставить заявку			0	feedback	t	1	1.10	page	2013-07-01 11:36:55	2013-07-01 11:36:55
55	t	Керамогранит 60*60	<p>Керамогранит 600*600 - это формат, который своим появлением образовал новую масштабную рыночную нишу. Причина в том, что одновременно с началом производства керамического гранита на рынке строительных материалов появилась и технология навесных вентилируемых фасадов. Вентфасады ныне являются основным способом отделки наружных поверхностей зданий. А большеформатная &quot;всепогодная&quot; керамогранитная плита 60х60 сразу же стала основным облицовочным фасадным материалом для них. Кроме того, плитка 600х600 с появлением гигантских торговых (форматы: гипер, молл, cash&amp;carry) или многоцелевых центров стала очень востребована для внутренних пространств. Итак, специализацией большеформатных плит является его использование как фасадного керамогранита и для отделки больших пространств.</p>\r\n	keramogranit-6060	Керамогранит 60*60			40	keramogranit-6060	f	4	1.4.55	page	2013-07-09 16:21:43	2013-07-09 16:21:43
5	t	Керамогранит 30*30	<p>Керамогранит 30*30&nbsp;- самый популярный размер керамогранитных плит. Такой формат является как бы праотцом для всех остальных, т.к. именно на небольших плитках была обкатана вся технология производства gres porcellanato. В процессе развития технологии появились более крупные форматы со своей специализацией. Специализацией для формата плитки 30х30 является использование в частных итерьерах, как керамогранит для лестничных маршей, керамогранит для подсобных помещений, в помещениях общего пользования (туалетные комнаты, коридоры, лифтовые холлы, входные группы), в помещениях любого назначения с небольшой площадью внутреннего пространства. Также отличительным качеством керамогранитных плиток 30*30 является их относительная невысокая стоимость.</p>\r\n	keramogranit3030				20	articles1	f	4	1.4.5	page	2013-07-01 11:36:55	2013-07-01 11:36:55
56	t	Керамогранит под дерево	<p>Практичность камня и уют натурального материала способен совместить в себе лишь керамогранит под дерево. Многообразие природных фактур древесных пород в своих коллекциях стремятся передать все известные производители керамогранита. Сегодня качественный уровень создания керамогранита достиг того уровня, когда потребитель получает практически полную имитацию текстуры и цвета различных пород древесины.<br />\r\nКомания &laquo;Рекада&raquo; предоставляет широкий выбор керамогранита, имитирующего дерево. Этот вид отделочного материала уже с момента своего появления стал ключевым элементом дизайнерских решений для частных и общественных интерьеров. Плитку &laquo;под дерево&raquo; часто используют в системе теплых полов, что позволяет этому материалу создать в доме атмосферу, полную домашнего уюта и покоя.</p>\r\n	keramogranit-pod-derevo	Керамогранит под дерево			10	keramogranit-pod-derevo	f	4	1.4.56	page	2013-07-09 16:28:01	2013-07-09 16:28:01
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                2535.dat                                                                                            100600  004000  002000  00000014025 12167500070 007113  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	first	main	Главная	Почему выбирают нас / первый блок	-	<h2>Комплекс: керамогранит, плитка, камень, мозаика</h2>\r\n\r\n<p>Это очень удобно &ndash; приобрести облицовочные материалы у единого специализированного поставщика. Наши профессионалы готовы быстро рассчитать объёмы &nbsp;и стоимость керамогранита, плитки, камня и мозаики &laquo;под ключ&raquo;, как для частного покупателя, так и для корпоративного заказчика.</p>\r\n\r\n<h2>Минимальный срок поставки</h2>\r\n\r\n<p>Рекада-Центр предлагает товары исходя из наличия, поэтому срок поставки заказа редко превышает один день после оплаты. Если товар является уникальным (изделия, панно, миксы, спецобработка), или имеет специфические параметры по размерам, объёму, цветовому решению, то срок также остаётся минимальным &ndash; не более 14 дней.</p>\r\n\r\n<h2>Профессиональная консультация</h2>\r\n\r\n<p>Профессионализм наших менеджеров является гордостью нашей компании. Быстрое понимание задачи, компетентное обсуждение &nbsp;и скорый отклик с коммерческим предложением &nbsp;экономит массу времени заказчика, а значит и его деньги. Вам будет предложено лучшее решение по разумной цене.&nbsp;</p>	2013-04-23 04:22:44	2013-04-23 04:22:44
3	third	main	Главная	Почему выбирают нас / третий блок	-	<h2>Выгодные цены для оптовых покупателей</h2>\r\n\r\n<p>Многолетнее партнёрство и утверждённые в контрактах условия сотрудничества с производителями позволяют нам предлагать керамогранит по оптовым ценам или по отпускным ценам завода. При крупных заказах плитки, камня, мозаики &ndash; цены обсуждаются индивидуально.</p>\r\n\r\n<h2>Доступность образцов</h2>\r\n\r\n<p>В офисе компании всегда есть возможность посмотреть образцы керамогранита, плитки, камня или мозаики. Очень часто, для экономии времени, мы сначала отправляем заказчикам фото образцов по e-mail. После предварительного согласования, в случае крупного заказа, образец &nbsp;доставляется на объект для окончательного утверждения.</p>\r\n\r\n<h2>12 лет опыта и успеха</h2>\r\n\r\n<p>Надёжность поставщика &ndash; важный пункт при реализации проекта для любого заказчика. Налаженная и профессиональная работа всех звеньев: &nbsp;менеджера по продажам, инженера, логиста, администрации и бухгалтерии &ndash; причина успеха нашей компании. Именно поэтому наши клиенты обращаются к нам снова и смело рекомендуют своим друзьям и партнёрам.&nbsp;</p>\r\n\r\n<p>&nbsp;</p>	2013-04-23 04:22:44	2013-04-23 04:22:44
2	second	main	Главная	Почему выбирают нас / второй блок	-	<h2>Резка, обработка и изготовление изделий</h2>\r\n\r\n<p>Рекада предлагает любые виды работ по обработке облицовочных плит (ступени, плинтуса, столешницы, подоконники, панно, мозаика, резка), для этого используется оборудование с &nbsp;технологией Waterjet. Расчёт стоимости работ или самих изделий осуществляется на основании ТЗ заказчика.</p>\r\n\r\n<h2>Инженерная поддержка</h2>\r\n\r\n<p>В составе компании работают специалисты, которые осуществляют инженерные расчёты, готовят чертежи и рассчитывают итоговую стоимость, используя профессиональные&nbsp;программы. Это особенно важно при изготовлении сложных изделий из камня, керамогранита и агломератов, а также при&nbsp;расчёте стоимости вентфасадов.</p>\r\n\r\n<h2>Доставка в любую точку России</h2>\r\n\r\n<p>Наша компания ежедневно отгружает значительные объёмы керамогранита, плитки или камня. В большинстве случаев заказчик поручает нашей компании доставить товар на объект: строительные площадки, коттеджи или апартаменты. Стоимость доставки обсуждается индивидуально, зависит от расстояния и объёма.</p>	2013-04-23 04:22:44	2013-04-23 04:22:44
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           2544.dat                                                                                            100600  004000  002000  00000000252 12167500070 007110  0                                                                                                    ustar00                                                                                                                                                                                                                                                        3	Мрамор
4	Гранит
5	Старый камень
6	Травертин
7	Соль - перец
8	Кожа
9	Ткань
10	Дерево
11	Моноколор
\.


                                                                                                                                                                                                                                                                                                                                                      2551.dat                                                                                            100600  004000  002000  00000012043 12167500070 007107  0                                                                                                    ustar00                                                                                                                                                                                                                                                        53	t	Antica Bramante		antica-bramante	Antica Bramante			10	antica-bramante	f	43	1.29.43.53	product	2013-07-04 14:49:22	2013-07-04 14:49:22	AN-Bramante	1500.00	PannoBramante.jpg	3	5	1	t	f	f	60.0	60.0	8.0
33	t	Antika 30*30	<p>Керамогранит ANTICA - это так сказать нишевый материал, он очень специфичен, но у него есть изюминка, которая и позволила ему так долго оставаться популярным. Во-первых, это рельефный керамогранит, т.н. керамогранит под камень или под состаренный камень, он весьма популярен в дизайне. Во-вторых, имея структурированную поверхность, очень востребован для отделки входных групп как противоскользящий керамогранит.</p>\r\n	antika-3030	Antika 30*30			10	antika-3030	f	32	1.13.32.33	product	2013-07-01 14:30:21	2013-07-01 14:30:21	AN	530.00	an01p.jpg	3	5	1	t	f	t	30.0	30.0	8.0
37	t	Trend 40*40	<p>Керамогранит TR - это коллекция, которую пожалуй еще не распробовал покупатель. TREND - керамогранит очень красивый, темные вкрапления разной величины, а также яркие и необычные фоновые цвета подняли его в более высокий класс. Сложность изготовления, яркость цветов оправдывает сравнительно высокую цену на эту коллекцию.</p>\r\n	trend-4040	Trend 40*40			40	trend-4040	f	32	1.13.32.37	product	2013-07-01 15:19:18	2013-07-01 15:19:18	TR	650.00	TR-01.jpg	2	4	1	f	f	f	40.0	40.0	10.0
35	t	Standart 60*60	<p>Коллекция керамогранита Estima ST или Standart - одна из самых старых и наиболее известных на рынке. Керамогранит ST имеет простой рисунок - фоновый цвет с мелкой крапинкой, т.н. керамогранит соль-перец. Такой цвет достаточно легок в изготовлении, соответственно - это недорогой материал, его простой цвет универсален в дизайне, и это и есть причина его сверхпопулярности.</p>\r\n	standart-6060	Standart 60*60			20	standart-6060	f	32	1.13.32.35	product	2013-07-01 14:40:22	2013-07-01 14:40:22	ST	550.00	st01 (3).jpg	2	7	1	f	f	f	60.0	60.0	10.0
38	t	Trend 60*60	<p>Керамогранит TR - это коллекция, которую пожалуй еще не распробовал покупатель. TREND - керамогранит очень красивый, темные вкрапления разной величины, а также яркие и необычные фоновые цвета подняли его в более высокий класс. Сложность изготовления, яркость цветов оправдывает сравнительно высокую цену на эту коллекцию.</p>\r\n	trend-6060	Trend 60*60			50	trend-4040	f	32	1.13.32.38	product	2013-07-01 15:21:47	2013-07-01 15:21:47	TR 60	950.00	TR-01.jpg	2	4	1	f	f	f	60.0	60.0	10.0
36	t	Stone 30*30	<p>Коллекция керамогранита STONE - адаптированный вариант хорошо известного на рынке рельефного керамогранита ESTIMA ANTICA. Он лишен высокого дизайнерского стиля и, таким образом, очень прагматичен. Его назначение - входные группы или любые площади, где требуется т.н. антискользящий керамогранит.</p>\r\n	stone-3030	Stone 30*30			20	stone-3030	f	32	1.13.32.36	product	2013-07-01 14:56:18	2013-07-01 14:56:18	SN	700.00	SN01.jpg	3	4	1	f	t	f	30.0	30.0	8.0
51	t	Палаццо 60*60	<p>Керамогранит Палаццо - исключительный выбор для любителей роскоши.</p>\r\n	palatstso-6060	Палаццо 60*60			20	palatstso-6060	f	34	1.13.34.51	product	2013-07-04 14:15:07	2013-07-04 14:15:07	GR73\\SG6059	2000.00	dekor-white.jpg	4	10	1	f	t	f	60.0	60.0	10.0
50	t	Аллея 30*30		alleya-3030	Аллея 30*30			10	alleya-3030	f	34	1.13.34.50	product	2013-07-04 13:59:10	2013-07-04 13:59:10	SG9065	1300.00	list30.jpg	2	5	1	t	f	f	30.0	30.0	8.0
54	t	Патио 30*30		patio-3030	Патио 30*30			30	patio-3030	f	32	1.13.32.54	product	2013-07-04 14:59:15	2013-07-04 14:59:15	SG906400N	700.00	braun.jpg	3	5	1	f	f	f	30.0	30.0	8.0
52	t	Ступень 1200*30		stupen-120030	Ступень 1200*30			10	stupen-120030	f	42	1.29.42.52	product	2013-07-04 14:22:43	2013-07-04 14:22:43	S120/30	8000.00	ступень (1).jpg	1	3	1	t	f	f	120.0	30.0	12.0
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             2553.dat                                                                                            100600  004000  002000  00000004033 12167500070 007111  0                                                                                                    ustar00                                                                                                                                                                                                                                                        3	St 01 полированный	st01 (5).jpg	2	35		1	600.00	t	2013-07-01 14:43:26	2013-07-01 14:43:26
10	Tr 02	TR-02.jpg	4	37		2	790.00	t	2013-07-01 15:21:39	2013-07-01 15:21:39
15	AN 05	An05p.jpg	5	33		3	900.00	t	2013-07-02 11:47:23	2013-07-02 11:47:23
14	AN 04	an04p.jpg	2	33		3	590.00	t	2013-07-02 11:46:59	2013-07-02 11:46:59
13	AN 03	an03p.jpg	3	33		3	620.00	t	2013-07-02 11:46:33	2013-07-02 11:46:33
12	AN 02	an02p.jpg	3	33		3	600.00	t	2013-07-02 11:46:05	2013-07-02 11:46:05
16	TR 03	TR-03.jpg	4	37		2	900.00	t	2013-07-02 12:41:26	2013-07-02 12:41:26
17	TR 04	TR-04.jpg	6	37		2	1400.00	t	2013-07-02 12:42:00	2013-07-02 12:42:00
18	TR 05	TR-05_pol.jpg	3	37		2	950.00	t	2013-07-02 12:42:28	2013-07-02 12:42:28
19	TR 06	TR-06.jpg	2	37		2	1200.00	t	2013-07-02 12:42:53	2013-07-02 12:42:53
20	TR 07	TR-07.jpg	4	37		2	900.00	t	2013-07-02 12:43:22	2013-07-02 12:43:22
21	TR 08	TR08.jpg	8	37		2	980.00	t	2013-07-02 12:43:53	2013-07-02 12:43:53
22	TR 09	TR09.jpg	2	37		2	1150.00	t	2013-07-02 12:44:27	2013-07-02 12:44:27
5	SN 02	SN02.jpg	2	36		3	720.00	t	2013-07-01 14:57:09	2013-07-01 14:57:09
6	SN 03	SN03.jpg	3	36		3	750.00	t	2013-07-01 14:58:39	2013-07-01 14:58:39
7	SN 04	SN04.jpg	3	36		3	740.00	t	2013-07-01 15:01:05	2013-07-01 15:01:05
8	SN 08	SN08.jpg	5	36		3	900.00	t	2013-07-01 15:04:53	2013-07-01 15:04:53
23	Светлый	white.jpg	1	50		2	950.00	t	2013-07-04 13:59:56	2013-07-04 13:59:56
24	Бежевый	bej.jpg	2	50		2	1340.00	t	2013-07-04 14:00:25	2013-07-04 14:00:25
25	Кирпичный	brick.jpg	3	50		2	1500.00	t	2013-07-04 14:00:54	2013-07-04 14:00:54
26	SG605902R Светлый	white (1).jpg	2	51		4	1700.00	t	2013-07-04 14:16:31	2013-07-04 14:16:31
27	SG606002R Серый	grey.jpg	4	51		4	1900.00	t	2013-07-04 14:17:06	2013-07-04 14:17:06
28	SG907300N Белый	white (2).jpg	1	54		3	900.00	t	2013-07-04 14:59:58	2013-07-04 14:59:58
29	SG906200N Светло-бежевый	white-bej.jpg	2	54		3	950.00	t	2013-07-04 15:03:07	2013-07-04 15:03:07
30	SG906300N Бежевый	bej (1).jpg	2	54		3	850.00	t	2013-07-04 15:03:42	2013-07-04 15:03:42
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     2531.dat                                                                                            100600  004000  002000  00000000034 12167500070 007102  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	admin
2	user
3	guest
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    2559.dat                                                                                            100600  004000  002000  00000000357 12167500070 007124  0                                                                                                    ustar00                                                                                                                                                                                                                                                        2	Телефон	phone	+7 (495) 921-40-44
4	Прайс	price	price.xls
5	Координаты	coordinates	55.675306, 37.624741
1	email	email	larina@rekada.ru
3	Адрес	address	115230, Москва, Варшавское ш., д. 42
\.


                                                                                                                                                                                                                                                                                 2542.dat                                                                                            100600  004000  002000  00000000205 12167500070 007104  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	Полированный
2	Неполированный
3	Структурированный
4	Лаппатированный
\.


                                                                                                                                                                                                                                                                                                                                                                                           2533.dat                                                                                            100600  004000  002000  00000000102 12167500070 007100  0                                                                                                    ustar00                                                                                                                                                                                                                                                        1	admin	21232f297a57a5a743894a0e4a801fc3	admin@example.org	1
\.


                                                                                                                                                                                                                                                                                                                                                                                                                                                              restore.sql                                                                                         100600  004000  002000  00000120646 12167500070 010236  0                                                                                                    ustar00                                                                                                                                                                                                                                                        --
-- NOTE:
--
-- File paths need to be edited. Search for $$PATH$$ and
-- replace it with the path to the directory containing
-- the extracted data files.
--
--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

ALTER TABLE ONLY public.users DROP CONSTRAINT users_id_role_fkey;
ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_surface_fkey;
ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_pattern_fkey;
ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_parent_fkey;
ALTER TABLE ONLY public.product DROP CONSTRAINT product_id_country_fkey;
ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_id_surface_fkey;
ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_id_product_fkey;
ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_id_color_fkey;
ALTER TABLE ONLY public.page DROP CONSTRAINT page_id_parent_fkey;
ALTER TABLE ONLY public.news DROP CONSTRAINT news_id_parent_fkey;
ALTER TABLE ONLY public.category DROP CONSTRAINT category_id_parent_fkey;
ALTER TABLE ONLY public.brand DROP CONSTRAINT brand_id_parent_fkey;
DROP TRIGGER trig_update_product_node_path ON public.product;
DROP TRIGGER trig_update_page_node_path ON public.page;
DROP TRIGGER trig_update_news_node_path ON public.news;
DROP TRIGGER trig_update_category_node_path ON public.category;
DROP TRIGGER trig_update_brand_node_path ON public.brand;
DROP INDEX public.page_path_idx;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
ALTER TABLE ONLY public.surface DROP CONSTRAINT surface_pkey;
ALTER TABLE ONLY public.settings DROP CONSTRAINT settings_pkey;
ALTER TABLE ONLY public.settings DROP CONSTRAINT settings_name_key;
ALTER TABLE ONLY public.role DROP CONSTRAINT role_pkey;
ALTER TABLE ONLY public.product DROP CONSTRAINT product_pkey;
ALTER TABLE ONLY public.product_color DROP CONSTRAINT product_color_pkey;
ALTER TABLE ONLY public.pattern DROP CONSTRAINT pattern_pkey;
ALTER TABLE ONLY public.page_text DROP CONSTRAINT page_text_pkey;
ALTER TABLE ONLY public.page_text DROP CONSTRAINT page_text_mark_key;
ALTER TABLE ONLY public.page DROP CONSTRAINT page_pkey;
ALTER TABLE ONLY public.page DROP CONSTRAINT page_path_key;
ALTER TABLE ONLY public.page DROP CONSTRAINT page_name_key;
ALTER TABLE ONLY public.news DROP CONSTRAINT news_pkey;
ALTER TABLE ONLY public.gallerymain DROP CONSTRAINT gallerymain_pkey;
ALTER TABLE ONLY public.gallery DROP CONSTRAINT gallery_pkey;
ALTER TABLE ONLY public.feedback DROP CONSTRAINT feedback_pkey;
ALTER TABLE ONLY public.country DROP CONSTRAINT country_pkey;
ALTER TABLE ONLY public.color DROP CONSTRAINT color_pkey;
ALTER TABLE ONLY public.color DROP CONSTRAINT color_hex_key;
ALTER TABLE ONLY public.category DROP CONSTRAINT category_pkey;
ALTER TABLE ONLY public.brand DROP CONSTRAINT brand_pkey;
ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.surface ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.settings ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.role ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.product_color ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.product ALTER COLUMN updated_at DROP DEFAULT;
ALTER TABLE public.product ALTER COLUMN created_at DROP DEFAULT;
ALTER TABLE public.product ALTER COLUMN is_locked DROP DEFAULT;
ALTER TABLE public.product ALTER COLUMN "order" DROP DEFAULT;
ALTER TABLE public.product ALTER COLUMN is_published DROP DEFAULT;
ALTER TABLE public.product ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.pattern ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.page_text ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.page ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN updated_at DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN created_at DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN is_locked DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN "order" DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN is_published DROP DEFAULT;
ALTER TABLE public.news ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.gallerymain ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.gallery ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.feedback ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.country ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.color ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN updated_at DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN created_at DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN is_locked DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN "order" DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN is_published DROP DEFAULT;
ALTER TABLE public.category ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.brand ALTER COLUMN updated_at DROP DEFAULT;
ALTER TABLE public.brand ALTER COLUMN created_at DROP DEFAULT;
ALTER TABLE public.brand ALTER COLUMN is_locked DROP DEFAULT;
ALTER TABLE public.brand ALTER COLUMN "order" DROP DEFAULT;
ALTER TABLE public.brand ALTER COLUMN is_published DROP DEFAULT;
ALTER TABLE public.brand ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE public.users_id_seq;
DROP TABLE public.users;
DROP SEQUENCE public.surface_id_seq;
DROP TABLE public.surface;
DROP SEQUENCE public.settings_id_seq;
DROP TABLE public.settings;
DROP SEQUENCE public.role_id_seq;
DROP TABLE public.role;
DROP SEQUENCE public.product_color_id_seq;
DROP TABLE public.product_color;
DROP TABLE public.product;
DROP SEQUENCE public.pattern_id_seq;
DROP TABLE public.pattern;
DROP SEQUENCE public.page_text_id_seq;
DROP TABLE public.page_text;
DROP SEQUENCE public.page_id_seq;
DROP TABLE public.news;
DROP SEQUENCE public.gallerymain_id_seq;
DROP TABLE public.gallerymain;
DROP SEQUENCE public.gallery_id_seq;
DROP TABLE public.gallery;
DROP SEQUENCE public.feedback_id_seq;
DROP TABLE public.feedback;
DROP SEQUENCE public.country_id_seq;
DROP TABLE public.country;
DROP SEQUENCE public.color_id_seq;
DROP TABLE public.color;
DROP TABLE public.category;
DROP TABLE public.brand;
DROP TABLE public.page;
DROP FUNCTION public.urltranslit(text);
DROP FUNCTION public.trig_update_page_node_path();
DROP FUNCTION public.get_calculated_page_node_path(param_page_id bigint);
DROP EXTENSION ltree;
DROP EXTENSION plpgsql;
DROP SCHEMA public;
--
-- Name: public; Type: SCHEMA; Schema: -; Owner: pgsql
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pgsql;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pgsql
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: ltree; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS ltree WITH SCHEMA public;


--
-- Name: EXTENSION ltree; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION ltree IS 'data type for hierarchical tree-like structures';


SET search_path = public, pg_catalog;

--
-- Name: get_calculated_page_node_path(bigint); Type: FUNCTION; Schema: public; Owner: xu
--

CREATE FUNCTION get_calculated_page_node_path(param_page_id bigint) RETURNS ltree
    LANGUAGE sql
    AS $_$
SELECT
  CASE WHEN p.id_parent IS NULL THEN
    p.id::text::ltree
    ELSE
      get_calculated_page_node_path(p.id_parent) || p.id::text
    END
FROM page AS p
WHERE p.id = $1;
$_$;


ALTER FUNCTION public.get_calculated_page_node_path(param_page_id bigint) OWNER TO xu;

--
-- Name: trig_update_page_node_path(); Type: FUNCTION; Schema: public; Owner: xu
--

CREATE FUNCTION trig_update_page_node_path() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF TG_OP = 'UPDATE' THEN
            IF (COALESCE(OLD.id_parent, 0) != COALESCE(NEW.id_parent, 0) OR NEW.id != OLD.id) THEN
                -- update all nodes that are children of this one including this one
                UPDATE page
                SET path = get_calculated_page_node_path(id)
                WHERE OLD.path @> page.path;
            END IF;

        ELSIF TG_OP = 'INSERT' THEN
            UPDATE page SET path = get_calculated_page_node_path(NEW.id) WHERE page.id = NEW.id;

            IF (COALESCE(NEW."name", '') = '') THEN
                UPDATE page SET "name" = urltranslit(NEW.title) WHERE page.id = NEW.id;
            END IF;
            IF (COALESCE(NEW."page_title", '') = '') THEN
                UPDATE page SET "page_title" = NEW.title WHERE page.id = NEW.id;
            END IF;
            IF (COALESCE(NEW."entity", '') = '') THEN
                UPDATE page SET "entity" = TG_TABLE_NAME WHERE page.id = NEW.id;
            END IF;

            IF (COALESCE(NEW."page_title", '') = '') THEN
                UPDATE page SET page_url = urltranslit(NEW.title) WHERE page.id = NEW.id;
            END IF;

        END IF;

        RETURN NEW;
    END
    $$;


ALTER FUNCTION public.trig_update_page_node_path() OWNER TO xu;

--
-- Name: urltranslit(text); Type: FUNCTION; Schema: public; Owner: xu
--

CREATE FUNCTION urltranslit(text) RETURNS text
    LANGUAGE sql
    AS $_$
SELECT
regexp_replace(
	replace(
		replace(
			replace(
				replace(
					replace(
						replace(
							replace(
                translate(
                  lower($1),
                  ' абвгдеёзийклмнопрстуфхыэъь',
                  '-abvgdeezijklmnoprstufhye'
                ), 'ж',	'zh'
							), 'ц',	'ts'
						), 'ч',	'ch'
					), 'ш',	'sh'
				), 'щ',	'sch'
			), 'ю', 'yu'
		), 'я',	'ya'
	)
	,
	'[^a-z0-9-]+',
	'',
	'g'
)
$_$;


ALTER FUNCTION public.urltranslit(text) OWNER TO xu;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: page; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE page (
    id bigint NOT NULL,
    is_published boolean DEFAULT false,
    title character varying(255) NOT NULL,
    content text,
    page_url character varying(255),
    page_title character varying(255),
    page_description text,
    page_keywords text,
    "order" integer DEFAULT 0,
    name character varying(255),
    is_locked boolean DEFAULT false,
    id_parent bigint,
    path ltree,
    entity character varying(255),
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.page OWNER TO xu;

--
-- Name: brand; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE brand (
    id_parent bigint,
    image character varying(255)
)
INHERITS (page);


ALTER TABLE public.brand OWNER TO xu;

--
-- Name: category; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE category (
    id_parent bigint,
    image character varying(255)
)
INHERITS (page);


ALTER TABLE public.category OWNER TO xu;

--
-- Name: color; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE color (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    hex character varying(7) NOT NULL
);


ALTER TABLE public.color OWNER TO xu;

--
-- Name: color_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.color_id_seq OWNER TO xu;

--
-- Name: color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE color_id_seq OWNED BY color.id;


--
-- Name: country; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE country (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.country OWNER TO xu;

--
-- Name: country_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE country_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.country_id_seq OWNER TO xu;

--
-- Name: country_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE country_id_seq OWNED BY country.id;


--
-- Name: feedback; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE feedback (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255) NOT NULL,
    content text,
    created_at timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.feedback OWNER TO xu;

--
-- Name: feedback_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE feedback_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.feedback_id_seq OWNER TO xu;

--
-- Name: feedback_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE feedback_id_seq OWNED BY feedback.id;


--
-- Name: gallery; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE gallery (
    id integer NOT NULL,
    is_published boolean DEFAULT false,
    title character varying(255) NOT NULL,
    image character varying(255),
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.gallery OWNER TO xu;

--
-- Name: gallery_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE gallery_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gallery_id_seq OWNER TO xu;

--
-- Name: gallery_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE gallery_id_seq OWNED BY gallery.id;


--
-- Name: gallerymain; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE gallerymain (
    id integer NOT NULL,
    is_published boolean DEFAULT false,
    title character varying(255) NOT NULL,
    text text,
    image character varying(255),
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now(),
    url character varying(255)
);


ALTER TABLE public.gallerymain OWNER TO xu;

--
-- Name: gallerymain_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE gallerymain_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gallerymain_id_seq OWNER TO xu;

--
-- Name: gallerymain_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE gallerymain_id_seq OWNED BY gallerymain.id;


--
-- Name: news; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE news (
    id_parent bigint,
    preview text,
    published_at timestamp(0) without time zone DEFAULT now()
)
INHERITS (page);


ALTER TABLE public.news OWNER TO xu;

--
-- Name: page_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.page_id_seq OWNER TO xu;

--
-- Name: page_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE page_id_seq OWNED BY page.id;


--
-- Name: page_text; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE page_text (
    id integer NOT NULL,
    mark character varying(255) NOT NULL,
    "group" character varying(255) NOT NULL,
    group_title character varying(255) DEFAULT NULL::character varying,
    "position" character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    content text,
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.page_text OWNER TO xu;

--
-- Name: page_text_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE page_text_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.page_text_id_seq OWNER TO xu;

--
-- Name: page_text_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE page_text_id_seq OWNED BY page_text.id;


--
-- Name: pattern; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE pattern (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.pattern OWNER TO xu;

--
-- Name: pattern_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE pattern_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pattern_id_seq OWNER TO xu;

--
-- Name: pattern_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE pattern_id_seq OWNED BY pattern.id;


--
-- Name: product; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE product (
    id_parent bigint,
    article character varying(255) NOT NULL,
    cost numeric(10,2),
    image character varying(255),
    id_surface integer NOT NULL,
    id_pattern integer,
    id_country integer NOT NULL,
    is_action boolean DEFAULT false,
    is_new boolean DEFAULT false,
    is_hit boolean DEFAULT false,
    width numeric(10,1),
    height numeric(10,1),
    depth numeric(10,1)
)
INHERITS (page);


ALTER TABLE public.product OWNER TO xu;

--
-- Name: product_color; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE product_color (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    image character varying(255),
    id_color integer NOT NULL,
    id_product integer NOT NULL,
    content text,
    id_surface integer NOT NULL,
    cost numeric(10,2),
    is_published boolean DEFAULT false,
    created_at timestamp(0) without time zone DEFAULT now(),
    updated_at timestamp(0) without time zone DEFAULT now()
);


ALTER TABLE public.product_color OWNER TO xu;

--
-- Name: product_color_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE product_color_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_color_id_seq OWNER TO xu;

--
-- Name: product_color_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE product_color_id_seq OWNED BY product_color.id;


--
-- Name: role; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE role (
    id integer NOT NULL,
    name character varying(255)
);


ALTER TABLE public.role OWNER TO xu;

--
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.role_id_seq OWNER TO xu;

--
-- Name: role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE role_id_seq OWNED BY role.id;


--
-- Name: settings; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE settings (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    name character varying(255),
    value character varying(511)
);


ALTER TABLE public.settings OWNER TO xu;

--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.settings_id_seq OWNER TO xu;

--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE settings_id_seq OWNED BY settings.id;


--
-- Name: surface; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE surface (
    id integer NOT NULL,
    title character varying(255) NOT NULL
);


ALTER TABLE public.surface OWNER TO xu;

--
-- Name: surface_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE surface_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.surface_id_seq OWNER TO xu;

--
-- Name: surface_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE surface_id_seq OWNED BY surface.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: xu; Tablespace: 
--

CREATE TABLE users (
    id bigint NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(63) NOT NULL,
    email character varying(511),
    id_role integer
);


ALTER TABLE public.users OWNER TO xu;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: xu
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO xu;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: xu
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- Name: is_published; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand ALTER COLUMN is_published SET DEFAULT false;


--
-- Name: order; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand ALTER COLUMN "order" SET DEFAULT 0;


--
-- Name: is_locked; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand ALTER COLUMN is_locked SET DEFAULT false;


--
-- Name: created_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- Name: is_published; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category ALTER COLUMN is_published SET DEFAULT false;


--
-- Name: order; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category ALTER COLUMN "order" SET DEFAULT 0;


--
-- Name: is_locked; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category ALTER COLUMN is_locked SET DEFAULT false;


--
-- Name: created_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY color ALTER COLUMN id SET DEFAULT nextval('color_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY country ALTER COLUMN id SET DEFAULT nextval('country_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY feedback ALTER COLUMN id SET DEFAULT nextval('feedback_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY gallery ALTER COLUMN id SET DEFAULT nextval('gallery_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY gallerymain ALTER COLUMN id SET DEFAULT nextval('gallerymain_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- Name: is_published; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news ALTER COLUMN is_published SET DEFAULT false;


--
-- Name: order; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news ALTER COLUMN "order" SET DEFAULT 0;


--
-- Name: is_locked; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news ALTER COLUMN is_locked SET DEFAULT false;


--
-- Name: created_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY page ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY page_text ALTER COLUMN id SET DEFAULT nextval('page_text_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY pattern ALTER COLUMN id SET DEFAULT nextval('pattern_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product ALTER COLUMN id SET DEFAULT nextval('page_id_seq'::regclass);


--
-- Name: is_published; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product ALTER COLUMN is_published SET DEFAULT false;


--
-- Name: order; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product ALTER COLUMN "order" SET DEFAULT 0;


--
-- Name: is_locked; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product ALTER COLUMN is_locked SET DEFAULT false;


--
-- Name: created_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product ALTER COLUMN created_at SET DEFAULT now();


--
-- Name: updated_at; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product ALTER COLUMN updated_at SET DEFAULT now();


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product_color ALTER COLUMN id SET DEFAULT nextval('product_color_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY role ALTER COLUMN id SET DEFAULT nextval('role_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY settings ALTER COLUMN id SET DEFAULT nextval('settings_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY surface ALTER COLUMN id SET DEFAULT nextval('surface_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: xu
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Data for Name: brand; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY brand (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, image) FROM stdin;
\.
COPY brand (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, image) FROM '$$PATH$$/2550.dat';

--
-- Data for Name: category; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY category (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, image) FROM stdin;
\.
COPY category (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, image) FROM '$$PATH$$/2549.dat';

--
-- Data for Name: color; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY color (id, title, hex) FROM stdin;
\.
COPY color (id, title, hex) FROM '$$PATH$$/2540.dat';

--
-- Name: color_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('color_id_seq', 8, true);


--
-- Data for Name: country; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY country (id, title) FROM stdin;
\.
COPY country (id, title) FROM '$$PATH$$/2546.dat';

--
-- Name: country_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('country_id_seq', 2, true);


--
-- Data for Name: feedback; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY feedback (id, name, email, phone, content, created_at) FROM stdin;
\.
COPY feedback (id, name, email, phone, content, created_at) FROM '$$PATH$$/2548.dat';

--
-- Name: feedback_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('feedback_id_seq', 19, true);


--
-- Data for Name: gallery; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY gallery (id, is_published, title, image, created_at, updated_at) FROM stdin;
\.
COPY gallery (id, is_published, title, image, created_at, updated_at) FROM '$$PATH$$/2555.dat';

--
-- Name: gallery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('gallery_id_seq', 1, false);


--
-- Data for Name: gallerymain; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY gallerymain (id, is_published, title, text, image, created_at, updated_at, url) FROM stdin;
\.
COPY gallerymain (id, is_published, title, text, image, created_at, updated_at, url) FROM '$$PATH$$/2557.dat';

--
-- Name: gallerymain_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('gallerymain_id_seq', 5, true);


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY news (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, preview, published_at) FROM stdin;
\.
COPY news (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, preview, published_at) FROM '$$PATH$$/2538.dat';

--
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY page (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at) FROM stdin;
\.
COPY page (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at) FROM '$$PATH$$/2537.dat';

--
-- Name: page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('page_id_seq', 58, true);


--
-- Data for Name: page_text; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY page_text (id, mark, "group", group_title, "position", title, content, created_at, updated_at) FROM stdin;
\.
COPY page_text (id, mark, "group", group_title, "position", title, content, created_at, updated_at) FROM '$$PATH$$/2535.dat';

--
-- Name: page_text_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('page_text_id_seq', 3, true);


--
-- Data for Name: pattern; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY pattern (id, title) FROM stdin;
\.
COPY pattern (id, title) FROM '$$PATH$$/2544.dat';

--
-- Name: pattern_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('pattern_id_seq', 11, true);


--
-- Data for Name: product; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY product (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, article, cost, image, id_surface, id_pattern, id_country, is_action, is_new, is_hit, width, height, depth) FROM stdin;
\.
COPY product (id, is_published, title, content, page_url, page_title, page_description, page_keywords, "order", name, is_locked, id_parent, path, entity, created_at, updated_at, article, cost, image, id_surface, id_pattern, id_country, is_action, is_new, is_hit, width, height, depth) FROM '$$PATH$$/2551.dat';

--
-- Data for Name: product_color; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY product_color (id, title, image, id_color, id_product, content, id_surface, cost, is_published, created_at, updated_at) FROM stdin;
\.
COPY product_color (id, title, image, id_color, id_product, content, id_surface, cost, is_published, created_at, updated_at) FROM '$$PATH$$/2553.dat';

--
-- Name: product_color_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('product_color_id_seq', 30, true);


--
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY role (id, name) FROM stdin;
\.
COPY role (id, name) FROM '$$PATH$$/2531.dat';

--
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('role_id_seq', 3, true);


--
-- Data for Name: settings; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY settings (id, title, name, value) FROM stdin;
\.
COPY settings (id, title, name, value) FROM '$$PATH$$/2559.dat';

--
-- Name: settings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('settings_id_seq', 5, true);


--
-- Data for Name: surface; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY surface (id, title) FROM stdin;
\.
COPY surface (id, title) FROM '$$PATH$$/2542.dat';

--
-- Name: surface_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('surface_id_seq', 4, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: xu
--

COPY users (id, username, password, email, id_role) FROM stdin;
\.
COPY users (id, username, password, email, id_role) FROM '$$PATH$$/2533.dat';

--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: xu
--

SELECT pg_catalog.setval('users_id_seq', 1, true);


--
-- Name: brand_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY brand
    ADD CONSTRAINT brand_pkey PRIMARY KEY (id);


--
-- Name: category_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY category
    ADD CONSTRAINT category_pkey PRIMARY KEY (id);


--
-- Name: color_hex_key; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY color
    ADD CONSTRAINT color_hex_key UNIQUE (hex);


--
-- Name: color_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY color
    ADD CONSTRAINT color_pkey PRIMARY KEY (id);


--
-- Name: country_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY country
    ADD CONSTRAINT country_pkey PRIMARY KEY (id);


--
-- Name: feedback_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY feedback
    ADD CONSTRAINT feedback_pkey PRIMARY KEY (id);


--
-- Name: gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);


--
-- Name: gallerymain_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY gallerymain
    ADD CONSTRAINT gallerymain_pkey PRIMARY KEY (id);


--
-- Name: news_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);


--
-- Name: page_name_key; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_name_key UNIQUE (name);


--
-- Name: page_path_key; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_path_key UNIQUE (path);


--
-- Name: page_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_pkey PRIMARY KEY (id);


--
-- Name: page_text_mark_key; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY page_text
    ADD CONSTRAINT page_text_mark_key UNIQUE (mark);


--
-- Name: page_text_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY page_text
    ADD CONSTRAINT page_text_pkey PRIMARY KEY (id);


--
-- Name: pattern_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY pattern
    ADD CONSTRAINT pattern_pkey PRIMARY KEY (id);


--
-- Name: product_color_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_pkey PRIMARY KEY (id);


--
-- Name: product_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_pkey PRIMARY KEY (id);


--
-- Name: role_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- Name: settings_name_key; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_name_key UNIQUE (name);


--
-- Name: settings_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: surface_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY surface
    ADD CONSTRAINT surface_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_username_key; Type: CONSTRAINT; Schema: public; Owner: xu; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: page_path_idx; Type: INDEX; Schema: public; Owner: xu; Tablespace: 
--

CREATE INDEX page_path_idx ON page USING gist (path);


--
-- Name: trig_update_brand_node_path; Type: TRIGGER; Schema: public; Owner: xu
--

CREATE TRIGGER trig_update_brand_node_path AFTER INSERT OR UPDATE OF id, id_parent ON brand FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();


--
-- Name: trig_update_category_node_path; Type: TRIGGER; Schema: public; Owner: xu
--

CREATE TRIGGER trig_update_category_node_path AFTER INSERT OR UPDATE OF id, id_parent ON category FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();


--
-- Name: trig_update_news_node_path; Type: TRIGGER; Schema: public; Owner: xu
--

CREATE TRIGGER trig_update_news_node_path AFTER INSERT OR UPDATE OF id, id_parent ON news FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();


--
-- Name: trig_update_page_node_path; Type: TRIGGER; Schema: public; Owner: xu
--

CREATE TRIGGER trig_update_page_node_path AFTER INSERT OR UPDATE OF id, id_parent ON page FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();


--
-- Name: trig_update_product_node_path; Type: TRIGGER; Schema: public; Owner: xu
--

CREATE TRIGGER trig_update_product_node_path AFTER INSERT OR UPDATE OF id, id_parent ON product FOR EACH ROW EXECUTE PROCEDURE trig_update_page_node_path();


--
-- Name: brand_id_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY brand
    ADD CONSTRAINT brand_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES category(id) ON DELETE CASCADE;


--
-- Name: category_id_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY category
    ADD CONSTRAINT category_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES page(id) ON DELETE CASCADE;


--
-- Name: news_id_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY news
    ADD CONSTRAINT news_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES page(id) ON DELETE CASCADE;


--
-- Name: page_id_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES page(id) ON DELETE CASCADE;


--
-- Name: product_color_id_color_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_id_color_fkey FOREIGN KEY (id_color) REFERENCES color(id) ON DELETE CASCADE;


--
-- Name: product_color_id_product_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_id_product_fkey FOREIGN KEY (id_product) REFERENCES product(id) ON DELETE CASCADE;


--
-- Name: product_color_id_surface_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product_color
    ADD CONSTRAINT product_color_id_surface_fkey FOREIGN KEY (id_surface) REFERENCES surface(id) ON DELETE CASCADE;


--
-- Name: product_id_country_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_country_fkey FOREIGN KEY (id_country) REFERENCES country(id) ON DELETE CASCADE;


--
-- Name: product_id_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_parent_fkey FOREIGN KEY (id_parent) REFERENCES brand(id) ON DELETE CASCADE;


--
-- Name: product_id_pattern_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_pattern_fkey FOREIGN KEY (id_pattern) REFERENCES pattern(id) ON DELETE CASCADE;


--
-- Name: product_id_surface_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY product
    ADD CONSTRAINT product_id_surface_fkey FOREIGN KEY (id_surface) REFERENCES surface(id) ON DELETE CASCADE;


--
-- Name: users_id_role_fkey; Type: FK CONSTRAINT; Schema: public; Owner: xu
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_id_role_fkey FOREIGN KEY (id_role) REFERENCES role(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: pgsql
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM pgsql;
GRANT ALL ON SCHEMA public TO pgsql;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          