var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var _ = require('underscore');



/// Application Startup ///

http.listen(3000, function(){
    console.log('listening on *:3000,,, Inside apache');
});
var sockets = {}


var mysql      = require('mysql');
var connection = mysql.createConnection({
    host     : '127.0.0.1',
    user     : 'root',
    password : 'root',
    database: 'blueorca_k2_laravel_2'
});

connection.connect();


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
            if(sockets["chat-user-"+row.user_id]){
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
        sockets["chat-user-"+socket.soketid] = socket;
        console.log("Total following people are online: "+Object.keys(sockets));


        //var userId = "18";
        var onlineFriends = [];
        connection.query("select `user_membership`.*, `users`.`name`, `users`.`username`, `users`.`displayname`, `users`.`user_type`, `users`.`photo_id` from `user_membership` inner join `users` on `users`.`id` = `user_membership`.`user_id` where `resource_id` = '"+userId+"' and `user_membership`.`active` = '1'", function(err, rows, fields) {
            if (err) throw err;

            console.log(rows);

            _.each(rows, function(row) {
                console.log(row.user_id);
                if(sockets["chat-user-"+row.user_id]){
                    onlineFriends.push(row);
                    sockets["chat-user-"+row.user_id].emit('friend-come-online',socket.soketid);
                    console.log(onlineFriends);
                }
            });
            sockets["chat-user-"+userId].emit('friends_online',onlineFriends);
        });
    });

    socket.on('disconnect', function() {
        delete sockets["chat-user-"+socket.soketid];
        console.log("Remaining people are online: "+Object.keys(sockets));
        var onlineFriends = [];
        connection.query("select `user_membership`.*, `users`.`name`, `users`.`username`, `users`.`displayname`, `users`.`user_type`, `users`.`photo_id` from `user_membership` inner join `users` on `users`.`id` = `user_membership`.`user_id` where `resource_id` = '"+userId+"' and `user_membership`.`active` = '1'", function(err, rows, fields) {
            if (err) throw err;

            console.log(rows);

            _.each(rows, function(row) {
                console.log(row.user_id);
                if(sockets["chat-user-"+row.user_id]){
                    onlineFriends.push(row);
                    sockets["chat-user-"+row.user_id].emit('friend-go-offline',socket.soketid);
                    console.log(onlineFriends);
                }
            });
            sockets["chat-user-"+userId].emit('friends_online',onlineFriends);
        });
    });

    socket.on('chat_message', function (message, to, from, fromName) {
        console.log("chat-user-"+to+"  : "+message+" <<< FROM: "+from);
        sockets["chat-user-"+to].emit('chat_message',message, from, fromName);
    });
});

//connection.end();

// ------- Common Functions -------- //