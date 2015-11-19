<?php
/**
Records IP for client logins and makes them searchable for WHMCS by KuJoe (JMD.cc)
Slightly edited from Aldryic C'boas initial hook here: https://vpsboard.com/topic/134-whmcs-extended-login-tracking/

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
**/

function track_logins($vars) {
	insert_query("mod_tbllogins", array("clientid"=>$vars['userid'], "ip"=>$_SERVER['REMOTE_ADDR']));
   
	// Trim logins to 30 latest (couldn't get the SQL Helper to work on this DELETE query)
	mysql_query("DELETE FROM mod_tbllogins WHERE clientid = '". $vars['userid'] ."' AND date NOT IN (SELECT * FROM (SELECT date FROM mod_tbllogins WHERE clientid = '". $vars['userid'] ."' ORDER BY date DESC LIMIT 30) alias)");
}

add_hook("ClientLogin",1,"track_logins");
?>