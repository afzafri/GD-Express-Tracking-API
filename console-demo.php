<?php

require 'vendor/autoload.php';

use Afzafri\GDExpressTrackingApi;

if (isset($argv[1])) {
	print_r(GDExpressTrackingApi::crawl($argv[1]));
} else {
	echo "Usage: " . $argv[0] . " <Tracking code>\n";
}