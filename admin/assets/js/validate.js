var inputfeelds;
var inputfeelds2;
var datepiker;
var tags;
var reusaltLog;
var submitBtn;
var proccing_snipper;

var inputfeelds3;
var inputfeelds4;
var datepiker2;
var tags2;

// quiz var
var quizAddButton;
var addQiz;
var ansselectcheck;
var finishedBtn;
var FinishedQuiz;
var submitQuiz;
let quctions = [];
let quizcount = 0;

function addcurrentclassAttribute() {
    inputfeelds = document.querySelectorAll('.addcurrentclass .currentClassData input , .addcurrentclass .currentClassData select');
    submitBtn = document.getElementById('submitCurrentClass');
    reusaltLog = document.querySelectorAll('.addcurrentclass .rusaltLog div');
}

function atendentMarkAttribute() {
    reusaltLog = document.querySelectorAll('#atendentAlert .rusaltLog div');
    proccing_snipper = document.querySelector('#atendentAlert .proccing_snipper');
    reusaltLog[0].style.display = "none";
    reusaltLog[1].style.display = "none";
    proccing_snipper.style.display = "none";

}
function getqiozAttribute() {
    quizAddButton = document.getElementById('addQuiz');
    addQiz = document.querySelectorAll('.addQiz input[type=text] , .addQiz input[type=number] , .addQiz select , .addQiz textarea');
    ansselectcheck = document.querySelector('input[name=ansselectcheck]');
    finishedBtn = document.querySelector('input[name=FinishedQuiz]');
    FinishedQuiz = document.querySelectorAll('.finifhQiz input , .finifhQiz textarea');
    submitQuiz = document.querySelector('#quizfinishedBtn');
    reusaltLog = document.querySelectorAll('.rusaltLog div');
}

function getlessonAttribute() {
    inputfeelds = document.querySelectorAll('.AddLesSub1 input[type=text] , .AddLesSub1 select , .AddLesSub1 textarea');
    datepiker = document.querySelectorAll('.AddLesSub input[type=checkbox] , .AddLesSub input[type=date]');
    tags = document.querySelectorAll('.add-lesson .tags');  // .select2-selection__rendered li

    // lesson 2
    inputfeelds3 = document.querySelectorAll('.AddLesSub2 input[type=text] , .AddLesSub2 select , .AddLesSub2 textarea');
    datepiker2 = document.querySelectorAll('.AddLesSub2 input[type=checkbox] , .AddLesSub2 input[type=date]');
    tags2 = document.querySelectorAll('.add-lesson2 .tags');  // .select2-selection__rendered li
    reusaltLog = document.querySelectorAll('.rusaltLog div');

}

function getUpdateLessonAttribute() {
    inputfeelds = document.querySelectorAll('#mainModalAlert .AddLesSub input[type=text] , #mainModalAlert .AddLesSub select , #mainModalAlert .AddLesSub textarea');
    tags = document.querySelectorAll('#mainModalAlert .tags');
    reusaltLog = document.querySelectorAll('#mainModalAlert .rusaltLog div');
}

function getadclassAttribute() {
    inputfeelds = document.querySelectorAll('.modelMain .addclass');
    reusaltLog = document.querySelectorAll('.modelMain .rusaltLog div');
}

function getaddinstiAttribute() {
    inputfeelds = document.querySelectorAll('.modelMain input[type=text] , .modelMain textarea');
    inputfeelds2 = document.querySelector('.modelMain input[type=file]');
    reusaltLog = document.querySelectorAll('.modelMain .rusaltLog div');
}

function getaddgroupAttribute() {
    inputfeelds = document.querySelectorAll('.modelMain .addgroup');
    tags = document.querySelector('.modelMain .tags select');
    reusaltLog = document.querySelectorAll('.modelMain .rusaltLog div');
}

function getaddWinnerAttribute() {
    inputfeelds = document.querySelectorAll('.modelMain input[type=text] , .modelMain input[type=date]');
    inputfeelds2 = document.querySelector('.modelMain input[type=file]');
    reusaltLog = document.querySelectorAll('.modelMain .rusaltLog div');
}

function getaddpeaperAttribute() {
    inputfeelds = document.querySelectorAll('.modelMain .online');
    inputfeelds2 = document.querySelectorAll('.modelMain .physical');
    tags = document.querySelectorAll('.modelMain .tags select');
    reusaltLog = document.querySelectorAll('.modelMain .rusaltLog div');
}

// snippit update 
function getUpdateInstiAttribute() {
    inputfeelds = document.querySelectorAll('#modelMainContent input[type=text] , #modelMainContent textarea');
    inputfeelds2 = document.querySelector('#modelMainContent input[type=file]');
    reusaltLog = document.querySelectorAll('#modelMainContent .rusaltLog div');
}

function getUpdateClasspAttribute() {
    inputfeelds = document.querySelectorAll('#modelMainContent input[type=text] , #modelMainContent textarea');
    reusaltLog = document.querySelectorAll('#modelMainContent .rusaltLog div');
}

function getUpdateGroupAttribute() {
    inputfeelds = document.querySelector('#modelMainContent textarea');
    inputfeelds2 = document.querySelector('#modelMainContent input[type=file]');
    tags = document.querySelector('#modelMainContent .tags select');
    reusaltLog = document.querySelectorAll('#modelMainContent .rusaltLog div');
}


// add current class start 
function submitCurrentClass(val = null) {

    if (val == null) {
        addcurrentclassAttribute();
        if (validatecurrentclass()) {
            console.log(inputfeelds.length);
            var List = [];
            inputfeelds.forEach(function (self) {
                List.push(self.value);
            });
            var PassData = new FormData();
            var newList = JSON.stringify(List);
            PassData.append("addCurrentClass", newList);
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    if (response == ' success') {
                        reusaltLog[0].style.display = 'block';
                        reusaltLog[1].style.display = 'none';
                        location.reload();
                        // setTimeout(() => {
                        // reusaltLog[0].style.display = 'none';
                        // }, 5000);
                    }
                }
            });
        } else {
            reusaltLog[1].innerHTML = "Please Complete all";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = 'none'; }, 5000);
        }
    } else if (val == 'del') {
        var PassData = new FormData();
        PassData.append("addCurrentClass", val);
        $.ajax({
            url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                console.log(response);
                location.reload();
            }
        });
    }

}

function validatecurrentclass() {
    var finaly = true;
    for (var count = 0; count < inputfeelds.length - 3; count++) {
        if (inputfeelds[count].value == null || inputfeelds[count].value == "") {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            finaly = false;
        } else {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        }
    }
    var time1 = inputfeelds[1].value.replace(':', '');
    var time2 = inputfeelds[2].value.replace(':', '');
    if (time1 > time2) {
        inputfeelds[1].classList.toggle("is-valid", false);
        inputfeelds[2].classList.toggle("is-valid", false);
        inputfeelds[1].classList.toggle("is-invalid", true);
        inputfeelds[2].classList.toggle("is-invalid", true);
        finaly = false;
    }
    return finaly;
}
// add current class end  

// atendent start 
function atendent(val1, val2) {
    atendentMarkAttribute();
    proccing_snipper.style.display = "block";
    var PassData = new FormData();
    PassData.append("markAtendent", '');
    PassData.append("val1", val1);
    PassData.append("val2", val2);
    $.ajax({
        url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
            console.log(response);
            proccing_snipper.style.display = "none";
            if (response == ' success') {
                reusaltLog[0].style.display = "block";
                reusaltLog[1].style.display = "none";
                document.querySelector('#addcurrentclass .modal-footer button').click();
            } else {
                reusaltLog[0].style.display = "none"
                reusaltLog[1].style.display = "block"
            }
        }
    });
}
// atendent end

// lesson management start

function typeChange(val) {
    getlessonAttribute();
    if (inputfeelds3[0].value == 'quiz') {
        document.querySelectorAll('.AddLesSub2')[1].style.display = "none";
        document.querySelector('.quiz').style.display = "block";
    } else if (inputfeelds3[0].value == 'classwork') {
        document.querySelectorAll('.AddLesSub2')[1].style.display = "block";
        document.querySelector('.quiz').style.display = "none";
    } else if (inputfeelds3[0].value == 'upload') {
        document.querySelectorAll('.AddLesSub2')[1].style.display = "block";
        document.querySelector('.quiz').style.display = "none";
    }
}

function submitAddLesson(val) {
    event.preventDefault();
    getlessonAttribute();
    // val == 1 ? clearmodeldata('#lessovVidNot') : clearmodeldata('#lessonClzUpd');
    console.log(validatelesson_val(val));
    if (validatelesson_val(val)) {
        if (val == 1) {
            console.log('hfdvijbsfgi');
            // getting data
            var ligroup = document.querySelectorAll('.add-lesson .group .select2-selection__rendered li');
            var liaccess = document.querySelectorAll('.add-lesson .access .select2-selection__rendered li');
            const grouplist = [];
            const accesslist = [];
            ligroup.forEach(function (li) {
                var title = li.getAttribute('title');
                grouplist.push(title);
            });
            liaccess.forEach(function (li) {
                var title = li.getAttribute('title');
                accesslist.push(title);
            });

            // append data in varitable
            var LessonData = new FormData();
            LessonData.append("AddLessonData", "1");
            for (var count = 0; count < inputfeelds.length; count++) {
                LessonData.append(inputfeelds[count].name, inputfeelds[count].value);
            }
            if (datepiker[0].checked) {
                LessonData.append(datepiker[1].name, datepiker[1].value);
            } else {
                LessonData.append("expdate", "");
            }
            LessonData.append("grouplist", grouplist);
            LessonData.append("accesslist", accesslist);
        } else if (val == 2) {
            var ligroup = document.querySelectorAll('.add-lesson2 .group2 .select2-selection__rendered li');
            var liaccess = document.querySelectorAll('.add-lesson2 .access2 .select2-selection__rendered li');
            var quizval = tags2[0].querySelector('select');
            const grouplist = [];
            const accesslist = [];
            ligroup.forEach(function (li) {
                var title = li.getAttribute('title');
                grouplist.push(title);
            });
            liaccess.forEach(function (li) {
                var title = li.getAttribute('title');
                accesslist.push(title);
            });

            // append data in varitable
            var LessonData = new FormData();
            LessonData.append("AddLessonData", "2");
            for (var count = 0; count < inputfeelds3.length; count++) {
                LessonData.append(inputfeelds3[count].name, inputfeelds3[count].value);
            }
            if (datepiker2[0].checked) {
                LessonData.append(datepiker2[1].name, datepiker2[1].value);
            } else {
                LessonData.append("expdate", "");
            }
            LessonData.append("quiz", quizval.value);
            LessonData.append("grouplist", grouplist);
            LessonData.append("accesslist", accesslist);
        }
        // for (const [key, value] of LessonData.entries()) {
        // console.log(`Key: ${key}, Value: ${value}`);
        // }

        // pass php page data
        $.ajax({
            url: "sql/process.php", type: "POST", data: LessonData, processData: false, contentType: false, success: function (response, status) {
                console.log(response);
                if (response == ' successfull') {
                    $('.select-multiple').val(null).trigger('change');
                    clearFormData();
                    var count = val == 1 ? 0 : 2;
                    reusaltLog[count].style.display = "block";
                    setTimeout(function () { reusaltLog[count].style.display = "none"; }, 10000);
                } else {
                    var count = val == 1 ? 1 : 3;
                    reusaltLog[count].style.display = "block";
                    reusaltLog[count].innerHTML = (response == ' Alredy add') ? "Failed add the lesson because alredy add this lesson!" : "Failed add the lesson";
                    setTimeout(function () { reusaltLog[count].style.display = "none"; }, 10000);
                }
            }
        });

    }
}


function validatelesson_val(val) {
    // getlessonAttribute();
    var return_data = true;
    if (val == 1) {
        for (var count = 0; count < inputfeelds.length - 1; count++) {
            // console.log(inputfeelds[count].name);
            if (inputfeelds[count].value == null || inputfeelds[count].value == "") {
                inputfeelds[count].classList.toggle("is-valid", false);
                inputfeelds[count].classList.toggle("is-invalid", true);
                return_data = false;
            } else {
                inputfeelds[count].classList.toggle("is-valid", true);
                inputfeelds[count].classList.toggle("is-invalid", false);
                // return_data && true;
            }
        }

        if (datepiker[0].checked) {
            if (datepiker[1].value == null || datepiker[1].value == "") {
                datepiker[1].classList.toggle("is-valid", false);
                datepiker[1].classList.toggle("is-invalid", true);
                return_data = false;
                // console.log(datepiker[1].name);
            } else {
                datepiker[1].classList.toggle("is-valid", true);
                datepiker[1].classList.toggle("is-invalid", false);
                // return_data && true;
            }
        } else {
            datepiker[1].classList.toggle("is-valid", false);
            datepiker[1].classList.toggle("is-invalid", false);
        }

        for (var count = 0; count < tags.length; count++) {
            var tag = tags[count].querySelector('select');
            var errormsg = tags[count].querySelector('.invalid-feedback');
            var successmsg = tags[count].querySelector('.valid-feedback');
            // console.log(tag.value);
            if (tag.value == null || tag.value == "") {
                tags[count].classList.toggle("is-valid", false);
                tags[count].classList.toggle("is-invalid", true);
                errormsg.style.display = 'block';
                successmsg.style.display = 'none';
                // return_data = true;
                // console.log(tag.name);
            } else {
                tags[count].classList.toggle("is-valid", true);
                tags[count].classList.toggle("is-invalid", false);
                errormsg.style.display = 'none';
                successmsg.style.display = 'block';
            }
        }
    } else if (val == 2) {
        for (let index = 0; index < inputfeelds3.length - 1; index++) {
            // console.log(inputfeelds3[index].name);
            if (inputfeelds3[index].value == null || inputfeelds3[index].value == "") {
                inputfeelds3[index].classList.toggle("is-valid", false);
                inputfeelds3[index].classList.toggle("is-invalid", true);
                return_data = false;
                (inputfeelds3[0].value == 'quiz' && index == 1) ? return_data = true : null;
            } else {
                inputfeelds3[index].classList.toggle("is-valid", true);
                inputfeelds3[index].classList.toggle("is-invalid", false);
                // return_data && true;
            }
        }

        if (datepiker2[0].checked) {
            if (datepiker2[1].value == null || datepiker2[1].value == "") {
                datepiker2[1].classList.toggle("is-valid", false);
                datepiker2[1].classList.toggle("is-invalid", true);
                return_data = false;
            } else {
                datepiker2[1].classList.toggle("is-valid", true);
                datepiker2[1].classList.toggle("is-invalid", false);
                // return_data && true;
            }
        } else {
            datepiker2[1].classList.toggle("is-valid", false);
            datepiker2[1].classList.toggle("is-invalid", false);
        }

        for (let count = 0; count < tags2.length; count++) {
            // if (inputfeelds3[0].value == "quiz") {
            var tag = tags2[count].querySelector('select');
            var errormsg = tags2[count].querySelector('.invalid-feedback');
            var successmsg = tags2[count].querySelector('.valid-feedback');
            if (tag.value == null || tag.value == "") {
                tags2[count].classList.toggle("is-valid", false);
                tags2[count].classList.toggle("is-invalid", true);
                errormsg.style.display = 'block';
                successmsg.style.display = 'none';
                (inputfeelds3[0].value != 'quiz' && count == 0) ? return_data = true : return_data = false;
            } else {
                tags2[count].classList.toggle("is-valid", true);
                tags2[count].classList.toggle("is-invalid", false);
                errormsg.style.display = 'none';
                successmsg.style.display = 'block';
                // }
            }
        }
    }
    return return_data;
}

// add quction sectoion start

// add quiz update start

function quizpagePnclick() {

    getqiozAttribute();
    function getqiozAttribute() {
        quizAddButton = document.getElementById('addQuiz');
        addQiz = document.querySelectorAll('.addQiz input[type=text] , .addQiz input[type=number] , .addQiz select , .addQiz textarea');
        ansselectcheck = document.querySelector('input[name=ansselectcheck]');
        finishedBtn = document.querySelector('input[name=FinishedQuiz]');
        FinishedQuiz = document.querySelectorAll('.finifhQiz input , .finifhQiz textarea');
        submitQuiz = document.querySelector('#quizfinishedBtn');
    }

    submitQuiz.onclick = () => {
        event.preventDefault();
        var strquctions = JSON.stringify(quctions);
        var PassData = new FormData();
        PassData.append('AddQuiz', '');
        PassData.append("quctions", strquctions);
        FinishedQuiz.forEach((self) => {
            PassData.append(self.name, self.value);
        });
        $.ajax({
            url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                // response = errorHadel(response);
                if (response == "Successfull") {
                    console.log(response);
                } else {
                    console.log(response);
                }
            }
        });
    }

    finishedBtn.onclick = () => {
        if (finishedBtn.checked && checkAllquizValidate()) {
            submitQuiz.classList.add('finifhed');
            finishedBtn.checked = true;
        } else {
            submitQuiz.classList.remove('finifhed');
            finishedBtn.checked = false;
        }
    }


    // ADD NEW QUIZ
    quizAddButton.onclick = () => {
        event.preventDefault();
        if (checkQuizValid()) {
            // console.log('done');
            quizcount++;
            quctions.push({
                'numb': quizcount,
                'quction': addQiz[0].value,
                'dict': addQiz[6].value,
                'ans': addQiz[5].value,
                'option': [addQiz[1].value, addQiz[2].value, addQiz[3].value, addQiz[4].value]
            });
            loadquizlist();
            restoreQuiz();
            // console.log(quctions);
        }
    }

    // make answer
    ansselectcheck.onclick = () => {
        console.log('clicked');
        if (ansselectcheck.checked) {
            var finaly = true;
            for (let index = 0; index < 5; index++) {
                if (addQiz[index].value.length > 0) {
                    addQiz[index].classList.toggle("is-invalid", false);
                } else {
                    addQiz[index].classList.toggle("is-invalid", true);
                    finaly = false;
                }
            }

            if (finaly) {
                let qansList = "<option value=''>Select Answer</option>" +
                    "<option value='" + addQiz[1].value + "'>" + addQiz[1].value + "</option>" +
                    "<option value='" + addQiz[2].value + "'>" + addQiz[2].value + "</option>" +
                    "<option value='" + addQiz[3].value + "'>" + addQiz[3].value + "</option>" +
                    "<option value='" + addQiz[4].value + "'>" + addQiz[4].value + "</option>";
                addQiz[5].innerHTML = qansList;
                addQiz[5].classList.add("isitquiz");
            } else {
                ansselectcheck.checked = false;
                addQiz[5].innerHTML = "<option value=''>Select Answer</option>";
            }
        } else {
            addQiz[5].classList.remove("isitquiz");
        }
    }
}

// validate
function checkQuizValid() {
    finaly = true;
    for (let index = 0; index < addQiz.length - 1; index++) {
        if (addQiz[index].value.length > 0) {
            addQiz[index].classList.toggle("is-invalid", false);
        } else {
            addQiz[index].classList.toggle("is-invalid", true);
            finaly = false;
        }
    }
    return finaly;
}

function checkAllquizValidate() {
    var finaly = true;
    if (!quctions.length > 0) {
        document.querySelector('.add-quiz .invalid-feedback').style.display = "block";
        finaly = false;
    }
    for (let index = 0; index < FinishedQuiz.length - 1; index++) {
        if (FinishedQuiz[index].value.length > 0) {
            FinishedQuiz[index].classList.toggle("is-invalid", false);
        } else {
            FinishedQuiz[index].classList.toggle("is-invalid", true);
            finaly = false;
        }
    }
    return finaly;
}

function restoreQuiz() {
    clearFormData();
    addQiz[5].innerHTML = "<option value=''>Select Answer</option>";
    addQiz[5].classList.remove("isitquiz");
    for (let index = 0; index < addQiz.length; index++) {
        addQiz[index].classList.toggle("is-invalid", false);
    }
}

// add quiz update end


// get variyable quction start

loadquizlist();
function loadquizlist() {
    let QuizAllContent = "";
    if (quctions.length == 0) {
        QuizAllContent = "<p>Quction Not Found!</p>";
    }
    for (let index = 0; index < quctions.length; index++) {
        // const element = quctions[index];
        let QuizSnippetContent = "<div class='col-xl-10 col-sm-6 col-12 dashed-border p-3'>" +
            "<h5>" + quctions[index]['numb'] + " :- " + quctions[index]['quction'] + "</h5>" +
            // "<hr>" +
            "<span class='alert alert-info py-1 text-black'>" + quctions[index]['option'][0] + "</span>" +
            "<span class='alert alert-info py-1 text-black'>" + quctions[index]['option'][1] + "</span>" +
            "<span class='alert alert-info py-1 text-black'>" + quctions[index]['option'][2] + "</span>" +
            "<span class='alert alert-info py-1 text-black'>" + quctions[index]['option'][3] + "</span>" +
            "<span class='alert alert-success py-1 text-black'>" + quctions[index]['ans'] + "</span>" +
            "</div>"
        QuizAllContent += QuizSnippetContent;
        // console.log(QuizSnippetContent);
    }
    $('#quisList').html(QuizAllContent);
}
// get variyable quction end

// add quction sectoion end

// update lesson data start 
function updateLessonData(val1, val2) {
    getUpdateLessonAttribute();
    if (validUpdateLessonVal()) {
        console.log(1);
        var tag1 = tags[0].querySelector('select');
        var tag2 = tags[1].querySelector('select');
        var liaccess = document.querySelectorAll('#mainModalAlert .access3 .select2-selection__rendered li');

        var LessonData = new FormData();

        // append data in varitable
        LessonData.append("updateLessonData", "");
        LessonData.append("val1", val1);
        for (var count = 0; count < inputfeelds.length; count++) {
            LessonData.append(inputfeelds[count].name, inputfeelds[count].value);
        }

        if (tag1.value != "") {
            var ligroup = document.querySelectorAll('#mainModalAlert .group3 .select2-selection__rendered li');
            const grouplist = [];
            ligroup.forEach(function (li) {
                var title = li.getAttribute('title');
                console.log(title);
                grouplist.push(title);
            });
            grouplist.forEach(function (title) {
                LessonData.append("grouplist[]", title);
            });
        }

        if (tag2.value != "") {
            const accesslist = [];
            liaccess.forEach(function (li) {
                var title = li.getAttribute('title');
                accesslist.push(title);
            });
            accesslist.forEach(function (title) {
                LessonData.append("accesslist[]", title);
            });
        }

        // pass php page data
        $.ajax({
            url: "sql/process.php", type: "POST", data: LessonData, processData: false, contentType: false, success: function (response, status) {
                console.log(status);
                console.log(response);
                if (response == ' successfull') {
                    $('.select-multiple').val(null).trigger('change');
                    clearFormData();
                    loadLessocContent();
                    reusaltLog[0].style.display = "block";

                } else {
                    reusaltLog[1].style.display = "block";
                    reusaltLog[1].innerHTML = (response == ' Alredy add') ? "Failed add the lesson because alredy add this lesson!" : "Failed add the lesson";
                    setTimeout(function () { reusaltLog[1].style.display = "none"; }, 10000);
                }
            }
        });
    } else {
        reusaltLog[1].style.display = "block";
        reusaltLog[1].innerHTML = "Please Fill the all";
        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
    }
}

function validUpdateLessonVal() {
    var return_data = true;
    for (var count = 0; count < inputfeelds.length - 1; count++) {
        if (inputfeelds[count].value == null || inputfeelds[count].value == "") {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            return_data = false;
        } else {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        }
    }

    return return_data;
}

// update lesson data end

function lesAction(val1, val2) {
    var PassData = new FormData();
    PassData.append('lesAction', val2);
    PassData.append('val1', val1);
    $.ajax({
        url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
            // console.log(response);
            loadLessocContent();
        }
    });
}


// lesson management end   *********************

// peaper management start
function peaperType(value) {
    getaddpeaperAttribute();
    if (value == 'online' || value == 'both') {
        console.log('select Online');
        inputfeelds2[1].hidden = true;
        inputfeelds.forEach(function (self) {
            self.disabled = false;
            self.hidden = false;
        });
        tags.forEach(function (self) {
            self.disabled = false;
        });
    } else if (value == 'physical') {
        console.log('select physical');
        inputfeelds[1].hidden = true;
        inputfeelds2.forEach(function (self) {
            self.disabled = false;
            self.hidden = false;
        });
        tags.forEach(function (self) {
            self.disabled = false;
        });
    } else {
        inputfeelds.forEach(function (self) {
            self.disabled = true;
        });
        inputfeelds2.forEach(function (self) {
            self.disabled = true;
        });
        tags.forEach(function (self) {
            self.disabled = true;
        });
        inputfeelds[0].disabled = false;
        inputfeelds2[0].disabled = false;
    }

}

function submitModelPeaper(type, method = null) {
    event.preventDefault();
    if (type == 'addPeaper') {
        getaddpeaperAttribute();
        if (validateaddpeaper()) {
            console.log('done');
            var PeaperData = new FormData();
            PeaperData.append("peaperManage", "");
            PeaperData.append("type", "addPeaperData");
            if (inputfeelds[0].value == 'online' || inputfeelds[0].value == 'both') {
                for (var count = 0; count < inputfeelds.length; count++) {
                    PeaperData.append(inputfeelds[count].name, inputfeelds[count].value);
                }
            } else if (inputfeelds[0].value == 'physical') {
                for (var count = 0; count < inputfeelds2.length; count++) {
                    PeaperData.append(inputfeelds2[count].name, inputfeelds2[count].value);
                }
            } else {
                console.log('not oooo ' + inputfeelds[0].value);
            }
            if (true) {
                var ligroup = document.querySelectorAll('#modelMainContent .group .select2-selection__rendered li');
                var liaccess = document.querySelectorAll('#modelMainContent .class .select2-selection__rendered li');
                const grouplist = [];
                const accesslist = [];
                ligroup.forEach(function (li) {
                    var title = li.getAttribute('title');
                    grouplist.push(title);
                });
                liaccess.forEach(function (li) {
                    var title = li.getAttribute('title');
                    accesslist.push(title);
                });
                PeaperData.append('group', grouplist);
                PeaperData.append('class', accesslist);

                // PeaperData.forEach(function (key, value) {
                //     console.log("key : " + key + "   value : " + value);
                // })
            }
            $.ajax({
                url: "sql/process.php", type: "POST", data: PeaperData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    if (response == " success") {
                        // clearFormData();
                        // reusaltLog[1].style.display = "none";
                        // reusaltLog[0].style.display = "block";
                        // setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                        ShowBody('addPeaper');
                        $('#modelMain').modal('hide');
                        nTost({ type: 'success', titleText: 'SuccessFully add the peaper' });
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' Alredy Added' ? 'Failed Alredy add this peaper' : 'Failed add the peaper';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    }

}

function validateaddpeaper() {
    finaly = true;
    // console.log(inputfeelds[0].value);
    if (inputfeelds[0].value == 'online' || inputfeelds[0].value == 'both') {
        finaly = validateinputfeelds(1) && validatetags(0);
    } else if (inputfeelds[0].value == 'physical') {
        finaly = validateinputfeelds2(1) && validatetags(0);
    } else {
        finaly = false;
    }
    return finaly;
}

function validateinputfeelds(decre = 0) {
    var finaly = true;
    for (let index = 0; index < inputfeelds.length - decre; index++) {
        if (inputfeelds[index].value == "") {
            inputfeelds[index].classList.toggle("is-valid", false);
            inputfeelds[index].classList.toggle("is-invalid", true);
            // console.log('false is ' + inputfeelds[index].name);
            finaly = false;
        } else {
            inputfeelds[index].classList.toggle("is-valid", true);
            inputfeelds[index].classList.toggle("is-invalid", false);
        }
    }
    return finaly;
}

function validateinputfeelds2(decre = 0) {
    var finaly = true;
    for (let index = 0; index < inputfeelds2.length - decre; index++) {
        if (inputfeelds2[index].value == "") {
            inputfeelds2[index].classList.toggle("is-valid", false);
            inputfeelds2[index].classList.toggle("is-invalid", true);
            console.log('false is ' + inputfeelds2[index].name);
            finaly = false;
        } else {
            inputfeelds2[index].classList.toggle("is-valid", true);
            inputfeelds2[index].classList.toggle("is-invalid", false);
        }
    }
    return finaly;
}

function validatetags(decre = 0) {
    for (let index = 0; index < tags.length; index++) {
        // var errormsg = tags[count].parentNode.querySelector('.invalid-feedback');
        // var successmsg = tags[count].parentNode.querySelector('.valid-feedback');
        if (tags[index].value == "") {
            // errormsg.style.display = "block";
            // successmsg.style.display = 'none';
            finaly = false;
        } else {
            // errormsg.style.display = "none";
            // successmsg.style.display = "block";
        }
    }
    return finaly;
}

// peaper management end

// snippit management start   *********************

// insert data start 

function submitModelSnippet(type, method) {
    event.preventDefault();
    if (method == 'insti') {
        getaddinstiAttribute();
        if (validateaddinsti()) {
            var PassData = new FormData();
            PassData.append("addinstiData", "");
            for (var count = 0; count < inputfeelds.length; count++) {
                PassData.append(inputfeelds[count].name, inputfeelds[count].value);
            }
            PassData.append('instipic', inputfeelds2.files[0]);
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    if (response == " success") {
                        clearFormData();
                        reusaltLog[1].style.display = "none";
                        reusaltLog[0].style.display = "block";
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' alredy added' ? 'Failed Alredy create this insti' : 'Failed create the insti';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
            // PassData.append()
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    } else if (method == 'class') {
        getadclassAttribute();
        if (validateaddclass()) {
            console.log('sffsdfsedr');
            var PassData = new FormData();
            PassData.append("addClassData", "")
            for (var count = 0; count < inputfeelds.length; count++) {
                PassData.append(inputfeelds[count].name, inputfeelds[count].value);
            }
            // console.log(PassData);
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    if (response == " success") {
                        clearFormData();
                        reusaltLog[1].style.display = "none";
                        reusaltLog[0].style.display = "block";
                        location.reload();
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' alredy added' ? 'Failed Alredy create this class' : 'Failed create the class';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
            // PassData.append()
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    } else if (method == 'group') {
        getaddgroupAttribute();
        if (validateaddgroup()) {
            var ligroup = document.querySelectorAll('.grouphideclass .select2-selection__rendered li');
            const HideList = [];
            ligroup.forEach(function (li) {
                var title = li.getAttribute('title');
                HideList.push(title);
            });
            var PassData = new FormData();
            PassData.append("AddGroupData", "");
            PassData.append("HideList", HideList);
            PassData.append(inputfeelds[0].name, inputfeelds[0].value);
            PassData.append("groupimg", inputfeelds[1].files[0]);

            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    // response = errorHadel(response);
                    if (response == " success") {
                        clearFormData();
                        reusaltLog[1].style.display = "none";
                        reusaltLog[0].style.display = "block";
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' alredy added' ? 'Failed Alredy create this group' : 'Failed create the group';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    } else if (method == 'winner') {
        getaddWinnerAttribute();
        if (validateaddwinner()) {
            reusaltLog[1].style.display = 'none';
            var PassData = new FormData();
            PassData.append('addwinner', 'insert');
            var list = [];
            for (let index = 0; index < inputfeelds.length; index++) {
                index == 5 ? PassData.append(inputfeelds[5].name, inputfeelds[5].value) : list.push(inputfeelds[index].name, inputfeelds[index].value);
            }
            PassData.append('dataList', JSON.stringify(list));
            PassData.append('winnerImage', inputfeelds2.files[0]);

            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    // response = errorHadel(response);
                    if (response == " success") {
                        reusaltLog[0].style.display = "block";
                        clearFormData();
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 10000);
                        console.log(response);
                    } else {
                        console.log(response);
                    }
                }
            });
        } else {
            reusaltLog[1].innerHTML = 'Pleace complete everything';
            reusaltLog[1].style.display = 'block';
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    }

}

// insert data end

// update data start 
function submitModelSnippetUpdate(type, id) {
    event.preventDefault();
    if (type == 'instiUpdate') {
        getUpdateInstiAttribute();
        if (validatetext()) {
            var PassData = new FormData();
            PassData.append("updateInsti", "");
            PassData.append("id", id);
            for (var count = 0; count < inputfeelds.length; count++) {
                PassData.append(inputfeelds[count].name, inputfeelds[count].value);
            }
            inputfeelds2.value !== "" ? PassData.append("instiImage", inputfeelds2.files[0]) : null;
            // PassData.forEach((value, key) => {
            //     console.log("name  : " + key + " value : " + value);
            // });
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    // updateModelContent('instiUpdate', id);
                    ShowBody();
                    if (response == " success") {
                        reusaltLog[1].style.display = "none";
                        reusaltLog[0].style.display = "block";
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' alredy added' ? 'Failed Alredy create this insti' : 'Failed create the insti';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    } else if (type == 'classUpdate') {
        getUpdateClasspAttribute();
        if (validatetext()) {
            var PassData = new FormData();
            PassData.append("updateClass", "");
            PassData.append("id", id);
            for (var count = 0; count < inputfeelds.length; count++) {
                PassData.append(inputfeelds[count].name, inputfeelds[count].value);
            }
            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    ShowBody();
                    if (response == " success") {
                        // clearFormData();
                        reusaltLog[1].style.display = "none";
                        reusaltLog[0].style.display = "block";
                        // location.reload();
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' alredy added' ? 'Failed Alredy create this class' : 'Failed create the class';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
            // PassData.append()
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    } else if (type == 'groupUpdate') {
        getUpdateGroupAttribute();
        if (inputfeelds.value !== "") {
            var PassData = new FormData();
            PassData.append("id", id);
            if (tags.value !== "") {
                var ligroup = document.querySelectorAll('.grouphideclass .select2-selection__rendered li');
                const HideList = [];
                ligroup.forEach(function (li) {
                    var title = li.getAttribute('title');
                    HideList.push(title);
                });
                PassData.append("HideList", HideList);
            }
            PassData.append("updateGroup", "");
            PassData.append(inputfeelds.name, inputfeelds.value);
            PassData.append("groupimg", inputfeelds2.files[0]);

            $.ajax({
                url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                    console.log(response);
                    // response = errorHadel(response);
                    ShowBody();
                    if (response == " success") {
                        // clearFormData();
                        reusaltLog[1].style.display = "none";
                        reusaltLog[0].style.display = "block";
                        setTimeout(function () { reusaltLog[0].style.display = "none"; }, 5000);
                    } else {
                        reusaltLog[0].style.display = "none";
                        reusaltLog[1].style.display = "block";
                        reusaltLog[1].innerHTML = response == ' alredy added' ? 'Failed Alredy create this group' : 'Failed create the group';
                        setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
                    }
                }
            });
        } else {
            reusaltLog[0].style.display = "none";
            reusaltLog[1].innerHTML = "Please Fil The All Inputs";
            reusaltLog[1].style.display = "block";
            setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
        }
    }
    // else if (method == 'winner') {
    //     getaddWinnerAttribute();
    //     if (validateaddwinner()) {
    //         reusaltLog[1].style.display = 'none';
    //         var PassData = new FormData();
    //         PassData.append('addwinner', 'insert');
    //         var list = [];
    //         for (let index = 0; index < inputfeelds.length; index++) {
    //             index == 5 ? PassData.append(inputfeelds[5].name, inputfeelds[5].value) : list.push(inputfeelds[index].name, inputfeelds[index].value);
    //         }
    //         PassData.append('dataList', JSON.stringify(list));
    //         PassData.append('winnerImage', inputfeelds2.files[0]);

    //         $.ajax({
    //             url: "sql/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
    //                 // response = errorHadel(response);
    //                 if (response == " success") {
    //                     reusaltLog[0].style.display = "block";
    //                     clearFormData();
    //                     setTimeout(function () { reusaltLog[0].style.display = "none"; }, 10000);
    //                     console.log(response);
    //                 } else {
    //                     console.log(response);
    //                 }
    //             }
    //         });
    //     } else {
    //         reusaltLog[1].innerHTML = 'Pleace complete everything';
    //         reusaltLog[1].style.display = 'block';
    //         setTimeout(function () { reusaltLog[1].style.display = "none"; }, 5000);
    //     }
    // }

}
// update data end

// update data valied start 

function validatetext() {
    var finaly = true;
    for (var count = 0; count < inputfeelds.length; count++) {
        if (inputfeelds[count].value.length > 2) {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        } else {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            finaly = false;
        }
    }
    return finaly; s
}
// update data valied end

// insert insti validate start

function validateaddwinner() {
    var finaly = true;
    for (var count = 0; count < inputfeelds.length - 1; count++) {
        if (inputfeelds[count].value.length > 2) {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        } else {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            finaly = false;
        }
        if (inputfeelds2.value == "") {
            inputfeelds2.classList.toggle("is-valid", false);
            inputfeelds2.classList.toggle("is-invalid", true);
            finaly = false;
        } else {
            inputfeelds2.classList.toggle("is-valid", true);
            inputfeelds2.classList.toggle("is-invalid", false);
        }
    }
    return finaly;
}

function validateaddinsti() {
    var finaly = true;
    for (var count = 0; count < inputfeelds.length - 1; count++) {
        if (inputfeelds[count].value.length > 2) {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        } else {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            finaly = false;
        }
        if (inputfeelds2.value == "") {
            inputfeelds2.classList.toggle("is-valid", false);
            inputfeelds2.classList.toggle("is-invalid", true);
            finaly = false;
        } else {
            inputfeelds2.classList.toggle("is-valid", true);
            inputfeelds2.classList.toggle("is-invalid", false);
        }
    }
    return finaly;
}

function validateaddclass() {
    var finaly = true;
    for (var count = 0; count < inputfeelds.length; count++) {
        if (inputfeelds[count].value != '') {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        } else {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            finaly = false;
        }
    }
    return finaly;
}

function validateaddgroup() {
    var finaly = true;
    for (let count = 0; count < inputfeelds.length; count++) {
        if (inputfeelds[count].value != "") {
            inputfeelds[count].classList.toggle("is-valid", true);
            inputfeelds[count].classList.toggle("is-invalid", false);
        } else {
            inputfeelds[count].classList.toggle("is-valid", false);
            inputfeelds[count].classList.toggle("is-invalid", true);
            finaly = false;
        }
    }
    if (tags.value == "") {
        finaly = false;
    }
    return finaly;
}

// insert insti validate end

// snippit management end   *********************

// Special function  *********************

// text not sctipt validate 
function potentiallyEscaped(text) {
    const escapedChars = /<|>|&|"|;/g;
    return !escapedChars.test(text);
}

// image prevew
function showImage(src) {
    nthj(6, src);
}

// load script start 
function loadScript(url) {
    const script = document.createElement('script');
    script.src = url; // Set the script source
    document.head.appendChild(script); // Append the script to the head
}

// clear model data 
function clearmodeldata(data) {
    const myModal = document.querySelector(data);
    myModal.addEventListener('hidden.bs.modal', function (event) {
        $('.select-multiple').val(null).trigger('change');
        clearFormData();
    });
}

// clear form data 
function clearFormData() {
    const forms = document.querySelectorAll('#Formclear');
    forms.forEach((form) => {
        const elements = form.elements;

        for (let i = 0; i < elements.length; i++) {
            const element = elements[i];

            switch (element.type) {
                case 'hidden':
                    break;
                case 'text':
                case 'number':
                case 'file':
                case 'password':
                case 'email':
                case 'textarea':
                case 'select':
                    element.value = '';
                    break;
                case 'checkbox':
                case 'radio':
                    element.checked = false;
                    break;
                // Add more cases for other input types if needed
            }
        }
    });
}