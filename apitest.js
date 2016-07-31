$(function () {
    $('body').on('click', 'button[data-btn-type=ajax]', function(e) {
        console.log('click btn');
        console.log($('#mode').val());
        console.log($('#withinOneWeekMode').val());
        console.log($('#startingPointLatitude').val());
        console.log($('#startingPointLongitude').val());
        console.log($('#endPointLatitude').val());
        console.log($('#endPointLongitude').val());
        
        var send_data;
        send_data = {
            mode : $('#mode').val(),
            withinOneWeekMode : $('#withinOneWeekMode').val(),
            startingPointLatitude : $('#startingPointLatitude').val(),
            startingPointLongitude : $('#startingPointLongitude').val(),
            endPointLatitude : $('#endPointLatitude').val(),
            endPointLongitude : $('#endPointLongitude').val()
        };
        console.dir(send_data);
        
        $.ajax({
            type: 'GET',
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
        
        return false;
    });
});