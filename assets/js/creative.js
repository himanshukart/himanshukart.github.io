(function() {

    //change login and sign up form and vice versa
    $('.message a').click(function() {
        $('form').animate({
            height: "toggle",
            opacity: "toggle"
        }, "slow");
    });

})();

/**
 * [add_notify function to add user's publisher or subscribers based on type passed to it]
 * @param  {[string]} type [publisher or subscribers]
 * @return {[void]}      [description]
 */
function add_notify(type) {
    //intializing array
    var checkedBox = [];
    //call getAllChecked function and get all checked checkedBox value in array
    checkedBox = getAllChecked();
    //send ajax to add publisher or subscribers on user list
    $.ajax({
        type: 'POST',
        data: {
            'array': checkedBox,
            'type': type
        },
        url: '/users/add_notify'
    }).done(function(response) {
        location.reload();
    });
}
/**
 * [remove_notify function to remove user's publisher or subscribers based on type passed to it]
 * @param  {[string]} type [publisher or subscribers]
 * @return {[vooid]}      [description]
 */
function remove_notify(type) {
    //intializing array
    var checkedBox = [];
    //call getAllUnchecked function and get all unchecked checkedBox value in array
    checkedBox = getAllUnchecked();
    //send ajax to remnove publisher or subscribers on user list
    $.ajax({
        type: 'POST',
        data: {
            'array': checkedBox,
            'type': type
        },
        url: '/users/remove_notify'
    }).done(function(response) {
        location.reload();
    });
}
/**
 * [getAllUnchecked functiotn to get all unchecked checkbox in page]
 * @return {[array]} [all uncheked checkbox value ]
 */
function getAllUnchecked() {
    //intilizing array
    var data = [];
    //get all unchecked checkbox value in array using .each
    $("input:checkbox:not(:checked)").each(function(i) {
        data[i] = $(this).val();
    });
    return data;
}
/**
 * [getAllChecked functiotn to get all checked checkbox in page]
 * @return {[array]} [all cheked checkbox value ]
 */
function getAllChecked() {
    //intilizing array
    var data = [];
    //get all checked checkbox value in array using .each
    $("input[type=checkbox]:checked").each(function(i) {
        data[i] = $(this).val();
    });
    return data;
}
/**
 * [update_user_name function to upadte user's user name]
 * @return {[type]} [description]
 */
function update_user_name() {
    //get updated user name
    var $username = $('#username').val();
    $.ajax({
        type: 'POST',
        data: {
            'username': $username
        },
        url: '/users/change_user_info'
    }).done(function(response) {
        location.reload();
    });
}
/**
 * [update_user_password function to upadte user's passowrd]
 * @return {[type]} [description]
 */
function update_user_password() {
    //get updated users password
    var $password = $('#password').val();
    $.ajax({
        type: 'POST',
        data: {
            'password': $password
        },
        url: '/users/change_user_info'
    }).done(function(response) {
        //refresh when success response
        location.reload();
    });
}
/**
 * [delete_account function to delete user account]
 * @return {[type]} [description]
 */
function delete_account() {
    //send ajax to delete account
    $.ajax({
        type: 'POST',
        url: '/users/delete_account'
    }).done(function(response) {});
}

//set interval of 20 seconds and get notification count for unread messages
/**
 * [setInterval is a function which executes it's code after certain time given to it]
 * @param {[type]} function( [description]
 */
setInterval(function() {
    //ajax call to get notification count
    $.ajax({
        type: 'POST',
        url: '/users/get_notification_count'
    }).done(function(response) {
        var notifyObj = JSON.parse(response);
        //if count lenght greater then 0 append the responne
        if (notifyObj.data > 0) {
            $('#notification').addClass('notification-badge');
            $('#notification').text(notifyObj.data);
        }

    });
}, 20000);
