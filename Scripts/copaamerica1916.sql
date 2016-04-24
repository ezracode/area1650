insert into game (matchid, matchdate, game_type) values
                 (1, '1916-07-02', 1),
                 (2, '1916-07-06', 1),
                 (3, '1916-07-08', 1),
                 (4, '1916-07-10', 1),
                 (5, '1916-07-12', 1),
                 (6, '1916-07-16', 1);

insert into game_score (id, matchid, squad, goals, points, time_type) values
                       ( 1,       1,   598,     4,      2,         2),
                       ( 2,       1,   598,     1,      0,         1),
                       ( 3,       1,   056,     0,      0,         2),  
                       ( 4,       1,   056,     0,      0,         1),
                       ( 5,       2,   054,     6,      2,         2),
                       ( 6,       2,   054,     1,      0,         1),
                       ( 7,       2,   056,     1,      0,         2),
                       ( 8,       2,   056,     1,      0,         1),
                       ( 9,       3,   056,     1,      1,         2),
                       (10,       3,   056,     0,      0,         1),
                       (11,       3,   055,     1,      1,         2),
                       (12,       3,   055,     1,      0,         1),
                       (13,       4,   054,     1,      1,         2),
                       (14,       4,   054,     1,      0,         1),
                       (15,       4,   055,     1,      1,         2),
                       (16,       4,   055,     1,      0,         1),
                       (17,       5,   598,     2,      2,         2),
                       (18,       5,   598,     0,      0,         1),
                       (19,       5,   055,     1,      0,         2),
                       (20,       5,   055,     0,      0,         1),
                       (21,       6,   054,     0,      1,         2),
                       (22,       6,   054,     0,      0,         1),
                       (23,       6,   598,     0,      1,         2),
                       (24,       6,   598,     0,      0,         1);

select 
    a.name, 
	 sum(b.points) points, 
	 count(b.squad) games, 
	 count(c.squad) win, 
	 count(d.squad) draw, 
	 count(e.squad) loose,
	 sum(f.goals) goals,
	 sum(g.goals) again,
	 (sum(f.goals) - sum(g.goals)) diff
from 
country a inner join game_score b 
    on a.code = b.squad   
left join game_score c
    on c.points = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points = 1 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 0 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g
    on b.matchid = g.matchid and b.time_type = g.time_type and b.squad <> g.squad
where b.time_type = 2 
group by a.code
order by points desc