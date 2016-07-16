$(function () {
    $('body').on('click', 'button[data-btn-type=ajax]', function(e) {
        console.log('click btn');
        console.log($('#mode').val());
        console.log($('#withinOneWeekFlag').val());
        console.log($('#startingPointLatitudo').val());
        console.log($('#startingPointLongitude').val());
        console.log($('#endPointLatitudo').val());
        console.log($('#endPointLongitude').val());
        
        var send_data;
        send_data = {
            mode : $('#mode').val(),
            withinOneWeekFlag : $('#withinOneWeekFlag').val(),
            startingPointLatitudo : $('#startingPointLatitudo').val(),
            startingPointLongitude : $('#startingPointLongitude').val(),
            endPointLatitudo : $('#endPointLatitudo').val(),
            endPointLongitude : $('#endPointLongitude').val()
        };
        console.dir(send_data);
        
        $.ajax({
            url: 'api/v1/hostlist.php',
            dataType: "json",
            data: send_data,
        })
        .done(function(response){
            console.dir(response);
            $('div[data-result=""]').html(JSON.stringify(response));

            return false;
        })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            $('div[data-result=""]').html(XMLHttpRequest.status + ' : ' + errorThrown);
            return false;
        });
        
        $('input').focus();
        return false;
    });
});