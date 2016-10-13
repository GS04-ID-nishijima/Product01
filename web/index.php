<?php
include __DIR__ . '/htmlparts/header_parts.php';
?>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="header-title"><a href="./">Favorite Marche</a></h2>
                </div>
            </div>
        </div>
    </header>
    <div>
        <div class="container">
            <div class="row hostinfo-row">
                <div class="col-md-4 hostinfo-col col-md-push-8">
                    <ul class="nav nav-pills nav-justified">
                        <li class="active"><a href="#hostinfo_list_oneweek" data-toggle="pill">1週間以内</a></li>
                        <li><a href="#hostinfo_list_all" data-toggle="pill">すべて</a></li>
                    </ul>
                    <div class="hostinfo-list hidden-xs hidden-sm tab-content">
                        <div id="hostinfo_list_oneweek" class="tab-pane active">
                        </div>
                        <div id="hostinfo_list_all" class="tab-pane">
                        </div>
                    </div>
                </div>
                <div class="col-md-8 hostinfo-col col-md-pull-4">
                    <div id="hostinfomap" class="border-solid map-col">
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="latest-photo-col border-solid">
                        <p class="latest-photo-title">最近アップロードされた写真</p>
                        <div class="top-carousel-box">
                            <div class="top-carousel" id="top_carousel">
                            </div>
                        </div>
                     </div>
                </div>
            </div>
        </div>
<?php
include __DIR__ . '/htmlparts/footer_parts.php';
?>
    </div>

<script>
var dispInfoWindow;
var marcheMap;
var rangeMode = HOSTINFO_LIST_RANGEMODE_ONE;
var preMarkerDataArray = new Array();

$(document).ready(function(){
    // 最新アップロード写真取得
    var sendData;
    sendData = {
        number : 15
    };

    var requet = $.ajax({
        type: 'GET',
        url: 'api/v1/latestphotolist.php',
        cashe: false,
        dataType: "json",
        data: sendData,
        timeout: 10000
    });
    requet.done(function(responseList){
        var topCrouselHtml = "";
        for(var responseListArrayKey in responseList){
            var photoList = responseList[responseListArrayKey];
            var carousel_photo_template;
            if(_ua.Mobile) {
                carousel_photo_template = Handlebars.compile($('#carousel_photo_template_sp').html());
            } else {
                carousel_photo_template = Handlebars.compile($('#carousel_photo_template_except_sp').html());
            }
            topCrouselHtml = topCrouselHtml + carousel_photo_template(photoList);
        }
        $('#top_carousel').html(topCrouselHtml);

        // カルーセル対応
        $('.top-carousel').slick({
            infinite: false,
            slidesToShow: 5,
            slidesToScroll: 3,
            variableWidth: true
        });
    });
});

$(document).ajaxError(function(){
        console.log('fail');
        console.log(XMLHttpRequest);
});

// google Map 情報ウィンドウ表示
function attachMessage(marker, msg) {
    google.maps.event.addListener(marker, 'click', function(evnt) {
        if(dispInfoWindow) dispInfoWindow.close();
        dispInfoWindow = new google.maps.InfoWindow({
            content: msg
        });
        dispInfoWindow.open(marker.getMap(), marker);
    })
};

// Map初期化
function initMap() {
    // 初期表示地、仮
    var lat = 35.662107;
    var lon = 139.718568;

    marker_list = new google.maps.MVCArray();

    // #hostinfomap「GoogleMap」化
    marcheMap = new google.maps.Map(document.getElementById('hostinfomap'), {
       center: {lat: lat, lng: lon},
       zoom: 13
    });

    google.maps.event.addListener(marcheMap, 'idle', function(){
        viewHostinfoList(rangeMode, marcheMap.getBounds());
    });
};

// 開催情報クリック
function hostinfolist_click(i){
    google.maps.event.trigger(marker_list.getAt(i), "click");
};

// 開催情報タブ表示切替
$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {

    var id = $(e.target).attr("href");
    var bounds = marcheMap.getBounds();

    if(id === '#hostinfo_list_oneweek') {
        rangeMode = HOSTINFO_LIST_RANGEMODE_ONE;
    } else if(id === '#hostinfo_list_all') {
        rangeMode = HOSTINFO_LIST_RANGEMODE_ALL;
    }

    viewHostinfoList(rangeMode, bounds);
});

// 開催情報取得、html描画
function viewHostinfoList(rangeMode, bounds){

    var markerDataArray = new Array();

    var sendData;
    sendData = {
        mode : HOSTINFO_LIST_MODE_SCOPEMAP,
        rangeMode : rangeMode,
        startingPointLatitude : bounds.getSouthWest().lat(),
        startingPointLongitude : bounds.getSouthWest().lng(),
        endPointLatitude : bounds.getNorthEast().lat(),
        endPointLongitude : bounds.getNorthEast().lng()
    };

    var requet = $.ajax({
        type: 'GET',
        url: 'api/v1/hostinfolist.php',
        cashe: false,
        dataType: "json",
        data: sendData,
        timeout: 10000
    });
    requet.done(function(responseList){

        var hostinfoHtml = "";
        var markerCnt = 0;
        for(var hostInfoListArrayKey in responseList['hostInfoList']){
            var hostInfoList = responseList['hostInfoList'][hostInfoListArrayKey];

            var holdingDateYmd = {holdingDateYmd: hostInfoList.holdingDateYmd};
            hostinfoHtml += compileHandlebarsTemplate('#hostinfo_date_header_template', holdingDateYmd);

            for(var hostListArrayKey in hostInfoList.hostList){
                var hostList = hostInfoList.hostList[hostListArrayKey];

                hostList.markerCnt = markerCnt;
                hostList.holdingDateYmd = hostInfoList['holdingDateYmd'];
                hostinfoHtml += compileHandlebarsTemplate('#hostinfo_list_template', hostList);

                // 合わせてマーカーのInfoWindow作成
                var infoWindowTextHtml = compileHandlebarsTemplate('#infoWindowText_template', hostList);
                markerDataArray.push({
                    position: new google.maps.LatLng(hostList.latitude, hostList.longitude),
                    content: infoWindowTextHtml
                });

                markerCnt++;
            }
        }

        if(rangeMode === HOSTINFO_LIST_RANGEMODE_ONE) {
            $('#hostinfo_list_oneweek').html(hostinfoHtml);
        } else if(rangeMode === HOSTINFO_LIST_RANGEMODE_ALL) {
            $('#hostinfo_list_all').html(hostinfoHtml);
        }

        var mapRendering = false;

        if(preMarkerDataArray.length == 0) {
            preMarkerDataArray = markerDataArray;
        } else {
            for(i = 0; i < markerDataArray.length; i++){
                
                if(markerDataArray.length != preMarkerDataArray.length || markerDataArray[i].position.lat() !== preMarkerDataArray[i].position.lat() || markerDataArray[i].position.lng() !== preMarkerDataArray[i].position.lng()) {
                    mapRendering = true;
                    preMarkerDataArray = markerDataArray;
                    break;
                }
            }
            if(!mapRendering) {
                return;
            }
        }

        for(i = 0; i < marker_list.getLength(); i++){
            marker_list.getAt(i).setMap(null);
        }
        marker_list = new google.maps.MVCArray();

        // Marker作成
        for(i = 0; i < markerDataArray.length; i++){
            var marker = new google.maps.Marker({
                position: markerDataArray[i].position,
                map: marcheMap,
                zIndex: markerDataArray.length - i
            });
            marker_list.push(marker);
            attachMessage(marker, markerDataArray[i].content);
        }
    });
};

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzNXQMNe9MNNjRc6Ey4Eg-exdJymnaj-w&callback=initMap"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<?php
include __DIR__ . '/handlebarstemplate/template_index.php';
?>
</body>
</html>