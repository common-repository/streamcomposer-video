/**
 * Created by Jarl Gjessing on 1/2/17.
 *
 * Various objects and methods required for adding a video to StreamComposer.
 *
 * @TODO Various error handling
 * @TODO Various checks for filetypes etc.
 */

/**
 * Create the upload object that we use to upload videos to StreamComposer
 */
var r = new Resumable({
    target: '//app.streamcomposer.com/media/UploadFile?client-security-token=' + token,
    chunkSize: 1024 * 1024 * 2,
    simultaneousUploads: 1
});

//Attach uploader to input element
r.assignBrowse(document.getElementById('filename'));

/**
 * React on events
 */
r.on('fileSuccess', function (file) {
    document.getElementById('filename').value = file.fileName;
    document.getElementById('fileunique').value = file.uniqueIdentifier;
});

r.on('fileProgress', function (file, message) {
    var progress = parseInt(r.progress(false) * 100);
    document.getElementById('pctcontainer').style.width = progress + "%";
    document.getElementById('pctcontainer').innerHTML = progress + "%";
});

r.on('fileAdded', function (file, event) {
    document.getElementById('filename').disabled = true;
    //r.upload();
    document.getElementById('pctcontainer').style.width = "0%";
});

r.on('filesAdded', function (array) {
    r.upload();
    //console.debug('filesAdded', array);
});

r.on('fileRetry', function (file) {
    //console.debug(file);
});

r.on('fileError', function (file, message) {
    $('#submit').addClass('has-error');
    $('#submit .help-block').html('Error uploading file');
});

r.on('error', function (message, file) {
    console.log(message);
});

r.on('catchAll', function (message) {
    //console.log(message);
});

r.on('uploadStart', function () {
    document.getElementById('pctcontainer').style.width = "0%";
});

r.on('complete', function () {
    document.getElementById('filename').disabled = false;
    document.getElementById('pctcontainer').style.width = "100%";
    checkAddVideoBtn();
});

r.on('progress', function (message) {
    //console.log(message)
});

r.on('pause', function () {
    //console.debug('pause');
});

r.on('cancel', function () {
    //console.debug('cancel');
});

/**
 * Ensure that the add video button has only been enabled if all is ok
 */
function checkAddVideoBtn() {
    document.getElementById('addVideoBtn').disabled = !(
        document.getElementById('pctcontainer').style.width == "100%" &&
        document.getElementById('mediatitle').value != ""
    );
}

/**
 * Method for posting video data to StreamComposer. Can only be called AFTER the file has been uploaded.
 */
function addVideo() {
    document.getElementById('errorMessage').style.display = "none";
    var http = new XMLHttpRequest();
    var url = "//app.streamcomposer.com/media/addVideo";
    var params = "mediatitle=" + document.getElementById('mediatitle').value + "&" +
        "filename=" + document.getElementById('filename').value + '&' +
        "fileunique=" + document.getElementById('fileunique').value + '&' +
        "client-security-token=" + document.getElementById('apitoken').value;

    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function () {
        if (http.readyState == 4) {
            if (http.status != 200) {
                responseObject = JSON.parse(http.responseText);
                if (responseObject) {
                    document.getElementById('errorMessage').style.display = "block";
                    document.getElementById('errorContainer').innerHTML = responseObject.text;
                }
            } else {
                responseObject = JSON.parse(http.responseText);
                document.getElementById('updateMessage').style.display = "block";
                document.getElementById('updateContainer').innerHTML = responseObject.data.message;
            }
        }
    };
    http.send(params)
}
