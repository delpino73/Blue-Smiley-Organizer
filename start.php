<?php

if (isset($_COOKIE['sid'])) {

echo '<html><head><title>Organizer</title></head><frameset rows="80,*,50" frameborder=0 border=0><frame name="top_frame" src="top-frame.php" marginwidth="0" marginheight="0" frameborder="0" scrolling="0"><frame name="main" src="home.php" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0"><frame name="bottom_frame" src="bottom-frame.php" marginwidth="0" marginheight="0" frameborder="0" scrolling="no"></frameset>';

}

else { echo 'No Session ID found, please <a href="login.php">click here</a> to login. Make sure cookies are not disabled in your browser.'; exit; }

?>