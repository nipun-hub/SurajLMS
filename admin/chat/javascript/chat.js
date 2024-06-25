const userBody = document.querySelector(".userBody");
const userChat = document.querySelector(".userChat");
let userChatContent = document.querySelector(".chats");

let activeChat = 0;
let selectedStuCount = 0;
let UserList = {
    Status: false,
    Data: null
};
let chatContent = "";
let counter = 0;
var nameBar;
var sendBox;

getUserList();
// loadUserBody();

setInterval(() => {
    getUserList();
    // loadChat();
}, 1000);

function setActiveChat(id) {
    activeChat = id;
    // console.log("setted active chat is : " + id);
    counter = 0;
    nameBar, sendBox = "";
    loadChat();
}

function sendMassage(data, id) {
    var massage = data.parentNode.querySelector('input').value;
    if (isValid(massage)) {
        console.log("massage : " + massage, " id " + id);
        const PassData = new FormData();
        PassData.append("massage", massage);
        PassData.append("id", id);
        PassData.append('sendMassage', '');
        $.ajax({
            url: "chat/php/process.php", type: "POST", data: PassData, processData: false, contentType: false, dataType: "text", success: function (response, status) {
                console.log(status);
                data.parentNode.querySelector('input').value = "";
                scrollToBottom();
            }
        });
    }
}

function updateUserBody() { // not use now
    if (Object.keys(UserList.Data).length > selectedStuCount) {
        let userBodyContent = ``;
        for (i = selectedStuCount; i < Object.keys(UserList.Data).length; i++) {
            let userDetails = UserList.Data[i] || [];
            userBodyContent += `
                    <div class="bg-white activity-block m-0 p-2 border-bottom border-2 position-relative" onclick="setActiveChat(${userDetails[0]})">
                        <div class="activity-user">
                            <img src="${userDetails[1]}" alt="Activity User" onerror="notImage(this)">
                        </div>
                        <div class="activity-details">
                            <h4>${userDetails[2]}</h4>
                            <h5>${userDetails[3]}</h5>
                            <p>${userDetails[4]}</p>
                        </div>
                        ${userDetails[5] == 0 ? "" : "<span class='position-absolute top-0 end-0 m-2  plus sm'>" + userDetails[5] + "</span>"}
                    </div>`;
        }
        let newDiv = document.createElement("div");
        newDiv.innerHTML = userBodyContent;
        userBody.insertBefore(newDiv, userBody.firstChild);
        selectedStuCount = Object.keys(UserList.Data).length;
    }
}

function loadUserBody() {
    if (UserList.Status == true && UserList.Data.length > 0) {
        let userBodyContent = ``;
        for (let userId in UserList.Data) {
            if (UserList.Data.hasOwnProperty(userId)) {
                let userDetails = UserList.Data[userId];
                userBodyContent += `
                <div class="bg-white activity-block m-0 p-2 border-bottom border-2 position-relative" onclick="setActiveChat(${userDetails[0]})">
                    <div class="activity-user">
                        <img src="${userDetails[1]}" alt="user" onerror="notImage(this)">
                    </div>
                    <div class="activity-details w-100">
                        <h4 class="long-text1">${userDetails[2]}</h4>
                        <h5 class="long-text1">${userDetails[3]}</h5>
                        <p class="long-text1">${userDetails[4]}</p>
                    </div>
                     ${userDetails[5] == 0 ? "" : "<span class='position-absolute top-0 end-0 m-2  plus sm'>" + userDetails[5] + "</span>"}
                </div>`;
            }
        }
        userBody.innerHTML = userBodyContent;
        selectedStuCount = Object.keys(UserList.Data).length;
    } else {
        userBody.innerHTML = `<p class="text-dark text-center">Massage Not Found &nbsp;<i class="bi bi-search"></i></p>`;
    }
}

function loadChat() {
    if (activeChat != 0) {
        if (counter == 0) {
            // console.log("load header : " + activeChat);
            const filteredData = UserList.Data.filter(user => user[0].includes(activeChat));
            var nameBar = `
                <div class="col-12 activity-container mt-0">
                    <div class="card-header bg-white activity-block p-2">
                        <div class="">
                            <img src="${filteredData[0][1]}" alt="Activity User" onerror="notImage(this)" width="50" class="rounded-circle">
                        </div>
                        <div class="activity-details ms-3">
                            <h4>${filteredData[0][2]}</h4>
                            <h5>${filteredData[0][3]}</h5>
                        </div>
                    </div>
                </div>
                `;
            var chatMiddle = `
                <div class='col-12'>
                    <div class='mt-1 mx-2'>
                        <div class='card-body vh60  overflow-y-scroll'>
                            <ul class='chats  bottom-0 w-100 pt-3'>
                            </ul>
                        </div>
                    </div>
                </div>`;
            var sendBox = `
                <div class="col-12 ">
                    <div class="card">
                        <div class="card-body row d-flex">
                            <div class="col-12">
                                <div class="search-container">
                                    <div class="input-group w-100">
                                        <input type="text" class="form-control searchInp" style="background-color: #dae0e9;" placeholder="Type massage">
                                        <button class="btn" type="button" style="transform: rotate(45deg);" onclick="sendMassage(this,${filteredData[0][0]})">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            userChat.innerHTML = nameBar + chatMiddle + sendBox;
        }
        userChatContent = document.querySelector(".chats");
        if (counter == 0) {
            const PassData = new FormData();
            PassData.append('getChat', '');
            PassData.append('id', activeChat);
            $.ajax({
                url: "chat/php/process.php", type: "POST", data: PassData, processData: false, contentType: false, dataType: "text", success: function (response, status) {
                    userChatContent.innerHTML = response;
                    scrollToBottom();
                    console.log("geted");
                }
            });
            counter++;
        }
        if (counter != 0) {
            setInterval(() => {
                const PassData = new FormData();
                PassData.append('updateChat', '');
                PassData.append('id', activeChat);
                $.ajax({
                    url: "chat/php/process.php", type: "POST", data: PassData, processData: false, contentType: false, dataType: "text", success: function (response, status) {
                        var newRespons = JSON.parse(response);
                        if (!(chatContent == newRespons.Data)) {
                            userChatContent.innerHTML = newRespons.Data
                            chatContent = newRespons.Data;
                            console.log("updated : " + newRespons.Status);
                        }
                        // console.log(newRespons.Data + ":" + chatContent);
                    }
                });
            }, 1000);
        }
    }
}

function getUserList() {
    const PassData = new FormData();
    PassData.append('getUser', '');
    $.ajax({
        url: "chat/php/process.php", type: "POST", data: PassData, processData: false, contentType: false, dataType: "text", success: function (response, status) {
            var responseList = JSON.parse(response);
            var areEqual = JSON.stringify(UserList.Data) === JSON.stringify(responseList.Data);
            if (!areEqual) {
                UserList = responseList;
                loadUserBody();
            }
        }
    });
}

function searchChat(Data) {
    let filteredData = UserList.Data.filter(user => user[2].toLowerCase().includes(Data) || user[3].toLowerCase().includes(Data));
    console.log();
    if (filteredData.length > 0) {
        let userBodyContent = ``;
        filteredData.forEach(e => {
            userBodyContent += `
                <div class="bg-white activity-block m-0 p-2 border-bottom border-2 position-relative" onclick="setActiveChat(${e[0]})">
                    <div class="activity-user">
                        <img src="${e[1]}" alt="user" onerror="notImage(this)">
                    </div>
                    <div class="activity-details w-100">
                        <h4 class="long-text1">${e[2]}</h4>
                        <h5 class="long-text1">${e[3]}</h5>
                        <p class="long-text1">${e[4]}</p>
                    </div>
                     ${e[5] == 0 ? "" : "<span class='position-absolute top-0 end-0 m-2  plus sm'>" + e[5] + "</span>"}
                </div>`;
        });
        userBody.innerHTML = userBodyContent;
        // selectedStuCount = Object.keys(UserList.Data).length;
    } else {
        userBody.innerHTML = `<p class="text-dark text-center">Massage Not Found &nbsp;<i class="bi bi-search"></i></p>`;
    }
}

function isValid(value) {
    let text = value.replace(/\n/g, '')
    text = text.replace(/\s/g, '')
    return text.length > 0;
}

function scrollToBottom() {
    userChatContent.parentNode.scrollTop = userChatContent.parentNode.scrollHeight;
}

function notImage(self) {
    self.src = 'assets/img/site use/user.jpeg';
}


