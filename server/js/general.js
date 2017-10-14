$(document).ready(function() {
        var userid = $("#header-username").attr("data-id");
        var inviteArr = [];
        var notifyArr = [];

        var boxArr = [];
        
        function notifications() {
            var time = $("#time").text();
            $.ajax({
                type: 'GET',
                url: 'server/get-notifications.php',
                data: {id: userid},
                dataType: 'json',
                success: function (data) {
                    for (var n = 0; n < data.length; n++){
                        var text = data[n].message+".";
                        var notid = data[n].notid;
                        var type = data[n].type;
                        var parseData = text+"&%"+notid+"&%"+type;

                        if($.inArray(parseData, notifyArr) === -1){
                           notifyArr.push(parseData);
                        }
                    }    
                },
                complete: function (data) {
                        // Schedule the next
                        setTimeout(invites, 0);
                        for(var x = 0; x < notifyArr.length; x++){
                            var str = notifyArr[x];

                            var res = str.split("&%");
                            var src;

                            if(res[2] === "project"){
                                src = "resources/img/icon/check-box.svg";
                            } else if(res[2] === "contact"){
                                src = "resources/img/icon/notebook.svg";
                            } else if(res[2] === "task"){
                                src = "resources/img/icon/checkmark.svg";
                            } else if(res[2] === "client"){
                                src = "resources/img/icon/business-group.svg";
                            } else if(res[2] === "invite"){
                                src = "resources/img/icon/calendar.svg";
                            } else if(res[2] === "event"){
                                src = "resources/img/icon/bell.svg";
                            }
                            var notifyBox = "<li id=\"not-"+res[1]+"\"> \
                            <img src=\""+src+"\"> \
                            <p>"+res[0]+"</p> \
                            <div> \
                                <a href=\"#\" id=\"dismiss\" class=\"dismiss\" data-id-note=\""+res[1]+"\">Dismiss</a> \
                            </div> \
                        </li>";
                            if($.inArray(notifyBox, boxArr) === -1){
                                boxArr.push(notifyBox);
                            }
                        }
                        if(boxArr.length > 0){
                            $("#notifyNum").text(boxArr.length);
                        } else {
                            $("#notifyNum").text("");
                        }
                        $("header > div > a > ul").empty();
                        $("header > div > a > ul").append(boxArr);
                }
            });
        }
        function invites(){
            $.ajax({
               type: 'GET',
               url: 'server/get-invites.php',
               data: {id: userid},
               dataType: 'json',
               success: function (data) {
                    for (var c = 0; c < data.length; c++){
                        var text = data[c].name+" has invited you to attend \""+data[c].for+"\".";
                        var eventid = data[c].evid;
                        var parseData = text+"&%"+eventid;

                        if($.inArray(parseData, inviteArr) === -1){
                            inviteArr.push(parseData);
                        }
                    }
               },
               complete: function (data) {
                       // Schedule the next
                       setTimeout(notifications, 10000);
                        for(var x = 0; x < inviteArr.length; x++){
                            var str = inviteArr[x];

                            var res = str.split("&%");
                            var notifyBox = "<li id=\"not-"+res[1]+"\"> \
                            <img src=\"resources/img/icon/calendar.svg\"> \
                            <p>"+res[0]+"</p> \
                            <div> \
                                <a href=\"#\" class=\"invite-choose\" id=\"accept-invite\" data-id-event=\""+res[1]+"\" data-toggle=\"2\">Accept</a> \
                                <a href=\"#\" class=\"invite-choose\" id=\"dismiss\" data-id-event=\""+res[1]+"\" data-toggle=\"0\">Decline</a> \
                            </div> \
                        </li>";
                            if($.inArray(notifyBox, boxArr) === -1){
                                boxArr.unshift(notifyBox);
                            }
                            if(boxArr.length > 0){
                                $("#notifyNum").text(boxArr.length);
                            } else {
                                $("#notifyNum").text("");
                            }
                            $("header > div > a > ul").empty();
                            $("header > div > a > ul").append(boxArr);

                        }
                       
               }
            });
        }
        notifications();


        $("header > div > a > ul").on("click", ".dismiss", function(){
            var notid = $(this).attr("data-id-note");
            $.ajax({
                type: 'GET',
                url: 'server/dismiss.php',
                data: {id: notid},
                success: function (data) {
                    var boxcheck = $("#not-"+notid);
                    
                    inviteArr = [];
                    notifyArr = [];
                    boxArr = [];
                    notifications();
                }
           });
        });

        $("header > div > a > ul").on("click", ".invite-choose", function(){
            var notid = $(this).attr("data-id-event");
            var choice = $(this).attr("data-toggle");
            $.ajax({
                type: 'POST',
                url: 'server/respond-to-event.php',
                data: {id: notid,
                    choice: choice,
                    userid: userid},
                success: function (data) {
                    alert(data);
                    
                    var boxcheck = $("#not-"+notid);
                    boxcheck.remove();

                    inviteArr = [];
                    notifyArr = [];
                    boxArr = [];
                    notifications();
                }
           });
        });

        setInterval(function(){

                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();

                // Add leading zeros
                minutes = (minutes < 10 ? "0" : "") + minutes;
                hours = (hours < 10 ? "0" : "") + hours;

                // Compose the string for display
                var currentTimeString = hours + ":" + minutes;
                $("#time").html(currentTimeString);

	   },1000);  

        $("#back-end-pages > nav > ul > li > ul > li").on("click", function(){
                var status = $(this).text();
                var id = $("#header-username").data("id");
                $.ajax({
                        type: "POST",
                        url: "server/change-status.php",
                        data: {status: status, 
                                id: id},
                        success: function(stat){

                                if($.trim(stat) == "Online"){
                                       $("#headstat").css("background-color", "#88D54F"); 
                                } else if($.trim(stat) == "Away"){
                                        $("#headstat").css("background-color", "#FACF00"); 
                                } else if($.trim(stat) == "Busy"){
                                        $("#headstat").css("background-color", "#A80F0F"); 
                                } else if($.trim(stat) == "Do Not Disturb"){
                                        $("#headstat").css("background-color", "#FF0000"); 
                                } else if($.trim(stat) == "Out of Office"){
                                        $("#headstat").css("background-color", "grey"); 
                                }
                        }
                });
        });
});
        