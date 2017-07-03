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

ROADMAP

Step 1:create populated places in a universe 

a) generate random stars (statisticaly equivalent as in our galaxy) --done--

b) add a galactic structure similar to reality --done--

b) generate random planets --done--

c) generate complete stellar system (1,n stars + 0,n planets) --done--

d) generate random sentient races --done--


Step 2: integration of habitable zones for stars 

a) planetary temperature estimation --done--

b) mark habitable planets --done--

c) use it to alter population generation --done--

Addendum:

a) generate galactic viewer --done--

b) generate group viewer --done--

c) generate system viewer --done--
