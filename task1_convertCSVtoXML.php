<?php

echo "working .. wait";
ob_flush();
flush();
if (($handle = fopen("resources/air_quality.csv", "r")) !== FALSE) {

    # define the tags - last col value in csv file is derived so ignore
    $header = array('id', 'desc', 'date', 'time', 'nox', 'no', 'no2', 'lat', 'long');

    # throw away the first line - field names
    fgetcsv($handle, 200, ",");

    # count the number of items in the $header array so we can loop using it
    $cols = count($header);

    #set all 6 records count to 1
    $count = array(1, 1, 1, 1, 1, 1);
    # set row count to 2 - this is the row in the original csv file
    $row = 2;

    # start ##################################################
    $out = array('<records>', '<records>', '<records>', '<records>', '<records>', '<records>');
    $rec = array('', '', '', '', '', '');
    $stName = array('brislington', 'fishponds', 'parson_st', 'rupert_st', 'wells_rd', 'newfoundland_way');


    while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
        
        #a method that detect the data[0] value, since all monitor_id have no repeat value.
        #Thus, we can set it as a key value inside the switch method below.
        switch ($data[0]) {
            case '3':
                $rec[0] = stringCatRec($count, $row, $cols, $header, $data, 0);
                $count[0] ++;
                $out[0] .= $rec[0];
                break;
            
            case '6':
                $rec[1] = stringCatRec($count, $row, $cols, $header, $data, 1);
                $count[1] ++;
                $out[1] .= $rec[1];
                break;
            
            case '8':
                $rec[2] = stringCatRec($count, $row, $cols, $header, $data, 2);
                $count[2] ++;
                $out[2] .= $rec[2];
                break;
            
            case '9':
                $rec[3] = stringCatRec($count, $row, $cols, $header, $data, 3);
                $count[3] ++;
                $out[3] .= $rec[3];
                break;
            
            case'10':
                $rec[4] = stringCatRec($count, $row, $cols, $header, $data, 4);
                $count[4] ++;
                $out[4] .= $rec[4];
                break;
            
            case'11':
                $rec[5] = stringCatRec($count, $row, $cols, $header, $data, 5);
                $count[5] ++;
                $out[5] .= $rec[5];
                break;
        }

        $row++;
    }

    for ($i = 0; $i < 6; $i++) {
        $out[$i] .= '</records>';
        # write out file
        file_put_contents('resources/xml/' . $stName[$i] . '.xml', $out[$i]);
    }

    # finish ##################################################
    fclose($handle);
}


function stringCatRec($count, $row, $cols, $header, $data, $i) {
    $temp = '<row count="' . $count[$i] . '" id="' . $row . '">';
    for ($c = 0; $c < $cols; $c++) {
        $temp .= '<' . trim($header[$c]) . ' val="' . trim($data[$c]) . '"/>';
    }
    $temp .= '</row>';
    return $temp;
}

echo "....all done!";
?>

