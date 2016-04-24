insert into game (matchid, matchdate, game_type) values
                 (7, '1917-09-30', 1),
                 (8, '1917-10-03', 1),
                 (9, '1917-10-06', 1),
                 (10, '1917-10-07', 1),
                 (11, '1917-10-12', 1),
                 (12, '1917-10-14', 1);

insert into game_score (id, matchid, squad, goals, points, time_type) values
                       (25,       7,   598,     4,      2,         2),
                       (26,       7,   598,     2,      0,         1),
                       (27,       7,   056,     0,      0,         2),  
                       (28,       7,   056,     0,      0,         1),
                       (29,       8,   054,     4,      2,         2),
                       (30,       8,   054,     1,      0,         1),
                       (31,       8,   055,     2,      0,         2),
                       (32,       8,   055,     2,      0,         1),
                       (33,       9,   054,     1,      2,         2),
                       (34,       9,   054,     0,      0,         1),
                       (35,       9,   056,     0,      0,         2),
                       (36,       9,   056,     0,      0,         1),
                       (37,      10,   598,     4,      2,         2),
                       (38,      10,   598,     2,      0,         1),
                       (39,      10,   055,     0,      0,         2),
                       (40,      10,   055,     0,      0,         1),
                       (41,      11,   055,     5,      2,         2),
                       (42,      11,   055,     4,      0,         1),
                       (43,      11,   056,     0,      0,         2),
                       (44,      11,   056,     0,      0,         1),
                       (45,      12,   598,     1,      2,         2),
                       (46,      12,   598,     0,      0,         1),
                       (47,      12,   054,     0,      0,         2),
                       (48,      12,   054,     0,      0,         1);

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
country a inner join game_score b inner join game h
    on a.code = b.squad and b.matchid = h.matchid and year(h.matchdate) = 1917
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