# BurningDiscs
A simple Music-Indexer working with the Anonfiles API. Create Discs, upload them and share them with others. Create an Account, comment on Discs and favourite them. Made with much &lt;3 from a Music-lover. Check out the Official Site -> https://burningdiscs.h33t.moe

# Setup
Download the files and put them into the Directory you want to have them.

Import "bd-rocks.sql" into a MySQL Database of your choice.

Edit "config.php" to your likings, I made some explanations in the file itself.

After that, you can Login to the site via "admin:admin" (username:password) but I recommend creating a new account and deleting the admin account.

Then go to the MySQL Database (via phpMyAdmin), enter the 'users' table and change the 'admin' from 0 to 1 (if you created a new account).

For uploading ZIPs you need a Anonfiles API key. Instructions will show on the site if you try to create a new Disc without having one entered.

If you still have any questions, create an Issue.

# Functions

- Add Disc
- View Disc
- Download File
- Login
- Signup
- Search
- Comment

# Credits
Please credit the People who made this software properly by NOT removing the footer. I know, it doesn't look very nice but at least it's good.

THe code was written completely by "Saintly2k" (https://github.com/saintly2k).

The style used is "Bootstrap v3.4.2" (https://getbootstrap.com/docs/3.4).

The "favicon.ico" was created by "Freepik" (https://www.freepik.com).
