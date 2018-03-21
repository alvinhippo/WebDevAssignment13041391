/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//To setup html page when users open
$(document).ready(function () {
    createTimeSelectionList();
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(updateChart);
});

/*
 * Since hard-coding a time selection list will be a very long list,
 * therefore, having a math calculation to calculate HH:MM:SS and create a string with every 15 mins in an hour
 * by a while loop
 */
function createTimeSelectionList() {

    var div = document.getElementById("time");

    //Add selection element into div
    var selection = document.createElement("select");

    //Setup id for reading value
    selection.id = "actual_time";

    //Setup changes when users choose a specific time
    selection.onchange = updateChart;
    div.appendChild(selection);


    //Setup time options
    var i = 0;
    while (i <= 23.75) {

        //Calculate the string for hours (HH:MM:SS)
        //Minutes
        var minutes = i - Math.floor(i);
        minutes *= 60;
        minutes = minutes === 0 ? "00" : "" + minutes;

        //Hours
        var hours = (i < 10 ? "0" : "") + Math.floor(i);

        //Update the whole string of html time
        var string = hours + ":" + minutes + ":00";

        //Create options for selection list
        var options = document.createElement("option");
        options.value = string;
        options.text = string;

        //Append options
        selection.appendChild(options);

        i += 0.25;
    }
}

function updateChart() {

    var stName = document.getElementById("stations").value;
    var specHour = document.getElementById("actual_time").value;
    var specYear = document.getElementById("year").value;

    xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "task2_getNO2dataOnSChart.php?stName=" + stName +
            "&time=" + specHour + "&year=" + specYear, true);
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {

            //Read the responding JSON data
            var jsonData = this.responseText;

            //Display the google scatter chart
            var data = new google.visualization.DataTable(jsonData);
            var options = {
                title: ('NO2 level of ' + stName + ' station at ' + specHour + " in " + specYear),
                hAxis: {title: 'Month&Year'},
                vAxis: {title: 'NO2 level'},
                legend: {position: 'bottom'}
            };

            var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));

            chart.draw(data, options);

        }
    };
    xmlhttp.send();
}