delete from tournament where code > 0;
insert into tournament (code, country) values
    (1916, 54),     (1917, 598),      (1919, 55),
	(1920, 56),     (1921, 54),       (1922, 55),
	(1923, 598),    (1924, 598),      (1925, 54),
    (1926, 56),     (1927, 51),       (1929, 54),
	(1935, 51),     (1937, 54),       (1939, 51),
	(1941, 56),     (1942, 598),      (1945, 56),
    (1946, 54),     (1947, 593),      (1949, 55),
	(1953, 51),     (1955, 56),       (1956, 598),
	(1957, 51),     (1959, 54),       (1959, 593),
	(1963, 591),    (1967, 598);
	
update game set country = 54  where year(matchdate) = 1916; update game set country = 598 where year(matchdate) = 1917;
update game set country = 55  where year(matchdate) = 1919; update game set country = 56  where year(matchdate) = 1920;
update game set country = 54  where year(matchdate) = 1921; update game set country = 55  where year(matchdate) = 1922;
update game set country = 598 where year(matchdate) = 1923; update game set country = 598 where year(matchdate) = 1924;
update game set country = 54  where year(matchdate) = 1925; update game set country = 56  where year(matchdate) = 1926;
update game set country = 51  where year(matchdate) = 1927; update game set country = 54  where year(matchdate) = 1929;
update game set country = 51  where year(matchdate) = 1935; update game set country = 54  where year(matchdate) in (1936, 1937);
update game set country = 51  where year(matchdate) = 1939; update game set country = 56  where year(matchdate) = 1941;
update game set country = 598 where year(matchdate) = 1942; update game set country = 56  where year(matchdate) = 1945;
update game set country = 598 where year(matchdate) = 1946; update game set country = 56  where year(matchdate) = 1947;
update game set country = 598 where year(matchdate) = 1949; update game set country = 56  where year(matchdate) = 1953;
update game set country = 598 where year(matchdate) = 1955; update game set country = 56  where year(matchdate) = 1956;
update game set country = 598 where year(matchdate) = 1957; 
update game set country = 56  where year(matchdate) = 1959 and month(matchdate) in (3,4);
update game set country = 598 where year(matchdate) = 1959 and month(matchdate) = 12;
update game set country = 56  where year(matchdate) = 1963;
update game set country = 56  where matchid = 364;
update game set country = 57  where matchid = 365;
update game set country = 593 where matchid = 366;
update game set country = 595 where matchid = 367;
update game set country = 598 where year(matchdate) = 1967;