<?php

namespace Afzafri;

class GDExpressTrackingApi
{
    public static function crawl($trackingNo, $include_info = false)
    {
        $url = "https://esvr3.gdexpress.com/SOTS_Integrated/api/services/app/eTracker/GetListByCnNumber?input=" . $trackingNo; # url of gdex tracking website
        # store post data into array (skynet website only receive the tracking no with POST, not GET. So we need to POST data)
        $postdata = http_build_query(array(
            'input' => $trackingNo,
        ));

        # use cURL instead of file_get_contents(), this is because on some server, file_get_contents() cannot be used
        # cURL also have more options and customizable
        $ch = curl_init(); # initialize curl object
        curl_setopt($ch, CURLOPT_URL, $url); # set url
        curl_setopt($ch, CURLOPT_POST, 1); # set option for POST data
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); # set post data array
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
	curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
        $result = curl_exec($ch); # execute curl, fetch webpage content
        $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
        $errormsg = (curl_error($ch)) ? curl_error($ch) : "No error"; # catch error message
        curl_close($ch); # close curl
        # array for keeping the data
        $trackres = array();
        $trackres['http_code'] = $httpstatus; # set http response code into the array
        $trackres['error_msg'] = $errormsg; # set error message into array
        $data = json_decode($result, true);
        $list = $data['result'][0]['listPodData'];

        if ($list != null)
        {
        	$trackres['status'] = 1;
            $trackres['message'] = "Record Found";

            for ($i = 0;$i < count($list);$i++)
            {

                // ----- MAPPING -----
                $prob_code = $list[$i]['problem_code'];

                // process type
                if ($list[$i]['type'] == "pod" || $list[$i]['type'] == "m_pod")
                {
                    if ($prob_code == "")
                    {
                        $list[$i]['type'] = "Delivered";
                    }
                    else if ($prob_code == "00")
                    {
                        $list[$i]['type'] = "Delivered to Consignee";
                    }
                    else if ($prob_code == "P1")
                    {
                        $list[$i]['type'] = "Delivered to Smart Locker";
                    }
                    else
                    {
                        $list[$i]['type'] == $list[$i]['problem_code'];
                    }
                }
                else if ($list[$i]['type'] == "i_pod")
                {
                    $list[$i]['type'] = "Under Claim";
                }
                else if ($list[$i]['type'] == "undl" || $list[$i]['type'] == "m_undl")
                {
                    $list[$i]['type'] = "Undelivered due to " . $list[$i]['problem_code'];
                }
                else if ($list[$i]['type'] == "rts" || $list[$i]['type'] == "m_rts")
                {
                    $list[$i]['type'] = "Returned to shipper";
                }
                else if ($list[$i]['type'] == "I")
                {
                    $list[$i]['type'] = "Picked up by courier";
                }
                else if ($list[$i]['type'] == "P")
                {
                    $list[$i]['type'] = "In Packing";
                }
                else if ($list[$i]['type'] == "M" && $list[$i]['origin_defi'] == "Warehouse")
                {
                    $list[$i]['type'] = "Outbound to HUB";
                }
                else if ($list[$i]['type'] == "M")
                {
                    $list[$i]['type'] = "Outbound from " . $list[$i]['origin'] . " station";
                }
                else if ($list[$i]['type'] == "H")
                {
                    $list[$i]['type'] = "In transit";
                }
                else if ($list[$i]['type'] == "R")
                {
                    $list[$i]['type'] = "Inbound to " . $list[$i]['origin'] . " station";
                }
                else
                {
                    $list[$i]['type'] = "Out for delivery";
                }

                // location
                if ($list[$i]['type'] == "H")
                {
                    if ($list[$i]['origin'] == "HUB")
                    {
                        $list[$i]['origin_defi'] = "Petaling Jaya";
                    }
                }
                else if ($list[$i]['origin'] == "int")
                {
                    if ($list[$i]['desc'] == "")
                    {
                        $list[$i]['origin_defi'] = $list[$i]['origin_defi'];
                    }
                    else
                    {
                        $list[$i]['origin_defi'] = $list[$i]['desc'];
                    }
                }
                else if ($list[$i]['origin'] == "HBN")
                {
                    $list[$i]['origin_defi'] = "Butterworth";
                }
                else if ($list[$i]['origin_defi'] == "Warehouse")
                {
                    $list[$i]['origin_defi'] = "Warehouse";
                }
                else
                {
                    if ($list[$i]['origin_defi'] == "")
                    {
                        $list[$i]['origin_defi'] = "Petaling Jaya";
                    }
                    if (substr($list[$i]['origin'], 0, 2) == "KW")
                    {
                        $list[$i]['origin_defi'] = "Petaling Jaya";
                    }
                }

                # store into associative array
                $trackres['data'][$i]['date_time'] = $list[$i]['dtScan'];
                $trackres['data'][$i]['status'] = $list[$i]['type'];
                $trackres['data'][$i]['location'] = $list[$i]['origin_defi'];
            }
        }
        else
        {
        	$trackres['status'] = 0;
            $trackres['message'] = "No Record Found";
        }

        if ($include_info) {
		    $trackres['info']['creator'] = "Afif Zafri (afzafri)";
		    $trackres['info']['project_page'] = "https://github.com/afzafri/GD-Express-Tracking-API";
		    $trackres['info']['date_updated'] = "16/01/2020";
        }

        return $trackres;

    }
}
    
