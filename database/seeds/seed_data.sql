-- Insert sample data into the teams table
INSERT INTO teams (name, founded_year, captain, ranking) VALUES
('Team A', 1995, 'John Doe', 1),
('Team B', 2000, 'Jane Smith', 2),
('Team C', 1985, 'Alan Brown', 3),
('Team D', 2010, 'Emma White', 4);

-- Insert sample data into the players table
INSERT INTO players (name, age, team_id, role, matches_played, runs_scored, wickets_taken) VALUES
('Player 1', 30, 1, 'Batsman', 50, 1200, 10),
('Player 2', 28, 1, 'Bowler', 40, 300, 25),
('Player 3', 25, 2, 'All-rounder', 35, 600, 15),
('Player 4', 32, 2, 'Wicketkeeper', 45, 800, 5),
('Player 5', 27, 3, 'Batsman', 50, 1000, 5),
('Player 6', 24, 3, 'Bowler', 30, 150, 30),
('Player 7', 29, 4, 'Batsman', 20, 400, 0),
('Player 8', 26, 4, 'Bowler', 25, 200, 20);

-- Insert sample data into the matches table
INSERT INTO matches (team1_id, team2_id, match_date, venue, result) VALUES
(1, 2, '2024-10-10', 'Stadium A', 'Team A Win'),
(3, 4, '2024-10-12', 'Stadium B', 'Draw'),
(1, 3, '2024-10-15', 'Stadium C', 'Team A Win'),
(2, 4, '2024-10-20', 'Stadium D', 'No Result');

-- Insert sample data into the match_results table
INSERT INTO match_results (match_id, player_id, runs_scored, wickets_taken) VALUES
(1, 1, 100, 0),
(1, 2, 30, 2),
(2, 3, 75, 1),
(2, 4, 50, 0),
(3, 1, 80, 0),
(3, 5, 40, 1),
(4, 6, 60, 3),
(4, 7, 20, 0);
