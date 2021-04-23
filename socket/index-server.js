
var app = require('express')();
var cors  = require('cors')();

//app.use(cors);

console.log(require('socket.io').version);

app.use(function(req, res, next) {
    res.setHeader("s2Access-Control-Allow-Origin", "https://www.kinnect2.com:80");
    res.setHeader("Access-Control-Allow-Origin", "https://www.kinnect2.com:80");
    res.setHeader("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    res['sAccess-Control-Allow-Origin'] = "https://www.kinnect2.com:80";
    res['Access-Control-Allow-Origin'] = "https://www.kinnect2.com:80";
    console.log("am i being applied each time?");
    next();
});

//app.use(function(req, res, next) {
//      res.header("Access-Control-Allow-Origin", "https://www.kinnect2.com");
//console.log("HEADER APPLIED");
//    res.header("Access-Control-Allow-Headers", "X-Requested-With");
//      res.header("Access-Control-Allow-Headers", "Content-Type");
//        res.header("Access-Control-Allow-Methods", "PUT, GET, POST, DELETE, OPTIONS");
//      next();
// });

//var http = require('https').Server(app);
//var io = require('socket.io')(http);
var _ = require('underscore');


var http = require('https');
var fs = require('fs');
var httpsOptions = {
    key: fs.readFileSync('/etc/ssl/kinnect2/kinnect2.key'),
    cert: fs.readFileSync('/etc/ssl/kinnect2/kinnect2.crt'),
    'Access-Control-Allow-Origin': 'https://www.kinnect2.com:80',
    origins: 'https://www.kinnect2.com:80'
};

//var io = require('socket.io')(http);
//io.set('transports', ["websocket", "polling"]);
//io.origins("https://www.kinnect2.com:*");

//io.set('origins', 'https://www.kinnect2.com:*')
//io.set('match origin protocol', true);

//http.createServer(httpsOptions, app).listen(3000, function(){
//      console.log('Lisitening on port:3000');
//});


var server = http.createServer(httpsOptions, app).listen(3000, function(){
    console.log('listening on *:3000,,, Inside apache');
});

var io = require('socket.io')(server, {origins: "https://www.kinnect2.com:*"});
io.origins("https://www.kinnect2.com:*");
//var persistence = require('./modules/connection');

/// Application Startup ///

/*http.listen(3000, function(){
 console.log('listening on *:3000,,, Inside apache');
 });*/


/////////

var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var _ = require('underscore');

//var persistence = require('./modules/connection');

/// Application Startup ///

http.listen(3000, function(){
    console.log('listening on *:3000,,, Inside apache');
});
var sockets = {};
var groups = {};
//persistence.create_message("hello g!!!");

function getChatSocket(socket){
    return sockets["chat-user-"+socket];
}

function getGroupSocket(socket){
    return sockets["chat-group-"+socket];
}

function setChatSocket(socket, data){
    sockets["chat-user-"+socket] = data;
}

function deleteChatSocket(socket){
    delete sockets["chat-user-"+socket];
}

function joinGroup(groupID, memberID){
    //getChatSocket(memberID).join(groupID);
    if(groups[groupID]){
        groups[groupID][memberID] = memberID;
    }else{
        groups[groupID] = {};
        groups[groupID][memberID] = memberID;
    }
}

function leaveGroup(groupID, memberID){
    delete groups[groupID][memberID];
}

function emitToGroup(groupID, from, message){
    //io.to(to).emit('group_chat_message',message, from, to);
    var obj = groups[groupID];
    for (var prop in obj) {
        console.log(obj[prop])
        var onlineMember = getChatSocket(obj[prop]);
        if(onlineMember){
            onlineMember.emit('group_chat_message',message, from, groupID);
        }
    }
}

function attachmentToGroup(groupID, options){
    //io.to(to).emit('group_chat_message',message, from, to);
    var obj = groups[groupID];
    for (var prop in obj) {
        console.log(obj[prop])
        var sock = getChatSocket(obj[prop]);
        if(sock){
            sock.emit('receive_attachment', options.attachmentOptions);
        }

    }
}



var mysql      = require('mysql');
var connection = mysql.createConnection({
    host     : '127.0.0.1',
    user     : 'root',
    password : 'root',
    database: 'blueorca_k2_laravel_2'
});


connection.connect();

function populateGroupsFromDB(){
    connection.query("select id, user_id from conversations , conv_users where type = 'group' and id = conv_id ORDER by id DESC ", function (err, rows, fields) {
        if (err) {
            // error handling code goes here
            return err;
        }
        _.each(rows, function (row) {
            joinGroup(row.id, row.user_id)
        });
        console.log(groups);


    });

    console.log("Afer Querey");
}

setTimeout(function(){
    populateGroupsFromDB();
}, 1000)

/// ------------- Application Startup END --------------///




/// ------------   ROUTS   ------------ ///

app.use(function(req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    next();
});

app.get('/', function(req, res){
    res.sendfile('index.html');
});


app.post('/getOnlineFriends', function(req, res){
    var userId = "18";
    var onlineFriends = [];
    connection.query("select `user_membership`.*, `users`.`name`, `users`.`username`, `users`.`displayname`, `users`.`user_type`, `users`.`photo_id` from `user_membership` inner join `users` on `users`.`id` = `user_membership`.`user_id` where `resource_id` = '"+userId+"' and `user_membership`.`active` = '1'", function(err, rows, fields) {
        if (err) throw err;

        console.log(rows);

        _.each(rows, function(row) {
            console.log(row.user_id);
            if(getChatSocket(row.user_id)){
                onlineFriends.push(row);
                console.log(onlineFriends);
            }
        });
        res.setHeader('Content-Type', 'application/json');
        res.send({onlineFriends: onlineFriends});
    });


});

/// ------------   ROUTS END   ------------ ///


io.on('connection', function(socket){
    socket.on('come_online', function (userId) {
        socket.soketid = userId;
        setChatSocket(socket.soketid, socket);
        console.log("Total following people are online: "+Object.keys(sockets));


        //var userId = "18";
        var onlineFriends = [];
        var allFriends = [];
        connection.query("select `user_membership`.*, `users`.`name`, `users`.`username`, `users`.`displayname`, `users`.`user_type`, `users`.`photo_id` from `user_membership` inner join `users` on `users`.`id` = `user_membership`.`user_id` where `resource_id` = '"+userId+"' and `user_membership`.`active` = '1'", function(err, rows, fields) {
            if (err) throw err;

            console.log("L: "+rows.length);
            console.log(rows);

            _.each(rows, function(row) {
                allFriends[row.user_id] = row;
                console.log(row.user_id);
                if(getChatSocket(row.user_id)){
                    onlineFriends.push(row);
                    getChatSocket(row.user_id).emit('friend-come-online',socket.soketid);
                    //console.log(onlineFriends);
                }
            });

            console.log("Lddddd: "+onlineFriends.length);
            getChatSocket(userId).emit('friends_online', onlineFriends);
            getChatSocket(userId).emit('all_friends', allFriends);
        });
    });

    socket.on('disconnect', function() {
        var userId = socket.soketid;
        deleteChatSocket(socket.soketid);
        console.log("Remaining people are online: "+Object.keys(sockets));
        var onlineFriends = [];
        connection.query("select `user_membership`.*, `users`.`name`, `users`.`username`, `users`.`displayname`, `users`.`user_type`, `users`.`photo_id` from `user_membership` inner join `users` on `users`.`id` = `user_membership`.`user_id` where `resource_id` = '"+userId+"' and `user_membership`.`active` = '1'", function(err, rows, fields) {
            if (err) throw err;

            console.log(rows);

            _.each(rows, function(row) {
                console.log(row.user_id);
                if(getChatSocket(row.user_id)){
                    onlineFriends.push(row);
                    getChatSocket(row.user_id).emit('friend-go-offline',socket.soketid);
                    console.log(onlineFriends);
                }
            });
            //getChatSocket(userId).emit('friends_online',onlineFriends);
        });
    });

    socket.on('chat_message', function (message, to, from) {

        console.log("chat-user-"+to+"  : "+message+" <<< FROM: "+from);
        getChatSocket(to).emit('chat_message',message, from);
        //var params = {
        //    sender_id: from,
        //    recipient_id: to,
        //    message: message
        //};
        //persistence.save_message(params);

    });

    socket.on('group_chat_message', function (message, to, from) {
        console.log("inside group chat: " +to +", MESSAGE: "+message);
        //io.to(to).emit('group_chat_message',message, from, to);
        emitToGroup(to,from,message);
        //var params = {
        //    sender_id: from,
        //    conv_id: to,
        //    message: message
        //};
        //persistence.save_group_message(params);
        //io.to(to).emit('group_chat_message',message, to, from);
    });

    socket.on('join_group', function (groupID, members) {
        console.log("JOINED_GROUP");
        function memberJoinRoom(memberID, index, array) {
            console.log('a[' + index + '] = ' + memberID);
            joinGroup(groupID, memberID);
            //if(getChatSocket(memberID)){
            //    getChatSocket(memberID).join(groupID);
            //}else{
            //    console.log(memberID+ " (offline) Could not join group/room: "+groupID);
            //}
            console.log("One joined group/room: "+groupID);
        }
        members.forEach(memberJoinRoom);
    });


    socket.on('send_attachment', function (attachmentOptions) {
        console.log("sent_attachment");
        console.log(attachmentOptions);
        if(attachmentOptions.isGroup){
            attachmentToGroup(attachmentOptions.to, {attachmentOptions: attachmentOptions});
        }else{
            getChatSocket(attachmentOptions.to).emit('receive_attachment',attachmentOptions);
        }

    });


    io.sockets.emit('alive_again', true);

    //TODO: leave group event implementation;
});

//connection.end();

// ------- Common Functions -------- //
