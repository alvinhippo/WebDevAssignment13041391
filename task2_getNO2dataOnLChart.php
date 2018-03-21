<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * Receiving values from js 
 */
$stName = $_GET["stName"];
$time = $_GET["time"];
$selectedDate = $_GET["date"];


//Method for getting the next day of selected day from client
$oriDay = date('m/d/Y', strtotime($selectedDate));
$nextDay = date("m/d/Y", strtotime($oriDay . ' +1 day'));

$stringSelectedDay = explode("/", $oriDay);
$stringNextDay = explode("/", $nextDay);

$stringSelectedDay = "$stringSelectedDay[1]/$stringSelectedDay[0]/$stringSelectedDay[2]";
$stringNextDay = "$stringNextDay[1]/$stringNextDay[0]/$stringNextDay[2]";

//Starts converting XML elements into JSON
$xmlFile = simplexml_load_file("resources/xml/" . $stName . "_no2.xml");
$array = $xmlFile->xpath("//reading["
        . "(@date='$stringSelectedDay' and translate(@time, ':', '') >= translate('$time', ':', '')) or"
        . "(@date='$stringNextDay' and translate(@time, ':', '') <= translate('$time', ':', ''))]");

//Needs sorting the order of data by date and time, otherwise, faults will generate inside the graph
usort($array, 'sortDataByDateTime');

$rows = array();
$cols = array();

/* this is one of the way to convert specfic XML data into JSON format to apply on Google Chart Datatable:
 * https://groups.google.com/forum/#!msg/google-visualization-api/Ncg-2tCEmlE/5KunvAL7JQ4J
 *  */
//table with headers
$table = array(
    'cols' => array(
        array("label" => "Date&Time", "type" => "date"),
        array("label" => "NO2", "type" => "number")
    ),
    'rows' => array()
);


foreach ($array as $val) {
    $record = simplexml_load_string($val->asXML());
    $no2level = $record->attributes()->val;

    $date = DateTime::createFromFormat("d/m/Y H:i:s", ($record->attributes()->date . " " . $record->attributes()->time));

    /* Google chart needs date in a specific format: 
     * https://developers.google.com/chart/interactive/docs/datesandtimes
     * Format : "Date(Year, Month, Day, Hours, Minutes, Seconds, Milliseconds)"
     * which month starts with value of 0
     */

    $dateJSON = "Date(";
    $dateJSON .= date("Y, ", $date->format("U"));
    $dateJSON .= date("m", $date->format("U")) - 1 . ", ";
    $dateJSON .= date("d, H, i, s", $date->format("U")) . ")";

    $rows[] = array("c" => array(
            // get data information from record
            array("v" => (string) $dateJSON),
            //get NO2 level information 
            array("v" => (int) $no2level)
    ));
}
//return the collected array data to rows inside table
$table["rows"] = $rows;

//return data back to javascript in JSON format
echo json_encode($table);


function sortDataByDateTime($first, $second) {
    $record1 = simplexml_load_string($first->asXML());
    $record2 = simplexml_load_string($second->asXML());

    $date1 = DateTime::createFromFormat("d/m/Y H:i:s", ($record1->attributes()->date . " " . $record1->attributes()->time));
    $date2 = DateTime::createFromFormat("d/m/Y H:i:s", ($record2->attributes()->date . " " . $record2->attributes()->time));

    return $date1->format("U") - $date2->format("U");
    //
}
