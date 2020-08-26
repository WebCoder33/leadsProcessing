<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'vendor/autoload.php';

use App\Controller\StartController;
use LeadGenerator\Generator;
use LeadGenerator\Lead;
use App\Services\LeadsProcessing;


$start = microtime(true);

$generator = new Generator();
$startController = new StartController(new LeadsProcessing());

$leadsArray = [];
$generator->generateLeads(10000, function (Lead $lead) {
    global $leadsArray;
    $leadsArray[] = $lead;
});

$startController->start($leadsArray);

$end = microtime(true);
$time = number_format(($end - $start), 2);
echo 'This page loaded in ', $time, ' seconds';