# MinecraftAdvancements

This small PHP script creates a nice overview about all the advancements your players achieved, and for missing multi-achievements (like eat all food or tame all cats) it shows the still missing items to get the achievement.

Install:

* Put php file in your webroot
* Edit php file, set $MCDIR to your minecraft server directory
* Grant the user running the webserver (usually apache or httpd) read access to your minecraft server directory
* unzip server.jar in your minecraft/cache-directory (needed to get a list of possible achievements and their requirements)
