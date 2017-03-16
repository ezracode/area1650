select a.code * c.code as codigo, c.name, b.name, a.name from team a inner join team b 
left join country c on a.country = c.code
where
a.code <> b.code
order by codigo, c.code