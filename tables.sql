create table if not exists country (
     code smallint primary key, 
     name varchar(125) charset utf8
);

create table if not exists time_type (
     id smallint primary key,
     name varchar(25) charset utf8
);

create table if not exists game (
     matchid smallint primary key,
     matchdate date
);

create table if not exists game_score (
     id smallint, 
     matchid smallint,
     squad smallint,
     goals tinyint,
     points tinyint,
     time_type smallint,
     unique(id, matchid)
);

