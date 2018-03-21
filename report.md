ATWD2 Assignment Report
=======

  This report aims to discuss about the comparison of  streaming parsers and DOM parsers for document processing. Additionally, it will provide the method of extending the charting and data visualisation afterwards.

---

  ## Discussion of XML processing models

  XML processing models can be divided as DOM oriented parsers and stream oriented parsers. For instances, SimpleXML() is one of DOM parsers function and XMLReader() is one of stream parsers function.  

  In the former case, DOM parser is a tree model parser, it takes advantages of all nodes of XML file can be expressed in parent-child relationship and the contents of XML file can be expressed logically by using DOM tree. It prefers to parse small content of XML file since it loads the whole file into the memory first which has memory constraints. It parses the entire XML at first and a DOM tree generates and returns afterwards. In this case, the performance and efficiency of the runtime may be slower if the XML file is large.  

  In the latter case, stream parser is an event-based model parser which can deal with large XML document, it can handle any input size of XML file since it does not have memory constraints and parse node by node. For example, it can read a specific value inside an element by using the method of "startTag" and "endTag". This means it reports parsing events directly by callbacks, can be faster to run through the parsing process.  

  In general, stream parser has higher performance and efficiency than DOM one. It requires less memory than DOM and  supports any size of XML file to parse specially with the large one, the runtime of the application performs decently. Moreover, DOM requires a lot of complex coding which may need to spend more time for implementation. In a nutshell, stream parser sends data directly to the application as it read, then the application can handle what it wants to see on a specific data.

  ---

  ## Discussion of extending the visualisation

  In the original example of Google Chart, it used hard-coded array and read the data directly to the table for visualising the chart. In this application, it generates chart from JSON format data which created and converted from XML files and visualises on a html page. In the html page, it separates javascript part which it has its own coding files and html page would perform the code with interface elements only. Both of the chart require user input by selecting specific information. After the selection, the selected values would send to a php file by AJAX technique called XMLHttpRequest() from the javascript. The javascript would cause to update the chart when it receives a new input from html page. Inside the php file, it uses xpath to get the reading of NO2 level data and read it into arrays, formats the data and time into "d/m/Y H:i:s" style and encode these readings into JSON data format to send it back to client for processing the chart in javascript and html page.
