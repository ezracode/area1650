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
	 count(d.squad) win, 
	 count(e.squad) draw,
	 count(f.squad) loose,
 	 sum(g.goals) goals,
	 sum(i.goals) again,
	 (sum(g.goals) - sum(i.goals)) diff
from 
country a inner join game_score b inner join game h 
    on a.code = b.squad and b.matchid = h.matchid and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < '2016-05-15'
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) 
	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g        
    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad 
left join game_score i
    on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad 
group by a.code
order by points desc, diff desc, goals desc, again desc


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
    on a.code = b.squad and b.matchid = h.matchid and b.time_type in (2,3,4) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and year(h.matchdate) = 2011
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) 
	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g        
    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4))  
	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad 
left join game_score i
    on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4))  
	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad 
group by a.code
order by game_type desc, points desc, diff desc, goals desc, again desc

/*Todos los resultados entre dos equipos*/
select i.oldname namea, j.oldname nameb, f.name gametype, g.name timetype, a.goals goalsa, b.goals goalsb, c.matchdate matchdate, ifnull(d.goals,-1) goalsd, ifnull(e.goals, -1) goalse, ifnull(h.name, '') penalties 
from game_score a inner join game_score b inner join game c
 on a.matchid = b.matchid and a.time_type = b.time_type and 
 a.time_type = (select max(time_type) from game_score where matchid = b.matchid and time_type in (2,3,4,6))
 and a.matchid = c.matchid and c.matchdate < now()
 left join game_score d 
  on d.time_type = 7
  and a.matchid = d.matchid
  and a.squad = d.squad
 left join game_score e 
  on e.time_type = 7
  and b.matchid = e.matchid
  and b.squad = e.squad
  left join game_type f on c.game_type = f.id
  left join time_type g on a.time_type = g.id
  left join time_type h on e.time_type = h.id
  left join current_country i on a.squad = i.oldsquad
  left join current_country j on b.squad = j.oldsquad
 where i.newsquad = 33 and j.newsquad = 40
 order by matchdate desc
 
/*Participacion por pais*/
select 
    a.newname, 
	year(h.matchdate) championship,
	sum(b.points) points, 
	count(c.squad) games,
	count(d.squad) win, 
	count(e.squad) draw,
	count(f.squad) loose,
 	sum(g.goals) goals,
	sum(i.goals) again,
	(sum(g.goals) - sum(i.goals)) diff
from 
current_country a inner join game_score b inner join game h 
    on a.code = b.squad     and b.matchid = h.matchid and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) 
	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g        
    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad 
left join game_score i
    on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad 
/*where b.squad in (49228, 49)*/
/*where b.squad in (42, 420)*/
/*where b.squad in (38, 38111)*/
group by a.newsquad

where b.squad in (7097, 37517, 7)
group by a.code, year(h.matchdate)
order by year(h.matchdate) asc, points desc, diff desc, goals desc, again desc

/*Tabla consolidada a nuevos codigos para Alemania, Rusia, Republica Checa y Serbia*/
select 
    a.newname, 
	 sum(b.points) points, 
	 count(c.squad) games,
	 count(d.squad) win, 
	 count(e.squad) draw,
	 count(f.squad) loose,
 	 sum(g.goals) goals,
	 sum(i.goals) again,
	 (sum(g.goals) - sum(i.goals)) diff
from 
current_country a inner join game_score b inner join game h 
    on a.oldsquad = b.squad and b.matchid = h.matchid and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad 
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) 
	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g        
    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad
left join game_score i
    on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad
group by a.newsquad
order by points desc, diff desc, goals desc, again desc

/*Informacion por equipo*/
/*Participacion por pais*/
select 
	a.newname, 
	sum(b.points) points, 
	count(c.squad) games,
	count(d.squad) win, 
	count(e.squad) draw,
	count(f.squad) loose,
 	sum(g.goals) goals,
	sum(i.goals) again,
	(sum(g.goals) - sum(i.goals)) diff,
	(count(d.squad) / count(c.squad)) pw,
	(count(e.squad) / count(c.squad)) pd,
 	(count(f.squad) / count(c.squad)) pl
from 
current_country a inner join game_score b inner join game h 
    on a.newsquad = 7 and a.oldsquad = b.squad and b.matchid = h.matchid and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and b.squad = c.squad
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and b.squad = d.squad
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and b.squad = e.squad
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) 
	                 and b.matchid = f.matchid and b.time_type = f.time_type and b.squad = f.squad
left join game_score g        
    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = g.matchid and b.time_type = g.time_type and b.squad = g.squad 
left join game_score i
    on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = i.matchid and b.time_type = i.time_type and b.squad <> i.squad 
/*where b.squad in (49228, 49)*/
/*where b.squad in (42, 420)*/
/*where b.squad in (38, 38111)*/
/*where a.newsquad in (7)*/
group by a.newsquad
order by points desc, diff desc, goals desc, again desc
/*Informacion entre dos equipos*/
select 
    a.newname suada, 
    l.newname squadb,
	 sum(b.points) pointsa,
	 sum(g.points) pointsb, 
	 count(c.squad) games,
	 count(d.squad) winb, 
	 count(e.squad) draw,
	 count(f.squad) wina,
 	 sum(g.goals) goalsa,
	 sum(i.goals) goalsb,
 	 abs(sum(g.goals) - sum(i.goals)) diff,
	 (count(d.squad) / count(c.squad)) pb,
	 (count(e.squad) / count(c.squad)) pd,
	 (count(f.squad) / count(c.squad)) pa
from 
current_country a inner join game_score b inner join game h inner join game_score j inner join current_country l
    on a.newsquad = 34 and a.oldsquad = b.squad and b.matchid = h.matchid and b.matchid = j.matchid and l.newsquad = 39 and l.oldsquad = j.squad and b.time_type = j.time_type
       and b.time_type in (2,3,4,6) and h.game_type in (1, 2, 3, 4, 5, 6, 7, 8) and h.matchdate < now()
left join game_score c
    on  b.time_type = 2 and b.matchid = c.matchid and b.time_type = c.time_type and l.newsquad = 39 and l.oldsquad = c.squad 
left join game_score d
    on d.points >= 2 and b.matchid = d.matchid and b.time_type = d.time_type and l.newsquad = 39 and l.oldsquad = d.squad 
left join game_score e
    on e.points = 1 and b.matchid = e.matchid and b.time_type = e.time_type and l.newsquad = 39 and l.oldsquad = e.squad 
left join game_score f
    on f.points = 0 and f.time_type = (select max(time_type) from game_score where matchid = f.matchid) 
	                 and b.matchid = f.matchid and b.time_type = f.time_type and l.newsquad = 39 and l.oldsquad = f.squad
left join game_score g        
    on g.time_type = (select max(time_type) from game_score where matchid = g.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = g.matchid and b.time_type = g.time_type and a.newsquad = 34 and a.oldsquad = g.squad 
left join game_score i
    on i.time_type = (select max(time_type) from game_score where matchid = i.matchid and time_type in (2,3,4,6))  
	                 and b.matchid = i.matchid and b.time_type = i.time_type and l.newsquad = 39 and l.oldsquad = i.squad
group by a.newsquad