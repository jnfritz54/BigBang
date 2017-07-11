# BigBang
one little push that create a universe

Current State:
Create a customizable spiral galaxy with 4 arms and a central bulb, fill systems with stars and planets.

Can also determine whether a planet is in the habitable zone or not

Nova calculations to alter planet viability is functional but takes too much time to be usable

Generate random basics lifeforms on habitable planets


USAGE:
1) Create database using bigbang.sql (update db logins in generator.php if necessary) 
2) *OPTIONAL* customize galaxy by editing the galaxy object definition file (spiral arms, width etc...)
3) php launcher : populate stars and planets, determine habitable planets
4) use a browser to open the index.php file , load the viewers from the left menu and see what has been generated

ROADMAP

Step 1:create populated places in a universe 

1) generate random stars (statisticaly equivalent as in our galaxy) --done--
2) add a galactic structure similar to reality --done--
3) generate random planets --done--
4) generate complete stellar system (1,n stars + 0,n planets) --done--
5) generate random sentient races --done--
6) transform circular orbits in elliptic ones 

Step 2: integration of habitable zones for stars 

1) planetary temperature estimation --done--
2) mark habitable planets --done--
3) use it to alter population generation --done--
4) determine novas impacts to alter habitability --done--

Step 3: Lifeforms generation
1) modelise generic lifeforms caracteristics --done--
2) make a grid or scale to summarize lifeform advancement --done--
3) generate random lifeforms --done--
4) create pronouncable names for said lifeforms 

Step 4: Planets maps
1) generate heightmaps --done--
2) colored heightmaps into png --done--
3) biome generation
4) generate views/maps for gaz giants
5) generate maps for asteroids belts

Addendum:
1) generate galactic viewer --done--
2) generate group viewer --done--
3) generate system viewer --done--
4) generate lifeforms distribution viewer --done--
