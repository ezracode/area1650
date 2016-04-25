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
    on a.code = b.squad and b.matchid = h.matchid and h.game_type = 1 and   year(h.matchdate) = 1922
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
order by points desc, diff desc, goals desc, again desc