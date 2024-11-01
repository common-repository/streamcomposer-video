/**
 * Created by Jarl Gjessing on 1/2/17.
 */

function encodingStatus() {
    var myVar;

    var addedIds = [];
    var mediaList = [];
    var encodingContainer = document.getElementById('encodingStatusTable');

    function callApi(controller, action, params) {
        var http = new XMLHttpRequest();
        var url = "//app.streamcomposer.com/" + controller + '/' + action;
        http.open("GET", url + '?' + params, true);
        var responseObject;
        http.onreadystatechange = function () {
            if (http.readyState == 4) {
                if (http.status != 200) {
                    responseObject = JSON.parse(http.responseText);
                    if (responseObject) {

                    }
                } else {
                    responseObject = JSON.parse(http.responseText);
                    if (action == "queued") {
                        getStatus(responseObject.data.medias);
                    } else if (action == "encodingDetails") {
                        printStatus(responseObject.data);
                    }

                }
            }
        };
        http.send(params);

        function getStatus(data) {
            var ids = [];
            mediaList = [];
            for (var mediaCounter = 0; mediaCounter < data.length; mediaCounter++) {
                ids[mediaCounter] = data[mediaCounter].id;
                mediaList[data[mediaCounter].id] = data[mediaCounter];
            }
            if (mediaList.length == 0 && addedIds.length) {
                removeComplete();
                addedIds = [];
            }
            if (ids.length) {
                var params = "client-security-token=" + streamcomposer_token + "&" +
                    "ids=" + ids.join(',');
                callApi('media', 'encodingDetails', params);
            }
        }
    }

    /**
     * Add a row to the encoding table, the data is the single media data returned from StreamComposer
     * @param data
     */
    function addRow(data) {
        var row = encodingContainer.insertRow(encodingContainer.rows.length);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var progressDiv = document.createElement('div');
        var progressBarDiv = document.createElement('div');

        row.id = "encodingRow" + data.mediaid;
        row.className = "inactive";
        cell1.innerHTML = mediaList[data.mediaid].name;
        cell1.className = "column-description desc";
        cell2.className = "column-description desc";
        progressDiv.className = "progress";
        progressBarDiv.id = "pctcontainer" + data.mediaid;
        progressBarDiv.className = "progressbar";
        progressBarDiv.style.width = data.progress + '%';
        progressBarDiv.role = "progressbar";
        if (parseInt(data.progress) == 0) {
            progressBarDiv.innerHTML = 'Waiting';
        } else {
            progressBarDiv.innerHTML = parseInt(data.progress) + '%';
        }

        progressDiv.appendChild(progressBarDiv);
        cell2.appendChild(progressDiv);
        addedIds[addedIds.length] = data.mediaid;
    }

    /**
     * Update a row in the encoding table
     * @param data
     */
    function updateRow(data) {
        element = document.getElementById("pctcontainer" + data.mediaid);
        element.style.width = data.progress + '%';
        element.innerHTML = parseInt(data.progress) + '%';
    }

    /**
     * Remove rows from encoding table
     */
    function removeComplete() {
        var found = false;
        for (var addedCounter = 0; addedCounter < addedIds.length; addedCounter++) {
            found = false;
            mediaList.forEach(function (element) {
                if (element.id == addedIds[addedCounter]) {
                    found = true;
                }
            });
            if (!found) {
                var row = document.getElementById("encodingRow" + addedIds[addedCounter]);
                row.parentNode.removeChild(row);
                addedIds.splice(addedIds.indexOf(addedIds[addedCounter]), 1);
            }
        }
    }

    /**
     * Add or update rows in the encoding table, argument is the list of medias returned by StreamComposer
     * @param mediaList
     */
    function printStatus(mediaList) {
        for (var i = 0; i < mediaList.length; i++) {
            rowIsAdded = false;
            for (var addedCounter = 0; addedCounter < addedIds.length; addedCounter++) {
                if (mediaList[i].mediaid == addedIds[addedCounter]) {
                    updateRow(mediaList[i]);
                    rowIsAdded = true;
                    break;
                }
            }
            if (!rowIsAdded) {
                addRow(mediaList[i]);
            }
            removeComplete();
        }
    }

    function myFunction() {
        myVar = setInterval(alertFunc, 3000);
    }

    function alertFunc() {
        var params = "client-security-token=" + streamcomposer_token;
        queuedItems = callApi('media', 'queued', params);
    }

    myFunction();
    //http://app.streamcomposer.com/media/encodingDetails?ids=62
}