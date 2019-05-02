CreateUsersTable: create table "users" ("id" bigserial primary key not null, "name" varchar(255) not null, "email" varchar(255) not null, "email_verified_at" timestamp(0) without time zone null, "password" varchar(255) not null, "remember_token" varchar(100) null, "created_at" timestamp(0) without time zone null, "updated_at" timestamp(0) without time zone null)
CreateUsersTable: alter table "users" add constraint "users_email_unique" unique ("email")
CreatePasswordResetsTable: create table "password_resets" ("email" varchar(255) not null, "token" varchar(255) not null, "created_at" timestamp(0) without time zone null)
CreatePasswordResetsTable: create index "password_resets_email_index" on "password_resets" ("email")

CreateTestsTable: create table "tests" (
    "id" bigserial primary key not null, "created_at" timestamp(0) without time zone null, 
    "updated_at" timestamp(0) without time zone null
    )
