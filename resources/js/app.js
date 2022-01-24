require("./bootstrap");

import Echo from "laravel-echo";

window.io = require("socket.io-client");

window.Echo = new Echo({
    broadcaster: "socket.io",
    host: window.location.hostname + ":6001",
});

let onlineUsersCount = 0;

window.Echo.join("online")
    .here((users) => {
        onlineUsersCount = users.length;

        if (users.length > 1) {
            $("#no-online").addClass("d-none");
        }

        users.forEach((user) => {
            let user_id = $("meta[name=user_id]").attr("content");
            if (user.id == user_id) {
                return;
            }

            $("#no-online").addClass("d-none");
            $("#online_users").append(
                `<li id="user-${user.id}" class="list-group-item"><span><i class="fas fa-circle text-success pr-1"></i></span> ${user.name}</li>`
            );
        });
    })
    .joining((user) => {
        onlineUsersCount++;
        $("#online_users").append(
            `<li id="user-${user.id}" class="list-group-item"><span><i class="fas fa-circle text-success pr-1"></i></span> ${user.name}</li>`
        );
    })
    .leaving((user) => {
        onlineUsersCount--;

        if (onlineUsersCount == 1) {
            $("#no-online").addClass("d-block");
        }
        $("#user-" + user.id).remove();
    });

$("#chat-text").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: $("input[name=body]").data("url"),
        data: {
            _token: $("meta[name=csrf-token]").attr("content"),
            body: $("input[name=body]").val(),
        },
        success: function (response) {
            $("input[name=body]").val("");
            $("#chat").append(
                `<div class="mt-4 w-50 p-2 rounded float-right bg-primary text-white">
                ${response.body}
                </div>
                <div class="clearfix"></div>`
            );
        },
    });
});

window.Echo.channel("chat_database_chat-group").listen(
    "MessageDilvered",
    (e) => {
        $("#chat").append(
            `<div class="mt-4 w-50 p-2 rounded float-left bg-light">
            <span class="d-block pb-2" style="font-size: 12px">${e.message.user.name}</span>
            ${e.message.body}
            </div>
            <div class="clearfix"></div>`
        );
    }
);
