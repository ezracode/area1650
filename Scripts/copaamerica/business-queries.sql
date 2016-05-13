select a.code, b.name from tournament a, country b where a.country = b.code;

/*Tabla por campeonato desde 1916 hasta 1967*/
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
    on a.code = b.squad and b.matchid = h.matchid and h.game_type in (2) and year(h.matchdate) = 1923
left join game_score c
    on c.points >= 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points = 1 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 0 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g
    on b.matchid = g.matchid and b.time_type = g.time_type and b.squad <> g.squad
where b.time_type in (2, 4, 6) 
group by a.code
order by points desc, diff desc, goals desc, again desc

/*Tabla General todos los tiempos*/
select 
    a.name, 
	 sum(b.points) points, 
	 count(c.squad) games,
	 max(h.game_type) game_type,
	 count(d.squad) win, 
	 count(e.squad) draw,
	 count(f.squad) loose,
 	 sum(g.goals) goals,
	 sum(i.goals) again,
	 (sum(g.goals) - sum(i.goals)) diff
from 
country a inner join game_score b inner join game h 
    on a.code = b.squad and b.matchid = h.matchid and b.time_type in (2,4) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8)
                        and year(h.matchdate) < 2016 
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g
    on b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad
left join game_score i
    on b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad
group by a.code
order by game_type desc, points desc, diff desc, goals desc, again desc

/*Tabla por grupo desde 1975 actualidad*/
select 
    i.group_code,
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
country a inner join game_score b inner join game h inner join group_stage i
    on a.code = b.squad and b.matchid = h.matchid and h.game_type in (2) and year(h.matchdate) = 2001 
	 and b.squad = i.squad and i.group_code in ('A', 'B', 'C')  and i.tournament = 2001
left join game_score c
    on c.points >= 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points = 1 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 0 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g
    on b.matchid = g.matchid and b.time_type = g.time_type and b.squad <> g.squad
where b.time_type in (2, 4, 6) 
group by a.code
order by group_code, points desc, diff desc, goals desc, again desc

/*Tabla por campeonato desde 1975 actualidad*/
select 
    a.name, 
	 sum(b.points) points, 
	 count(c.squad) games,
	 max(h.game_type) game_type,
	 count(d.squad) win, 
	 count(e.squad) draw,
	 count(f.squad) loose,
 	 sum(g.goals) goals,
	 sum(i.goals) again,
	 (sum(g.goals) - sum(i.goals)) diff
from 
country a inner join game_score b inner join game h 
    on a.code = b.squad and b.matchid = h.matchid and b.time_type in (2,4) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and year(h.matchdate) = 2015
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g
    on b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad
left join game_score i
    on b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad
group by a.code
order by game_type desc, points desc, diff desc, goals desc, again desc