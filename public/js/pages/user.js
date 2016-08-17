$(document).ready(function() {
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
});