    <script>
        loadCSS('chat/style.css');
        loadCSS('https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css');
        function loadCSS(href) {
            const linkElement = document.createElement("link");
            linkElement.rel = "stylesheet";
            linkElement.type = "text/css";
            linkElement.href = href;
            document.head.appendChild(linkElement);
        }
    </script>
    <div class="chatbox-wrapper">
        <div class="chatbox-toggle">
            <i class='bx bx-message-dots'></i>
        </div>
        <div class="chatbox-message-wrapper">
            <div class="chatbox-message-header">
                <div class="chatbox-message-profile">
                    <img src="assets/img/site use/admin/admin.jpg" alt="Admin" class="chatbox-message-image">
                    <div>
                        <h4 class="chatbox-message-name">Chat With admin</h4>
                        <p class="chatbox-message-status">surajskumara.lk</p>
                    </div>
                </div>
                <div class="chatbox-message-dropdown">
                    <i class='bx bx-dots-vertical-rounded chatbox-message-dropdown-toggle'></i>
                    <ul class="chatbox-message-dropdown-menu">
                        <li>
                            <a href="#">Search</a>
                        </li>
                        <li>
                            <a href="#">Report</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="chatbox-message-content">
                <h4 class="chatbox-message-no-message">You don't have message yet!</h4>

                <div class="chatbox-message-item first" style='--index:1'>
                    <span class="chatbox-message-item-text">
                        Hello, <?php echo $_SESSION['username']; ?>
                    </span>
                    <!-- <span class="chatbox-message-item-time">08:30</span> -->
                </div>
                <div class="chatbox-message-item first" style='--index:2'>
                    <span class="chatbox-message-item-text">
                        how can we help you today?
                    </span>
                    <span class="chatbox-message-item-time">08:30</span>
                </div>
                <!-- <div class="chatbox-message-item sent">
					<span class="chatbox-message-item-text">
						Lorem, ipsum, dolor sit amet consectetur adipisicing elit. Quod, fugiat?
					</span>
					<span class="chatbox-message-item-time">08:30</span>
				</div> -->


            </div>
            <div class="chatbox-message-bottom">
                <!-- <button type="submit" class="chatbox-message-submit"><i class='bx bx-plus-circle'></i></button> -->
                <form action="#" class="chatbox-message-form">
                    <textarea rows="1" placeholder="Type message..." class="chatbox-message-input"></textarea>
                    <button type="submit" class="chatbox-message-submit" style="transform: rotate(45deg);"><i class='bi bi-send'></i></button>
                </form>
            </div>
        </div>
    </div>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> -->
    <script src="chat/javascript/script.js"></script>
    <script src="chat/javascript/chat.js"></script>