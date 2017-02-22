<?php

/*  GD Express (GDEX) Tracking API created by Afif Zafri.
    Tracking details are fetched directly from GDEX tracking website,
    parse the content, and return JSON formatted string.
    Please note that this is not the official API, this is actually just a "hack",
    or workaround for implementing tracking feature in other project.
    Usage: http://site.com/api.php?trackingNo=CODE , where CODE is your tracking number
*/

header("Access-Control-Allow-Origin: *"); # enable CORS

if(isset($_GET['trackingNo']))
{
	$trackingNo = $_GET['trackingNo']; 

    $url = "http://1.9.8.166/official/iframe/etracking2.php"; # url of gdex tracking website

    # data for POST request
    $postdata = http_build_query(
        array(
            'capture' => $trackingNo,
            'redoc_gdex' => 'cnGdex',
            'Submit' => 'Track',
        )
    );

    # use cURL instead of file_get_contents(), this is because on some server, file_get_contents() cannot be used
    # cURL also have more options and customizable
    $ch = curl_init(); # initialize curl object
    curl_setopt($ch, CURLOPT_URL, $url); # set url
    curl_setopt($ch, CURLOPT_POST, 1); # set option for POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); # set post data array
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
    $result = curl_exec($ch); # execute curl, fetch webpage content
    $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
    curl_close($ch);  # close curl

    # regex patern. to get the table result
    $patern = "#<table class='content1' cellpadding='1' cellspacing='0'  style=\"border: thin solid \#acacac;\" id='products'>
([\w\W]*?)</table>#"; 
    # execute regex
    preg_match_all($patern, $result, $parsed);  

    # parse the table by row <tr>
    $trpatern = "#<tr(.*?)</tr>#";
    preg_match_all($trpatern, implode('', $parsed[0]), $tr);

    # checking if record found or not, by checking the number of rows available in the result table
    if(count($tr[0]) > 0)
    {
        for($i=0;$i<count($tr[0]);$i++)
        {
            # parse the table by column <td>
            $tdpatern = "#<td>(.*?)</td>#";
            preg_match_all($tdpatern, $tr[0][$i], $td);
            
            # fetch and store data in array
            $datetime = strip_tags($td[0][1]);
            $status = strip_tags($td[0][2]);
            $location = strip_tags($td[0][3]);

            echo "<br>Date &amp; Time: ".$datetime;
            echo "<br>Status: ".$status;
            echo "<br>Location: ".$location;
            echo "<br><br>";
        }
    }
}

?>