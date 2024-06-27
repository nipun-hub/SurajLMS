const inputField = document.querySelector(".chatbox-message-input"),
    sendBtn = document.querySelector(".chatbox-message-submit"),
    chatBox = document.querySelector(".chatbox-message-content"),
    url = new URL(window.location.href);
// incoming_id = url.searchParams.get('user_id');
var chat = 0;
var chatContent = "";


// INSERT MASSAGE
sendBtn.onclick = (e) => {
    e.preventDefault();
    if (isValid(inputField.value)) {
        const PassData = new FormData();
        PassData.append('insertMassage', '1'); // Assuming 'insertMassage' is a flag
        // PassData.append('incoming_id', incoming_id);
        PassData.append('message', inputField.value);
        $.ajax({
            url: "chat/php/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                inputField.value = "";
                // writeMessage();
                scrollToBottom();
            }
        });
    }
}
chatBox.onmouseenter = () => {
    chatBox.classList.add("active");
}

chatBox.onmouseleave = () => {
    chatBox.classList.remove("active");
}


// update chat 
setInterval(() => {
    if (chatboxMessage.classList.contains("show")) {
        const PassData = new FormData();
        chat == 0 ? PassData.append('GetChat', '') : PassData.append('updateChat', '');
        // PassData.append('incoming_id', incoming_id);
        $.ajax({
            url: "chat/php/process.php", type: "POST", data: PassData, processData: false, contentType: false, success: function (response, status) {
                if (response.length > 5) {
                    var nowLength = chatBox.scrollTop;
                    // chat == 0 ? chatBox.innerHTML = response : chatBox.innerHTML = response;
                    // if (!chatBox.classList.contains("active")) {
                    //     chat == 0 ? scrollToBottom() : scrollToBottom();
                    // }
                    if (!(chatContent == response)) {
                        chatBox.innerHTML = response
                        chatContent = response;
                        scrollToBottom();
                    }
                }
            }
        });
        chat == 0 ? chat++ : null;
    }
}, 500);

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

function scrollToNow(value) {
    chatBox.scrollTop = value;
}