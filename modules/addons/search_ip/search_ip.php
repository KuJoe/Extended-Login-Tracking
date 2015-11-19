<?php
/**
Records IP for client logins and makes them searchable for WHMCS by KuJoe (JMD.cc)
Ported from this addon here: http://www.whmcs.com/appstore/52/Search-IP.html

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
**/

if (!defined("WHMCS"))
	die("This file cannot be accessed directly");

function search_ip_config() {
    $configarray = array(
    "name" => "Search IP",
    "description" => "Search for IPs in WHMCS using login_tracking hook.",
    "version" => "1.1",
    "author" => "KuJoe",
    "language" => "english"
	);
    return $configarray;
}
	
function search_ip_activate() {

    # Return Result
    return array('status'=>'success','description'=>'Success!');
    return array('status'=>'error','description'=>'Failure!');
    return array('status'=>'info','description'=>'Search for IP addresses.');

}

function search_ip_deactivate() {

    # Return Result
    return array('status'=>'success','description'=>'Success!');
    return array('status'=>'error','description'=>'Failure!');
    return array('status'=>'info','description'=>'Search for IP addresses.');

}

function search_ip_output() {
	if ($_POST["ip"]) {
		foreach ($_POST["ip"] AS $clientid=>$ip) {
			$clientid = sanitize($clientid);
			$ip = sanitize($ip);
			$date = sanitize($date);
		}
	}
	$filtertype = sanitize($_POST["filtertype"]);
	$filtervalue = sanitize($_POST["filtervalue"]);
	echo '

<center><form method="post" action="'.$modulelink.'">
<p align="center">Search for IP Address that <select name="filtertype">
<option';
if ($filtertype=="starts with") { echo ' selected'; }
echo '>starts with</option>
<option';
if ($filtertype=="ends with") { echo ' selected'; }
echo '>ends with</option>
<option';
if ($filtertype=="contains") { echo ' selected'; }
echo '>contains</option>
</select> <input type="text" name="filtervalue" size="30" value="'.$filtervalue.'"> <input type="submit" value="Filter"></p>
<table width="50%" cellspacing="1" bgcolor="#cccccc"><tr bgcolor="#efefef" style="text-align:center;font-weight:bold;"><td>ID</td><td width="120">IP Address</td><td>Last Login</td></tr>
';

if ($filtervalue == NULL){
	
	echo '<tr bgcolor="#ffffff"><td colspan="3" align="center">Please start your search.</td></tr>';
	
	}else{

	$clientid="";
	$query = "SELECT * FROM mod_tbllogins";
	$query.= " WHERE ip";
	if ($filtertype=="starts with") {
		$query.= " LIKE '$filtervalue%'";
	} elseif ($filtertype=="ends with") {
		$query.= " LIKE '%$filtervalue'";
	} else {
		$query.= " LIKE '%$filtervalue%'";
	}
	$query.= " ORDER BY ip ASC"; 
	$result=mysql_query($query);
	while ($data = mysql_fetch_array($result)) {
		$clientid = $data["clientid"];
		$ip = $data["ip"];
		$lastlogin = $data["date"];
		
		echo '<tr style="text-align:center;" bgcolor="#ffffff"><td><a href="clientssummary.php?userid='.$clientid.'">'.$clientid.'</a></td><td>'.$ip.'</td><td>'.$lastlogin.'</td></tr>';
	}
	}
	if (!$clientid) {
		echo '<tr bgcolor="#ffffff"><td colspan="3" align="center">No Data Found</td></tr>';
	}
echo '
		</table>
		</form><br />
		<p>This module allows you to quickly search for IP addresses that may belong to clients.</p>
		
		<p><strong>Ported from <a href="http://www.whmcs.com/appstore/52/Search-IP.html">this addon</a> by Joe D.</strong></p></center>';
}
?>