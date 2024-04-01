var fileInput = document.querySelector("#uploadPdfAlertContent .peaperUploader #file-input");
var fileList = document.querySelector("#uploadPdfAlertContent .peaperUploader #files-list");
var numOfFiles = document.querySelector("#uploadPdfAlertContent .peaperUploader #num-of-files");
var submitButton = document.querySelector("#uploadPdfAlertContent #fileUploadSubmit");
var resultLog = document.querySelectorAll("#uploadPdfAlertContent .rusaltLog div");

// Flag to indicate zip creation readiness
var isZipReady = false;

fileInput.addEventListener("change", () => {
    var formData = new FormData();  // Use a single FormData object
    var files = fileInput.files;
    fileList.innerHTML = "";
    numOfFiles.textContent = `${files.length} Files Selected`;

    for (let i of files) {
        var reader = new FileReader();
        var listItem = document.createElement("li");
        var fileName = i.name;
        var fileSize = (i.size / 1024).toFixed(1);
        listItem.innerHTML = `<p><span class="math-inline">${fileName}</p\><p\></span>${fileSize}KB</p>`;
        if (fileSize >= 1024) {
            fileSize = (fileSize / 1024).toFixed(1);
            listItem.innerHTML = `<p><span class="math-inline">${fileName}</p\><p\></span>${fileSize}MB</p>`;
        }
        fileList.appendChild(listItem);
    }

    for (let i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
    }

    for (let i = 0; i < resultLog.length; i++) {
        resultLog[i].style.display = "none";
    }

    submitButton.addEventListener('click', function () {

        // cresate a zip file start
        resultLog[2].innerHTML = "Creating zip file...";
        resultLog[2].style.display = "block";


        var zip = new JSZip();

        for (let i = 0; i < files.length; i++) {
            zip.file(files[i].name, files[i]);
        }

        zip.generateAsync({ type: "blob" })
            .then(function (content) {
                console.log('Zip created');
                formData.append('zipFile', content);
                isZipReady = true;
                resultLog[2].innerHTML = "Zip Create successfill. Redy to Upload!";
                // resultLog[2].style.display = "block";

                var data = localStorage.getItem('UploadFileData');
                // localStorage.removeItem('UploadFileData');
                formData.append("manageActivity", "");
                formData.append("data", data);
                formData.append("type", "uploadFile");
                // formData = "manageActivity=" + "&data=" + data + "&type=" + "uploadFile";
                console.log('--- FormData Entries ---');
                for (const [key, value] of formData.entries()) {
                    console.log(`Key: ${key}, Value: ${value}`); // Access key and value
                }

                $.ajax({
                    url: "sql/process.php",
                    type: "POST",
                    data: formData,
                    processData: false, // Don't modify form data by jQuery
                    contentType: false, // Set content type as multipart/form-data
                    success: function (response) {
                        console.log(response);
                        if (response === ' success') { // Corrected comparison for strict equality
                            for (let i = 0; i < resultLog.length; i++) {
                                resultLog[i].style.display = "none";
                            }
                            console.log("finished");
                            resultLog[0].style.display = 'block';
                        } else if (response = " this is time  out") {
                            for (let i = 0; i < resultLog.length; i++) {
                                resultLog[i].style.display = "none";
                            }
                            resultLog[1].textContent = "This is timed out";
                            resultLog[1].style.display = 'block';
                        } else {
                            console.log("unfinished");
                            for (let i = 0; i < resultLog.length; i++) {
                                resultLog[i].style.display = "none";
                            }
                            resultLog[1].style.display = 'block';
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error:", textStatus, errorThrown);
                        for (let i = 0; i < resultLog.length; i++) {
                            resultLog[i].style.display = "none";
                        }
                        resultLog[1].style.display = 'block';
                    }
                });

            }).catch(function (err) {
                console.log('Error creating zip:', err);
                for (let i = 0; i < resultLog.length; i++) {
                    resultLog[i].style.display = "none";
                }
                resultLog[1].style.display = 'block';
            });
    });
});