<?php

include_once "ScanFile.php";

CONST NEW_LINE = "\n";
CONST RED_TEXT = "";//"\033[31m";
CONST WHITE_TEXT = "";//"\033[0m";
CONST BLUE_TEXT = "";//"\033[34m";
CONST PURPLE_TEXT = "";//"\033[35m";
CONST GREEN_TEXT = "";//"\033[32m";

$path = ".";
$ext = "*";
$deep = -1;
$preview = 100;

$opt = getopt("p::e::d::r::");

if (isset($opt["p"]) && !empty($opt["p"])) {
    $path = $opt["p"];
}

if (isset($opt["e"]) && !empty($opt["e"])) {
    $ext = $opt["e"];
}

if (isset($opt["d"]) && (!empty($opt["d"]) || $opt["d"] === "0")) {
    $deep = $opt["d"];
}

if (isset($opt["r"]) && (!empty($opt["r"]) || $opt["r"] === "0")) {
    $preview = $opt["r"];
}

echo "Scanning directory: " . BLUE_TEXT . $path . WHITE_TEXT . NEW_LINE;
echo "Scan extension file: " . BLUE_TEXT . $ext . WHITE_TEXT . NEW_LINE;
echo "Directory deep: " . BLUE_TEXT . ($deep < 0 ? "Infinite" : $deep) . WHITE_TEXT . NEW_LINE;

$startTime = new DateTime();
echo PURPLE_TEXT . "Start " . RED_TEXT . $startTime->format("Y/m/d H:i:s") . WHITE_TEXT . NEW_LINE;

$scanFile = new ScanFile($path, $ext, $deep, $preview);

$scanFile->process();

$scanFile->describe_file();
$scanFile->get_highest_count_file();


$endTime = new DateTime();
echo PURPLE_TEXT . "End " . RED_TEXT . $endTime->format("Y/m/d H:i:s") . WHITE_TEXT . NEW_LINE;

$timeElapsed = date_diff($startTime, $endTime);

echo PURPLE_TEXT . "Total file processed: " . RED_TEXT . $scanFile->total_file . WHITE_TEXT . NEW_LINE;
echo GREEN_TEXT . "Time elapsed: " . ((((($timeElapsed->y * 365.25 + $timeElapsed->m * 30 + $timeElapsed->d) * 24 + $timeElapsed->h) * 60 + $timeElapsed->i)*60 + $timeElapsed->s) + $timeElapsed->f) . WHITE_TEXT . NEW_LINE;