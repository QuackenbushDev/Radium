$(document).ready(function() {
    $('#testUserConnectivity').click(function() {
        $('#testConnectivityModal').modal({show: true});
    });

    $('#disconnectUser').click(function() {
        $('#disconnectUserModal').modal({show: true});
    });

    $('button[name=add_user_group_button]').click(function() {
        var groupName = $('input:text[name=add_user_group_input]').val();

        if (groupName != '') {
            $('#user_groups').append('<option selected>' + groupName + '</option>');
        }
    });
});