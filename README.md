# Cricket DBMS

## Overview
Cricket DBMS is a web application designed to manage player statistics, team rosters, and match schedules for cricket teams. Built using HTML, PHP, and MySQL, this application provides an intuitive interface for users to view and manage essential cricket data efficiently.

## Features
- **Player Management:** Create, read, update, and delete player profiles. View detailed statistics for each player.
- **Team Management:** Manage team rosters, including adding and removing players.
- **Match Scheduling:** Schedule matches and manage match results.
- **Dynamic Interfaces:** Interactive forms and interfaces for seamless data management.
- **Ranking System:** Automatically calculate and display team rankings based on match results.

## Technologies Used
- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL
- **Testing:** PHPUnit

## Installation

### Prerequisites
- PHP (version >= 7.0)
- MySQL (version >= 5.6)
- Composer (for dependency management)

### Steps to Install
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/cricket-dbms.git

2. Navigate to repository
```bash
cd cricket-dbms
```

3. Install dependencies using Composer
```bash
composer install
```

4. Configure the database:

-Create a new MySQL database for the project.
-Import the create_tables.sql file located in database/migrations to set up the necessary tables.
-Edit the config/database.php file to add your database credentials.

5. Set up the server - either local server (XAMPP or MAMP), or configure your own Apache server.

6. Open your web browser and go to http://localhost/cricket-dbms/public.

Usage
Homepage: Navigate to the homepage to access player profiles, team rosters, and match schedules.
Manage Players: Use the player management interface to add or edit player information.
Manage Teams: View and modify team rosters.
Match Results: Schedule matches and input match results to see team rankings

7. Unit tests are included in the tests/Unit directory. Run the tests using PHPUnit:
```bash
vendor/bin/phpunit tests/Unit
```

