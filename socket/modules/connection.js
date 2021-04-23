/**
 * Created by Admin on 31-12-15.
 */
var mysql = require('mysql');
var _ = require('underscore');
var connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: '',
    database: 'kinnect2_test'

});

exports.connection = connection;
connection.connect();

exports.save_message = function (params) {

    connection.query('SELECT conv_id FROM conv_users WHERE conv_users.conv_id IN(SELECT conv_id FROM conv_users cu WHERE cu.user_id=' + params.recipient_id + ' OR cu.user_id=' + params.sender_id + ' group by cu.conv_id HAVING COUNT(cu.conv_id) = 2) group BY conv_id HAVING COUNT(conv_id)  =  2', function (err, rows, fields) {
        if (rows.length > 0) {
            _.each(rows, function (row) {
                var conv_id = row.conv_id;
                save_message_user(params, conv_id);
            });
        } else {
            create_conversation(params);
        }
    });


};

exports.save_group_message = function (params) {
    console.log("Sender ID: " + params.sender_id + ", Conversation ID: " + params.conv_id + ", Message: " + params.message);
    save_message_user(params, params.conv_id);
};


//Create Conversation
create_conversation = function (params) {
    connection.query("insert into `conversations` (`created_by`) values (" + params.sender_id + ")", function (err, rows, fields) {
        if (err) throw err;
        var conv_id = rows.insertId;
        var users = [];
        users.push(params.sender_id);
        users.push(params.recipient_id);
        if (exports.add_user_to_conversation(conv_id, users)) {
            save_message_user(params, conv_id);
        }

    })
};
// Save individual Message
save_message_user = function (params, conv_id) {
    connection.query("insert into `messages` (`sender_id`, `conv_id`, `content`) values (" + params.sender_id + ", " + conv_id + ", '" + params.message + "')", function (err, rows, fields) {
        if (err) throw err;
        var message_id = rows.insertId;
        save_message_status(conv_id, message_id, params.sender_id);
    });
};

// add users to conversation
exports.add_user_to_conversation = function (conv_id, users) {
    _.each(users, function (row) {
        connection.query("insert into `conv_users` (`conv_id`, `user_id`) values (" + conv_id + ", " + row + ")")
    });
    return true;
};

// Save message status
save_message_status = function (conv_id, message_id, sender) {
    connection.query("SELECT cu.user_id FROM conv_users cu WHERE cu.conv_id=" + conv_id, function (err, rows, fields) {
        if (err) throw err;
        console.log(rows);
        _.each(rows, function (row) {
            var user = row.user_id;
            if (user == sender) {
                var values = "(" + user + ", " + message_id + ", '1', '2')"
            } else {
                var values = "(" + user + ", " + message_id + ", '0', '1')"
            }
            connection.query("insert into `messages_status` (`user_id`, `msg_id`, `self`, `status`) values " + values)
        });
    });
};
///// --------------------------------------- /////
exports.create_message = function (param) {
    console.log(param);
    return false;
    //connection.connect();
    connection.query("INSERT INTO `chat_messages`( `sender_id`, `sender_type`, `receiver_id`, `receiver_type`, `chat_message`) VALUES (70,'user',25,'user','Test Message for ZAHID')");
};

exports.read_message = function (param) {
    //connection.connect();
    return connection.query("SELECT * FROM `chat_messages`", function (err, rows, fields) {

        var onlineFriends = [];
        _.each(rows, function (row) {
            console.log(row.sender_id);
            onlineFriends.push(row);
            //console.log(onlineFriends);

        });
        return onlineFriends;
    });
};
exports.get_groups = function(callback){
    connection.query("select id, user_id from conversations , conv_users where type = 'group' and id = conv_id ORDER by id DESC ", function (err, rows, fields) {
        var groups = [];
        _.each(rows, function (row) {
            //console.log(row.sender_id);
            groups.push(row);
        });
        //console.log(err);
        callback(err, groups);
    });
};

exports.get_messages = function (page, callback) {
    var per_page = 5;
    var start_point = (page * per_page) - per_page;

    var query = "ORDER by id DESC";

    connection.query('SELECT * FROM `chat_messages`' + query + ' LIMIT ' + start_point + ',' + per_page, function (err, rows, fields) {
        var messages = [];
        _.each(rows, function (row) {
            //console.log(row.sender_id);
            messages.push(row);
        });
        //console.log(err);
        callback(err, messages);
    });

};
