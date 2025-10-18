# Football World Cup Score Board

A simple **command-line** application built with **Symfony** and **PHP 8.1+** to manage and display live football (soccer) scores.  
The project demonstrates clean architecture, SOLID principles, and test-driven design — **without REST APIs or web services**, as per the specification.

---

### Features

- Start a new game between two teams
- Update scores for ongoing games
- Finish a game and remove it from the active scoreboard
- View a **summary** of all active games
    - Sorted by **total score (descending)**
    - If totals are equal → by **most recently added game first**
- Persistent storage of games in a local JSON file (`var/score_board.json`)
- Fully testable business logic (no external dependencies)

---

### Requirements

- **PHP** ≥ 8.1
- **Composer** for dependency management
- **Symfony CLI** *(optional, for convenience)*

---

### Installation

```bash
git clone https://github.com/sedonik/score_board.git
cd score_board
composer install
mkdir -p var
chmod -R 777 var
```

---

### Usage

All commands are run through the Symfony Console.

#### 1. Start a Game
```bash
bin/console app:scoreboard-start "Home Team" "Away Team"
# Example:
bin/console app:scoreboard-start "Mexico" "Canada"
```

#### 2. Update a Game Score
```bash
bin/console app:scoreboard-update "Home Team" "Away Team" <home-score> <away-score>
# Example:
bin/console app:scoreboard-update "Mexico" "Canada" 0 5
```

#### 3. Finish a Game
```bash
bin/console app:scoreboard-finish "Home Team" "Away Team"
# Example:
bin/console app:scoreboard-finish "Mexico" "Canada"
```

#### 4. View Summary
```bash
bin/console app:scoreboard-summary
```
Displays active games ordered by:
- **Total score** (descending)
- **Recently added first**, if totals are equal.

#### 5. Show Detailed Game Information
```bash
bin/console app:scoreboard-matches
```

##### Example Workflow

```bash
bin/console app:scoreboard-start "Mexico" "Canada"
bin/console app:scoreboard-update "Mexico" "Canada" 0 5

bin/console app:scoreboard-start "Spain" "Brazil"
bin/console app:scoreboard-update "Spain" "Brazil" 10 2

bin/console app:scoreboard-start "Germany" "France"
bin/console app:scoreboard-update "Germany" "France" 2 2

bin/console app:scoreboard-start "Uruguay" "Italy"
bin/console app:scoreboard-update "Uruguay" "Italy" 6 6

bin/console app:scoreboard-start "Argentina" "Australia"
bin/console app:scoreboard-update "Argentina" "Australia" 3 1

# View summary
bin/console app:scoreboard-summary
```

**Detailed View:**
```
Current Matches
===============

1. Mexico - Canada: 0 - 5
2. Spain - Brazil: 10 - 2
3. Germany - France: 2 - 2
4. Uruguay - Italy: 6 - 6
5. Argentina - Australia: 3 - 1

```

**Expected Output:**
```
Scoreboard Summary
==================

1. Uruguay 6 - Italy 6
2. Spain 10 - Brazil 2
3. Mexico 0 - Canada 5
4. Argentina 3 - Australia 1
5. Germany 2 - France 2

```

---

### Project Structure

```
src/
 ├── Command/ScoreBoard/
 │   ├── StartCommand.php
 │   ├── UpdateCommand.php
 │   ├── FinishCommand.php
 │   ├── SummaryCommand.php
 │   └── MatchesCommand.php
 ├── Entity/
 │   └── Game.php
 ├── Service/
 │   ├── ScoreBoard.php
 │   ├── JsonScoreBoardRepository.php
 │   └── ScoreBoardRepositoryInterface.php
tests/
 └── Command/ScoreBoard/
     ├── StartCommandTest.php
     ├── UpdateCommandTest.php
     └── SummaryCommandTest.php
var/
 └── score.json
```

---

### Testing

Run unit tests via PHPUnit:

```bash
./vendor/bin/phpunit tests/Command/ScoreBoard/
```

**Test coverage includes:**
- Start command — valid start, duplicate game, invalid teams
- Update command — successful updates, invalid scores, non-existent games
- Summary command — correct ordering and tie-breaking
- Repository — persistence and data integrity tests
