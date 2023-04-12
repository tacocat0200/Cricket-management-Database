create database cricket_management_5;

use cricket_management_5;


CREATE TABLE Teams (
    team_id VARCHAR(10) PRIMARY KEY,
    team_name VARCHAR(50),
    coach_name VARCHAR(50),
    home_ground VARCHAR(50)
);

CREATE TABLE Players (
    player_id VARCHAR(10) PRIMARY KEY,
    player_name VARCHAR(50),
    age INT,
    position VARCHAR(50),
    team_id VARCHAR(10) REFERENCES Teams(team_id)
);

CREATE TABLE Matches (
    match_id VARCHAR(10) PRIMARY KEY,
    match_date DATE,
    venue VARCHAR(50),
    team_1_id VARCHAR(10) REFERENCES Teams(team_id),
    team_2_id VARCHAR(10) REFERENCES Teams(team_id),
    winner_id VARCHAR(10) REFERENCES Teams(team_id)
);

CREATE TABLE Plays_In (
    team_id VARCHAR(10) REFERENCES Teams(team_id),
    match_id VARCHAR(10) REFERENCES Matches(match_id),
    PRIMARY KEY (team_id, match_id)
);

CREATE TABLE Statistics (
    match_id VARCHAR(10) REFERENCES Matches(match_id),
    player_id VARCHAR(10) REFERENCES Players(player_id),
    runs INT,
    wickets INT,
    overs FLOAT,
    PRIMARY KEY (match_id, player_id)
);

INSERT INTO Teams (team_id, team_name, coach_name, home_ground)
VALUES
    ('t0001', 'MI', 'Ravi Shastri', 'Mumbai'),
    ('t0002', 'CSK', 'Justin Langer', 'Sydney'),
    ('t0003', 'KKR', 'Chris Silverwood', 'London');

INSERT INTO Players (player_id, player_name, age, position, team_id)
VALUES
    ('p0001', 'Virat Kohli', 33, 'Batsman', 't0001'),
    ('p0002', 'Rohit Sharma', 34, 'Batsman', 't0001'),
    ('p0003', 'Jasprit Bumrah', 27, 'Bowler', 't0001'),
    ('p0004', 'Steve Smith', 32, 'Batsman', 't0002'),
    ('p0005', 'Pat Cummins', 28, 'Bowler', 't0002'),
    ('p0006', 'Joe Root', 31, 'Batsman', 't0003');

INSERT INTO Matches (match_id, match_date, venue, team_1_id, team_2_id,winner_id)
VALUES
    ('m0001', '2022-03-15', 'Mumbai', 't0001', 't0002','t0001'),
    ('m0002', '2022-04-10', 'Sydney', 't0002', 't0003','t0003'),
    ('m0003', '2022-05-20', 'London', 't0001', 't0003','t0001');

INSERT INTO Plays_In (team_id, match_id)
VALUES
    ('t0001', 'm0001'),
    ('t0002', 'm0001'),
    ('t0002', 'm0002'),
    ('t0003', 'm0002'),
    ('t0001', 'm0003'),
    ('t0003', 'm0003');

INSERT INTO Statistics (match_id, player_id, runs, wickets, overs)
VALUES
    ('m0001', 'p0001', 120, 0, 0),
    ('m0001', 'p0003', 2, 3, 4.2),
    ('m0002', 'p0004', 60, 0, 0),
    ('m0002', 'p0005', 0, 2, 4.0),
    ('m0003', 'p0002', 80, 0, 0),
    ('m0003', 'p0006', 30, 1, 3.0);

#----query_6
INSERT INTO Players (player_id, player_name, age, position, team_id)
VALUES ('p0007', 'Faf du Plessis', 33, 'Batsman', 't0001');


#----query_7----
UPDATE Teams
SET coach_name = 'Kapil Dev'
WHERE team_id = 't0003';

#----query_8-----
DELETE FROM Statistics WHERE runs < 50;

#----query_9-----
INSERT INTO Matches (match_id, match_date, venue, team_1_id, team_2_id, winner_id)
VALUES ('m0004', '2022-03-18', 'Mumbai', 't0001', 't0003', 't0003');


#---query_10---
UPDATE Matches
SET winner_id = 't0002'
WHERE match_id = 'm0001';

#---query_11----
SELECT MAX(runs) AS highest_runs_scored
FROM Statistics;

#----query_12----
SELECT Teams.team_id, COUNT(matches.winner_id) AS wins
FROM Teams
LEFT JOIN matches ON Teams.team_id = Matches.winner_id
GROUP BY Teams.team_id;

#----query_13----
SELECT Teams.team_id, AVG(Players.age) AS average_age
FROM Teams
JOIN Players ON Teams.team_id = Players.team_id
GROUP BY Teams.team_id;

#-----query_14------
SELECT MAX(wickets) AS highest_wickets_taken
FROM statistics;


#------query_15----
SELECT teams.team_id, SUM(statistics.runs) AS total_runs
FROM Teams 
JOIN Players ON teams.team_id = players.team_id
JOIN Statistics ON players.player_id = statistics.player_id
GROUP BY teams.team_id
ORDER BY total_runs DESC
LIMIT 1;

#----query_16-----
SELECT player_name, position
FROM Players
WHERE team_id = 't0001'; -- replace 't0001' with the team_id you want to query

#----query_17----
SELECT DISTINCT Teams.team_id, Teams.team_name, Teams.coach_name
FROM Teams
JOIN Matches ON (Teams.team_id = Matches.winner_id)
WHERE (Matches.team_1_id = 't0001' OR Matches.team_2_id = 't0001')
AND Matches.winner_id <> 't0001';

#-----query_18-----
SELECT Matches.match_id, Matches.match_date, Matches.venue, Players.player_name
FROM Matches
JOIN Plays_In ON Matches.match_id = Plays_In.match_id
JOIN Players ON Plays_In.team_id = Players.team_id AND Players.player_id = 'p0004';

#-----query_19-----
SELECT DISTINCT Teams.team_id, Teams.team_name, Matches.match_date, Statistics.runs
FROM Teams
JOIN Matches ON Matches.team_1_id = Teams.team_id OR Matches.team_2_id = Teams.team_id
JOIN Plays_In ON Plays_In.match_id = Matches.match_id
JOIN Statistics ON Statistics.match_id = Plays_In.match_id AND Statistics.player_id = 'p0004'
WHERE Statistics.runs > 50;

#---query_20----
SELECT Players.player_name, Matches.match_date, Statistics.wickets
FROM Players
JOIN Statistics ON Statistics.player_id = Players.player_id
JOIN Matches ON Matches.match_id = Statistics.match_id
WHERE Statistics.wickets > 17 AND Statistics.runs > 17 AND Players.player_id = 'p0005';