[**Create your own Football Manager in PHP and MySQL**
](https://www.udemy.com/course/create-your-own-football-manager-in-php-and-mysql/learn/lecture/22442302?start=0#overview)

## Terms
* User: The person playing the game
* Manager: Represents the user online
* Player: Member of football team
* Team: One senior and one youth per user consisting of 14-15 players
* Trainer: One or more trainer per team. Player is allocated a trainer
* Match: Single competition with two teams. Lasting 2 x 45 minutes. May be friendly or part os league
* League: Series of games where a set of teams is playing all other teams in the league twise. One home match and one away match
* Sponsor: Entity providing founds for the teams

# Section 1: Introduction
## 1. My course, Your Football Manager!

* Module for logging in
* User is getting teams with 14-15 players
* Need to create players for users
  * Player cost
  * Player salary
* Minute by minute view of game
* Players can be trained
* Allocate sponsor
* Allocate player number
* Need to hire trainer
  * Trainer cost
  * Trainer samary
* Players has different qualities
  * This makes them attractive in different positions
* Match result is calculated from each player qualities and some random factors.

# Section 2: All resources from the game
## 2. New user, first login, features of the game
## 3. Bot teams, league, first round
## 4. Admin section, useful things, resources
## 5. Add access level for the files in administration

Tables:
* Module: superadmin, admin, helper
* Usermodule: withch user have which rights<br>Mapps user with user module

Each page checks this against logged in user
  

# Section 3: Users - Creating the Class
## 6. Table and class defined

Database: myFM
Tables:
* User
* Stadium
* Country

# Section 4: Players
## 7. Create the class and the rables

Tables:
* Player
* Userplayer
* Firstname
* Lastname

## 8. Create players - see it in action

Tables:
* Leap
* Lineup
* Percentage
* Traininer
* Trainerplaer
* Vmaxpos

# Section 5: Addins a template
## 9. Download and change the template

* [colorlib](colorlib.com)
* [AZNews](https://colorlib.com/wp/template/aznews/)

## 10. Adding log on and log off

# Section 6: Training Module
## 11. Training file - explaining the idea

training.php

## 12. Executing the training

# Section 7. Player
## 13. Displaying the player

## 14. Player - defending, midfielding and attacking values

# Section 8: Bot-Team
## 15. Create bot-team, part I


## 16. Create bot-team, part II

# Section 9: Generate the Game
## 17. Tables involved

genGame.php

Tables:
* Gameinvitation
* Gametext
* Gamedetail
* Tplemessages

## 18. See it in action
## 19. More explanations

ComputeTheScore()

## 20. Goals scored by each team
## 21. Generate the plays of the game
## 22. Morale and number of spectators
## 23. Game is online, Live!
# Section 10: News Section
## 24. Table and module, in administration panel
# Section 11: Resources
## 25. Resources for aznews template, modified so far
# Section 12: Moving on with the initial layout
## 26. Initial layout
## 27. Add numbers to the t-shirts
## 28. Increase stadium
## 29. Generate the league

Tables:
* Competition
* Clasament / Standings
* Competitionstatus
* Gameinvitation

## 30. Estimate the score!

Ask for Estimation!
* estimateScore.php
  
## 31. Estimate the score, part II