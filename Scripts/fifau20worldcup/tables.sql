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
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (44) and b.code = 44 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (331, 3399, 3398, 33) and b.code = 33 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (322, 32) and b.code = 32 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (1519, 1613) and b.code = 1613 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (13141850, 13141015, 1) and b.code = 1 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (90232, 90) and b.code = 90 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (30231, 3021, 30) and b.code = 30 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (4203, 42, 420) and b.code = 420 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (49, 49228, 4930) and b.code = 49 
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (38, 38111, 38220, 381) and b.code = 381
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (7812, 7097, 37517, 7) and b.code = 7
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (3531, 353) and b.code = 353
union
select a.code as oldsquad, a.name as oldname, b.code as newsquad, b.name as newname from country a, country b where a.code in (951, 95) and b.code = 95
union
select a.code as oldsquad, a.name as oldname, a.code as newsquad, a.name as newname from country a where a.code not in (44, 331, 3399, 3398, 33, 322, 32, 1519, 1613, 13141850, 13141015, 1, 90232, 90, 30231, 3021, 30, 4203, 42, 420, 49, 49228, 4930, 38, 38111, 38220, 381, 7812, 7097, 37517, 7, 3531, 353, 951, 95)