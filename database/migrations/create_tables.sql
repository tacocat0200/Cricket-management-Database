-- Create Teams table
CREATE TABLE IF NOT EXISTS teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    founded_year INT,
    captain VARCHAR(100),
    ranking INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Players table
CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT,
    team_id INT,
    role ENUM('Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper'),
    matches_played INT DEFAULT 0,
    runs_scored INT DEFAULT 0,
    wickets_taken INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE SET NULL
);

-- Create Matches table
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team1_id INT,
    team2_id INT,
    match_date DATE,
    venue VARCHAR(100),
    result ENUM('Team 1 Win', 'Team 2 Win', 'Draw', 'No Result'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (team1_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (team2_id) REFERENCES teams(id) ON DELETE CASCADE
);

-- Create Match Results table
CREATE TABLE IF NOT EXISTS match_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT,
    player_id INT,
    runs_scored INT DEFAULT 0,
    wickets_taken INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);
