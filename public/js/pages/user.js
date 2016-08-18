$(document).ready(function() {
    console.log("MOO!");

    $('#testConnectivityButton').click(function() {
        $('#testConnectivityModal').on('show', function() {
            $('#testConnectivityiFrame').attr('src', testConnectivityiFrameSource);
        });

        $('#testConnectivityModal').modal({show: true});
    });

    $('#disconnectUser').click(function() {
        $('#disconnectModal').on('show', function() {
            $('#disconnectiFrame').attr('src', disconnectiFrameSource);
        });

        $('#disconnectModal').modal({show: true});
    });

    $('button[name=add_user_group_button]').click(function() {
        var groupName = $('input:text[name=add_user_group_input]').val();

        if (groupName != '') {
            $('#user_groups').append('<option selected>' + groupName + '</option>');
        }
    });
});