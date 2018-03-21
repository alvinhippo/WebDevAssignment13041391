<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo "Processing... please wait for a moment<br>";

$place = array('brislington', 'fishponds', 'parson_st', 'rupert_st', 'wells_rd', 'newfoundland_way');

foreach ($place as $placeName) {

    generateDatawithNO2($placeName);
}

echo "all done!";

function generateDatawithNO2($pName) {

    $xmlRead = new XMLReader();
    $xmlRead->open('resources/xml/' . $pName . '.xml');

    /*  Generate xxx_no2.xml format like below:
     * 
     * <?xml version="1.0" encoding="UTF-8"?>
      <data type="nitrogen dioxide">
      <location id="wells road" lat="51.427" long="-2.568">
      <reading date="13/02/2016" time="03:15:00" val="11"/>
      <reading date="13/02/2016" time="03:30:00" val="11"/>
      <reading date="13/02/2016" time="03:45:00" val="11"/>

      <!-- thouands of other rows -->

      <reading date="13/02/2017" time="16:15:00" val="35"/>

      </location>
      </data> */
    $xmlWrite = new XMLWriter();
    $xmlWrite->openUri('resources/xml/' . $pName . '_no2.xml');
    $xmlWrite->setIndent(true);

    # show xml version and encoding
    $xmlWrite->startDocument('1.0', 'UTF-8');

    #show <data type="nitrogen dioxide">
    $xmlWrite->startElement('data');
    $xmlWrite->writeAttribute('type', 'nitrogen dioxide');
    $doc = new DOMDocument;
    $hasLoc = false;

    # start read through the whole XML file
    # Since we need the details inside <row> node, ignore other nodes.
    while ($xmlRead->read() && $xmlRead->name !== 'row') {
        continue;
    }

    #when the system found row node, starts parsing data to new file.
    while ($xmlRead->name === 'row') {

        $rec = simplexml_import_dom($doc->importNode($xmlRead->expand(), true));

        //When xxx_no2.xml file doesn't exist location data
        if (!$hasLoc) {
            $xmlWrite->startElement('location');
            $xmlWrite->writeAttribute('id', $rec->desc->attributes()->val);
            $xmlWrite->writeAttribute('lat', $rec->lat->attributes()->val);
            $xmlWrite->writeAttribute('long', $rec->long->attributes()->val);
            $hasLoc = true;
        }

        #Afterwards, adding reading data into XML
        $xmlWrite->startElement('reading');
        $xmlWrite->writeAttribute('date', $rec->date->attributes()->val);
        $xmlWrite->writeAttribute('time', $rec->time->attributes()->val);
        $xmlWrite->writeAttribute('val', $rec->no2->attributes()->val);
        $xmlWrite->endElement();

        //move to the next 'row 'node
        $xmlRead->next('row');
    }

    $xmlWrite->endElement(); //end location node
    $xmlWrite->endElement(); //end data node
    $xmlWrite->endDocument();
    $xmlWrite->flush();

    $xmlRead->close();
}
