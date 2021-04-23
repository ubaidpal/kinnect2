/**
 * Created by Admin on 02-1-16.
 */
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var _ = require('underscore');
var mysql = require('./modules/connection');
var sockets = {};
var groups = {};
http.listen(3000, function() {
    console.log('listening on *:3000,,, Inside apache');
    // mysql.connection.connect();
    mysql.get_groups(function (err, groupsData) {
        if (err) {
            // error handling code goes here
           return err;
        } else {
            // code to execute on data retrieval
            _.each(groupsData, function (row) {
                joinGroup(row.id, row.user_id)
            });
            console.log(groups)
        }
    });

});

function joinGroup(groupID, memberID){
    //getChatSocket(memberID).join(groupID);
    if(groups[groupID]){
        groups[groupID][memberID] = memberID;
    }else{
        groups[groupID] = {};
        groups[groupID][memberID] = memberID;
    }
}
