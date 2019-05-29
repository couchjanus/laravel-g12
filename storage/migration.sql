CreateUsersTable: create table "users" ("id" bigserial primary key not null, "name" varchar(255) not null, "email" varchar(255) not null, "email_verified_at" timestamp(0) without time zone null, "password" varchar(255) not null, "remember_token" varchar(100) null, "created_at" timestamp(0) without time zone null, "updated_at" timestamp(0) without time zone null)
CreateUsersTable: alter table "users" add constraint "users_email_unique" unique ("email")
CreatePasswordResetsTable: create table "password_resets" ("email" varchar(255) not null, "token" varchar(255) not null, "created_at" timestamp(0) without time zone null)
CreatePasswordResetsTable: create index "password_resets_email_index" on "password_resets" ("email")

CreateTestsTable: create table "tests" (
    "id" bigserial primary key not null, "created_at" timestamp(0) without time zone null, 
    "updated_at" timestamp(0) without time zone null
    )



-- Table: public.comments

-- DROP TABLE public.comments;

CREATE TABLE public.comments
(
  id bigint NOT NULL DEFAULT nextval('comments_id_seq'::regclass),
  body text NOT NULL,
  commentable_type character varying(255) NOT NULL,
  commentable_id bigint NOT NULL,
  creator_type character varying(255) NOT NULL,
  creator_id bigint NOT NULL,
  votes integer NOT NULL DEFAULT 0,
  spam integer NOT NULL DEFAULT 0,
  created_at timestamp(0) without time zone,
  updated_at timestamp(0) without time zone,
  _lft integer NOT NULL DEFAULT 0,
  _rgt integer NOT NULL DEFAULT 0,
  parent_id integer,
  CONSTRAINT comments_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.comments
  OWNER TO dev;

-- Index: public.comments__lft__rgt_parent_id_index

-- DROP INDEX public.comments__lft__rgt_parent_id_index;

CREATE INDEX comments__lft__rgt_parent_id_index
  ON public.comments
  USING btree
  (_lft, _rgt, parent_id);

-- Index: public.comments_commentable_type_commentable_id_index

-- DROP INDEX public.comments_commentable_type_commentable_id_index;

CREATE INDEX comments_commentable_type_commentable_id_index
  ON public.comments
  USING btree
  (commentable_type COLLATE pg_catalog."default", commentable_id);

-- Index: public.comments_creator_type_creator_id_index

-- DROP INDEX public.comments_creator_type_creator_id_index;

CREATE INDEX comments_creator_type_creator_id_index
  ON public.comments
  USING btree
  (creator_type COLLATE pg_catalog."default", creator_id);

