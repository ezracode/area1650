create table if not exists country (
     code int primary key, 
     name varchar(125) charset utf8
);

create table if not exists tournament (
     code smallint, 
	 country int,
	 unique(code, country)
);

create table if not exists group_stage(
     id smallint primary key,
     tournament smallint,
	 group_code varchar(1),
	 squad int
);

create index group_stage_i on group_stage (tournament, group_code);

alter table group_stage add column group_order smallint;
update group_stage set group_order = 2 where id = 129;
update group_stage set group_order = 1 where id = 130;

create table if not exists time_type (
     id smallint primary key,
     name varchar(25) charset utf8
);

create table if not exists game_type (
    id smallint primary key,
    name varchar(25) charset utf8
);

create table if not exists game (
     matchid smallint primary key,
     matchdate date,
	 game_type smallint,
	 country int
);

create table if not exists game_score (
     id smallint, 
     matchid smallint,
     squad int,
     goals tinyint,
     points tinyint,
     time_type smallint,
     unique(id, matchid)
);

create index game_score_i on game_score (squad);

create view current_country as
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (86) and b.code = 86
union
select a.code as oldsquad, a.name as oldname, a.code as newsquad, a.name as newname from country a where a.code not in (86)