<?php
$current_date = date('Y-m-d H:i:s');
$body = "Dear User,<br />
This is a confirmation that the password for your account has been changed successfully.<br />
* Date & Time: " . $current_date . "<br />
* Account: {EMAIL_OR_USERNAME}<br />
If you made this change, no further action is required.<br />
If you did not change your password, please verify your account and secure your account as soon as possible.<br />
For your security, we recommend:<br />
* Using a strong and unique password.<br />
* Never sharing your login credentials with anyone.<br />
* Updating your password regularly.<br />";