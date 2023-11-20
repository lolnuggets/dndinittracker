# dndinittracker (DND Initiative Tracker)
Initiative Tracker for DND

# CONTENTS -
1- About<br>
2- Features<br>
3- Changelog<br>
4- Scripts<br>
5- External Resources Used

# ABOUT-------------------------

  This tool is used to track initiatives of players in the campaign "Legends of
Bria" The tool is intended to be used by every player in combat and the dm. It
currently supports only 1 battle. All passwords / character data/ battle data save
in a SQL database. The Hearts of Iron party emblem was AI generated additionally
some code was AI generated for convenience (i.e JS for large sections of HTML I
didn't want to write that code as it would have been very tedious). This is a
personal project created and maintained by me, no other people have helped in the
coding.

This website is currently being hosted at [www.dndhoi.com](www.dndhoi.com)

# CHANGELOG---------------------

## [1.1.0] - 11-20-2023
  
Attempting to refactor all code to limit PHP usage and swap to JS for flexibility. Other small QOL changes.

### Added

- [scripts](/scripts)<br>
  Created create-cards.js intended to create cards on character page and index page

### Changed
  
- [playerview/index.php](/playerview/index.php)<br>
  Added a color gradient to player status

- [scripts](/scripts)<br>
  Replaced loadcharcards with new php get-players returns JSON for ajaxing
 
### Fixed
 
- [scripts](/scripts)<br>
  signout redirecting incorrectly/not relative page wise in script verifylogged.php

### Removed
 
- [scripts](/scripts)<br>
  signout.php

- [scripts](/scripts)<br>
  loadcharcards.php

## [1.0.0] - 11-15-2023
  
Initial release of the project, all pages, scripts, resources created.

### Added

- [index.php](/index.php)<br>
  Created as home page

- [characters/index.php](/characters/index.php)<br>
  Created as character creation/selection page

- [info/index.php)](/info/index.php)<br>
  Created as character view page
  
- [playerview/index.php](/playerview/index.php)<br>
  Created as frontend
  
- [dmview/index.php](/dmview/index.php)<br>
  Created as backend

- [login/index.php](/login/index.php)<br>
  Created as login page

- [scripts](/scripts)<br>
  Created and saturated with all nessecary scripts
  
 
### Changed
  
- [scripts](/scripts)<br>
  Changed the db.php script and updated the database.
 
### Fixed
 
- All pages<br>
  Corrected favicon.ico location and index.php
- All pages<br>
  Edited signout to redirect to correct page

# FEATURES----------------------

#### index.php
1. View all created characters
2. Go to playerview or dmview through js buttons
3. Blocks a user with invalid perms to going to dmview

#### characters/index.php
1. Select a character through the viewing cards from home page
2. Select/deselect a player with a dropdown
3. Create a character

#### info/index.php
1. View your current player/party stats
2. Edit current player stats/delete player stats
3. DM has a different view; can select and player
   they want. This swaps their view to something
   akin to the player view with all the same
   features

#### playerview/index.php
1. Displays battle status/if there is a battle
2. Join with any selected initiative and hp
3. Auto sorting of all players/creatures by initiative
4. Secondary sorting by a 'priority' system which is
   manually manipulated by players for use in 'rollsies'
5. Displayed stats as follows: name, status, hp, ac, initiative
   and relationship to the party
6. Enemy HP and AC will not be shown to player
7. Party member HP will not be shown to the player
8. Spectators are a user with no character selected, they will be
   able to see party member HP accurately
9. Drop down on click to display additional stats
10. Additional stats as follows: Innate stats (max hp and level),
    passive stats (passive perception etc.), and current active
    conditions (poisoned, stunned etc.)
11. Ability to edit currently selected character battle stats
    like armor class, hp, or initiative
12. Ability to do battle actions on currently selected character
    like dealing or healing damage, +/- prio, setting conditions,
    and ending current turn
13. Party members show innate, passives, and conditions but do not
    allow any action to be taken to that character
14. Enemies only show their currently active conditions on their dropdown

#### dmview/index.php
1. All features included in playerview EXCEPT joining/leaving battle
2. Can do battle actions and edit battle stats on every creature/player
3. Any inserted creation can have the 'hidden' attribute which hides them
   from the players and skips their turn
4. Inserting a custom creature following party member creation guideline
5. Insert from dnd 5e api allowing setting some stats (max hp, cur hp, etc.)
6. Suggested fill in for creature type in api insertion
7. Remove a player/creature from combat
8. Set a creature to hidden
9. Set a creatures relationship to the party
10. Go next turn or previous turn
11. Start, pause, and clear battle
12. Force a player from the party into battle
13. Allows taking notes on creatures
14. Shows stat block for any creature inserted from the dnd5e api

#### login/index.php
1. Checks password is valid relative to selected perm
2. Does not set cookies if password is invalid
3. Sets if valid and redirects

# SCRIPTS-----------------------

### db.php
Used to initialize the database to edit

### updatechar.php
Form posted to in order to edit character stats, posted from the info page

### loadcharview.php
Used to load the character view on the info page to display current stats and
party stats

### apijoin.php
Used to add a creature from the dnd 5e api to the current battle

### dmcontrols.php
Posted to from dmview for the controls to be valid. It takes whatever button
is set and does the action requested by that button

### resetnpc.php
Resets the current npc stat table

### joinbattle.php
Posted to from in order to add a player to the battle or remove a player from
a battle

### battleupdatechar.php
Updates a characters battle stats. Accessed from the playerview page

### editorajax.js
Uses ajax to get player info in order to display that to the dm on the info
page

### dralist.js
Draws the initiative list for the players and attaches dropdown creation event
on click to all entries

### apifillin.js
Allows the dm to have autofill results for utilizing the dnd 5e api

### dropdownnpc.php
Posted to from a javascript and returns npc data to be displayed in a dropdown

### getnotes.php
Gotten from a javascript and returns the current notes. Not widely used could
be simplified and completely removed

### tempnamelist.php
Returns a list of names broken by a common character for use in displaying
them on dmview and playeview

### get-info.php
Returns the info of all players. For use in (?) and likely to display player
in maybe drawlist?

### setnotes.php
Sets the notes of a creature

### getlastplayer.php
Sets the player who was last to go to variable $lastplayer for use in
calculating turn order/current turn actions

### getcurrentturn.php
Sets the player who is currently going as $currentplayer for use in turn order
actions etc.

### resetconds.php
Resets conditions of all creatures/players

### resettemp.php
Resets all battle stats (located in tempinfo table) for all creatures/ players

### updatecondition.php
Updates the condition of a given creature/player

### dropdownplayer.php
Returns all nessecary information to saturate a dropdown for a player

### dropdownhandler.js
Adds dropdowns to all initiative items and handles the dropdown actions

### addcharacter.php
Adds a character to the character storage area for the party

### login.php
Cross checks the passwords with the database and allows entry and sets player
perm

### genchar.php
Generates a dropdown of all players currently in battle

### generate-char-selector.php
Generates a dropdown of all players

### loadbattle.php
Returns a string of all entries in a battle seperated by a common character
including basic battle stats

### loadcharcards.php
Generates all character cards on the home page and the character page and lets
you select in the character page but no home page

### getchars.php
Returns a list of players seperated by a common character

### dmloadeditor.php
Returns a list of all players in the database and stats shown to players in the
info page

### loadjoinbattle.php
Creates a form to join a battle from for the players and for spectators it says
select a player

### selectchar.php
Selects a player based on posted select value or deselects player if no value
is posted

### signout.php
Handles signout and clears cookies

### verifylogged.php
Present on every page and makes sure the user is logged in with a valid account
and valid perms

# EXTERNAL-RESOURCES-USED-------

### XAAMP
Used in creating a local sql database and creating a local serverside website

### MYSQLWORKBENCH
Used in viewing and editing sql database

### SUBLIME
Used in viewing and editing code for the website

### OPERA GX
Used in viewing website and researching solutions

### DND 5E API ([NO ACCOUNT NEEDED](https://www.dnd5eapi.co))
Used for generating custom stat block for api creatures

### MIDJOURNEY ([DISCORD ACCOUNT](https://www.midjourney.com/home?callbackUrl=%2Fexplore))
Used in resource creation

### GODADDY ([gpy62tzb26pu6](https://sso.godaddy.com/?realm=idp&app=cart&path=/checkoutapi/v1/redirects/login))
Used in aquiring domain dndhoi.com

### HEROKU ([bhnzwmvhq1gu5](https://id.heroku.com/login))
Used in hosting of website

### AMAZON AWS ([3ve3tf18fxjjm](https://signin.aws.amazon.com/signin?redirect_uri=https%3A%2F%2Fconsole.aws.amazon.com%2Fconsole%2Fhome%3FhashArgs%3D%2523%26isauthcode%3Dtrue%26nc2%3Dh_ct%26src%3Dheader-signin%26state%3DhashArgsFromTB_us-east-2_e662f0077debafcf&client_id=arn%3Aaws%3Asignin%3A%3A%3Aconsole%2Fcanvas&forceMobileApp=0&code_challenge=sdC1gfMfvpZrwaP5zbRiCG2aKM8aMSrFkPCUYfjWvvM&code_challenge_method=SHA-256))
Used in hosting database
