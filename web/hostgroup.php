<?php
$hostgroupid = (int)filter_input(INPUT_GET, 'hostgroupid');
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/lib/bootstrap/bootstrap.min.js"></script>
    <script src="js/lib/slick/slick.min.js"></script>
    <script src="js/lib/handlebars/handlebars-v4.0.5.js"></script>
    <script src="js/main.js"></script>
    <link rel="stylesheet" href="css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/lib/slick/slick-theme.css">
    <link rel="stylesheet" href="css/lib/slick/slick.css">
    <link rel="stylesheet" href="css/lib/lightbox/lightbox.min.css">
    <link rel="stylesheet" href="css/marche.css">
    <title>Favorite Marche - Farmer's Market</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="header-title"><a href="index.html">Favorite Marche</a></h2>
                </div>
            </div>
        </div>
    </header>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-12"><h3 class="hostgroup-name" id="hostgrroup_name"></h3></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#hostgroup_top" data-toggle="pill">TOP</a></li>
                        <li><a href="#hostgroup_branchperson_list" data-toggle="pill">出店者一覧</a></li>
                        <li><a href="#hostgroup_photo" data-toggle="pill">写真</a></li>
                    </ul>
                    <div class="hostgroup-contents border-solid tab-content">
                        <div id="hostgroup_top" class="tab-pane active">
                            <div class="col-md-8">
                                <img src="../photo/sample_01.jpg" alt="sample_01" class="hostgroup-top-photo">
                                <p class="hostgroup-top-introduction">今日も晴天です。<br><br>６月は梅雨が始まり、曇りや雨の天気が多いですが、今年は比較的晴天の日が続いています。<br><br>それでも、湿気が多くジメっとした気候が続き、体には負担がかかります。<br>体調管理をしっかりして、本格的な夏の始まり前に、梅雨を万全に乗り切りましょう。<br><br>食も、肉類・脂っこいものだけではなく、野菜や魚など多様な摂り、体に滋養をつけましょう。</p>
                            </div>
                            <div class="col-md-4 hostgroup-top-subcontents-col" id="hostgroup_top_subcontents">
                            </div>
                        </div>
                        <div id="hostgroup_branchperson_list" class="tab-pane">
                            <div class="col-md-12">
                                <div class="panel-group" id="hostgroup_branchperson_list_accordion">
                                </div>
                            </div>
                        </div>
                        <div id="hostgroup_photo" class="tab-pane">
                            <div class="col-md-12 hostgroup-photo-box">
                                <div class="panel-group" id="hostgroup_photo_accordion">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="row footer-row">
                    <div class="footer-col col-md-6">
                       <div class="footer-contents1">
                        <span class="footer-title">Favorite Marche</span>
                        <span class="footer-inquiry"><a href="#">お問い合わせ</a></span>
                       </div>
                    </div>
                    <div class="footer-col col-md-6 copyright">
                       <div class="footer-contents2">
                        ©2016 Favorite Marche All Rights Reserved.
                       </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

<script>

// 開催日ごとの写真リスト取得状態管理
var photoListStatus = {100: false, 200: false, 300: false, 400: false, 500: false};

$(document).ready(function(){

    // 開催団体情報取得
    var sendData;
    sendData = {
        hostGroupId : <?php echo $hostgroupid ?>
    };

    var requet = $.ajax({
        type: 'GET',
        url: '../api/v1/hostgroupinfo.php',
        cashe: false,
        dataType: "json",
        data: sendData,
        timeout: 10000
    });
    requet.done(function(responseList){

        $('#hostgrroup_name').html(responseList['hostGroupName']);

        var hostgroup_top_subcontents_template = Handlebars.compile($('#hostgroup_top_subcontents_template').html());
        $('#hostgroup_top_subcontents').html(hostgroup_top_subcontents_template(responseList));

        var hostGroupMap = new google.maps.Map(document.getElementById("hostgroup_map"), {
        center: new google.maps.LatLng(responseList['latitude'], responseList['longitude']),
        zoom: 15
        });

        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(responseList['latitude'], responseList['longitude']),
            map: hostGroupMap
        });
    });

    // カルーセル対応
    $('.hostgroup-photo-carousel').slick({
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 2,
        responsive: [
            {
              breakpoint: 1210,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 982,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 2
              }
            },
            {
              breakpoint: 730,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 360,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
        ]
    });
});

$(document).ajaxError(function(){
        console.log('fail');
        console.log(XMLHttpRequest);
        console.log(textStatus);
        console.log(errorThrown);
});

// 開催情報タブ表示切替
$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {

    var id = $(e.target).attr("href");

    if(id === '#hostgroup_branchperson_list') {

        // 開催団体情報取得
        var sendData;
        sendData = {
            mode: BRANCHPERSON_LIST_MODE_FUTURE,
            hostGroupId : <?php echo $hostgroupid ?>
        };

        var requet = $.ajax({
            type: 'GET',
            url: '../api/v1/branchpersonlist.php',
            cashe: false,
            dataType: "json",
            data: sendData,
            timeout: 10000
        });
        requet.done(function(responseList){

            var branchpersonListHtml = '<div class="panel panel-default">';
            var accordionCnt = 1;
            var listCnt = 0;
            //  TODO 画面サイズに応じて変更させる値
            var maxListCnt = 5;

            for(var holdingDateBranchPersonListArrayKey in responseList['holdingDateBranchPersonList']) {
                var holdingDateBranchPersonList = responseList['holdingDateBranchPersonList'][holdingDateBranchPersonListArrayKey];

                var holdingDateYmd = {holdingDateYmd: formatHostinfoDate(holdingDateBranchPersonList.holdingDateYmd),
                                     accordionCnt: accordionCnt};
                var branchperson_list_header_template;
                if(accordionCnt === 1) {
                    branchperson_list_header_template = Handlebars.compile($('#branchperson_list_header_first_template').html());
                } else {
                    branchperson_list_header_template = Handlebars.compile($('#branchperson_list_header_template').html());
                }
                branchpersonListHtml += branchperson_list_header_template(holdingDateYmd);

                for(var branchPersonListArrayKey in holdingDateBranchPersonList.branchPersonList){
                    var branchPersonList = holdingDateBranchPersonList.branchPersonList[branchPersonListArrayKey];

                    if(listCnt === maxListCnt){
                        branchpersonListHtml += '</ul><ul>';
                        listCnt = 0;
                    }

                    var branchperson_list_li_template = Handlebars.compile($('#branchperson_list_li_template').html());
                    branchpersonListHtml += branchperson_list_li_template(branchPersonList);

                    listCnt++;
                }
                branchpersonListHtml += '</ul></div></div></div></div><div class="panel panel-default">';
                listCnt = 0;
                accordionCnt++;
            }

            $('#hostgroup_branchperson_list_accordion').html(branchpersonListHtml);
        });

    } else if(id === '#hostgroup_photo') {

        photoListStatus = {100: false, 200: false, 300: false, 400: false, 500: false};
        // 開催日取得
        var sendData;
        sendData = {
            userType: USER_TYPE_HOSTGROUP,
            mode: HOLDINGDATEYMD_LIST_MODE_PAST,
            id: <?php echo $hostgroupid ?>,
            onlyPhotoDateFlag: FLAG_ON
        };

        var requet = $.ajax({
            type: 'GET',
            url: '../api/v1/holdingdateymdlist.php',
            cashe: false,
            dataType: "json",
            data: sendData,
            timeout: 10000
        });
        requet.done(function(responseList){
            var hostgroupPhotoHtml = '<div class="panel panel-default">';
            var accordionCnt = 100;

            var firstHoldingDateFlag = true;
            for(var holdingDateYmdListArrayKey in responseList['holdingDateYmdList']) {
                var holdingDateYmdList = responseList['holdingDateYmdList'][holdingDateYmdListArrayKey];

                var holdingDateYmd = {holdingDateYmd: formatHostinfoDate(holdingDateYmdList.holdingDateYmd),
                                      originalHoldingDateYmd: holdingDateYmdList.holdingDateYmd,
                                      accordionCnt: accordionCnt};
                var hostgroup_photo_header_template;
                if(accordionCnt === 100) {
                    hostgroup_photo_header_template = Handlebars.compile($('#hostgroup_photo_header_first_template').html());
                } else {
                    hostgroup_photo_header_template = Handlebars.compile($('#hostgroup_photo_header_template').html());
                }
                hostgroupPhotoHtml += hostgroup_photo_header_template(holdingDateYmd);

                // 最初の日のみ写真リストを取得
                if(firstHoldingDateFlag) {
                    getPhotoList(holdingDateYmdList.holdingDateYmd, accordionCnt);
                    firstHoldingDateFlag = false;
                }
                accordionCnt = accordionCnt + 100;
                hostgroupPhotoHtml += '</div></div></div>';
            }
            hostgroupPhotoHtml += '</div>';
            $('#hostgroup_photo_accordion').html(hostgroupPhotoHtml);

            // 残りのaccordionのオープン時写真リスト取得イベンド処理
            $('#hostgroup_photo_group200, #hostgroup_photo_group300, #hostgroup_photo_group400, #hostgroup_photo_group500').on('shown.bs.collapse', function (e) {
                getPhotoList($('#' +this.id).data('holdingdateymd'), this.id.slice(-3));
            });

        });
    }
});


// 開催日指定写真リスト取得
function getPhotoList(holdingDateYmd, accordionCnt) {

    // 取得済みであれば実行しない。
    if(photoListStatus[accordionCnt]) {
        return;
    }

    var sendData;
    var hostgroupPhotoCarouselHtml = '';
    sendData = {
        userType: USER_TYPE_HOSTGROUP,
        holdingDateYmd: holdingDateYmd,
        hostGroupId : <?php echo $hostgroupid ?>
    };

    var requet = $.ajax({
        type: 'GET',
        url: '../api/v1/holdingdateymdphotolist.php',
        cashe: false,
        dataType: "json",
        data: sendData,
        timeout: 10000
    });
    requet.done(function(responseList){
        var groupCnt = 1;
        for(var holdingDateYmdPhotoListArrayKey in responseList['holdingDateYmdPhotoList']) {
            var holdingDateYmdPhotoList = responseList['holdingDateYmdPhotoList'][holdingDateYmdPhotoListArrayKey];

            holdingDateYmdPhotoList['accordionCnt'] = accordionCnt;
            holdingDateYmdPhotoList['groupCnt'] = accordionCnt + groupCnt;
            if(!holdingDateYmdPhotoList.branchPersonName){
                holdingDateYmdPhotoList['branchPersonName'] = "開催者アップロード";
            }
            var hostgroup_photo_carousel_box_template = Handlebars.compile($('#hostgroup_photo_carousel_box_template').html());
            hostgroupPhotoCarouselHtml += hostgroup_photo_carousel_box_template(holdingDateYmdPhotoList);
            groupCnt++;
        }
        $('#hostgroup_photo_card_list' + accordionCnt).html(hostgroupPhotoCarouselHtml);

        // カルーセル対応
        $('.hostgroup-photo-carousel' + accordionCnt).slick({
            infinite: false,
            slidesToShow: 5,
            slidesToScroll: 2,
            responsive: [
                {
                  breakpoint: 1210,
                  settings: {
                    slidesToShow: 4,
                    slidesToScroll: 2
                  }
                },
                {
                  breakpoint: 982,
                  settings: {
                    slidesToShow: 3,
                    slidesToScroll: 2
                  }
                },
                {
                  breakpoint: 730,
                  settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                  }
                },
                {
                  breakpoint: 360,
                  settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                  }
                }
            ]
        });

        photoListStatus[accordionCnt] = true;
    });
};


</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzNXQMNe9MNNjRc6Ey4Eg-exdJymnaj-w"></script>
<script src="js/lib/lightbox/lightbox.min.js"></script>
<script type="text/x-handlebars-template" id="hostgroup_top_subcontents_template">
    <div class="hostgroup-top-subcontents-header">開催スケジュール</div>
    <p class="hostgroup-top-subcontents-body">{{holdingSchedule}}</p>
    <div class="hostgroup-top-subcontents-header">開催時間</div>
    <p class="hostgroup-top-subcontents-body">{{holdingTime}}</p>
    <div class="hostgroup-top-subcontents-header">開催場所</div>
    <p class="hostgroup-top-subcontents-body">{{placeName}}</p>
    <div class="hostgroup-top-subcontents-header">アクセス</div>
    <p class="hostgroup-top-subcontents-body">{{directions}}</p>
    <div id="hostgroup_map" class="border-solid hostgroup-top-map"></div>
</script>
<script type="text/x-handlebars-template" id="branchperson_list_header_first_template">
    <div class="panel-heading">
        <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#hostgroup_branchperson_list_accordion" href="#holdingdate_group{{accordionCnt}}">{{holdingDateYmd}}</a>
        </h4>
    </div>
    <div id="holdingdate_group{{accordionCnt}}" class="panel-collapse collapse in">
        <div class="panel-body">
            <div class="hostgroup-branchperson-list">
                <ul>
</script>
<script type="text/x-handlebars-template" id="branchperson_list_header_template">
    <div class="panel-heading">
        <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#hostgroup_branchperson_list_accordion" href="#holdingdate_group{{accordionCnt}}">{{holdingDateYmd}}</a>
        </h4>
    </div>
    <div id="holdingdate_group{{accordionCnt}}" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="hostgroup-branchperson-list">
                <ul>
</script>
<script type="text/x-handlebars-template" id="branchperson_list_li_template">
    <li>
        <p class="hostgroup-branchperson-name"><span class="hostgroup-branchperson-text">{{branchPersonName}}</span></p>
        <p><img src="../photo/sample_01.jpg" alt="{{branchPersonName}}" class="hostgroup-branchperson-photo"></p>
    </li>
</script>
<script type="text/x-handlebars-template" id="hostgroup_photo_header_first_template">
    <div class="panel-heading">
        <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#hostgroup_photo_accordion" href="#hostgroup_photo_group{{accordionCnt}}">{{holdingDateYmd}}</a>
        </h4>
    </div>
    <div id="hostgroup_photo_group{{accordionCnt}}" class="panel-collapse collapse in" data-holdingDateYmd="{{originalHoldingDateYmd}}">
        <div class="panel-body">
            <div class="hostgroup-photo-card-list" id="hostgroup_photo_card_list{{accordionCnt}}">
</script>
<script type="text/x-handlebars-template" id="hostgroup_photo_header_template">
    <div class="panel-heading">
        <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#hostgroup_photo_accordion" href="#hostgroup_photo_group{{accordionCnt}}">{{holdingDateYmd}}</a>
        </h4>
    </div>
    <div id="hostgroup_photo_group{{accordionCnt}}" class="panel-collapse collapse" data-holdingDateYmd="{{originalHoldingDateYmd}}">
        <div class="panel-body">
            <div class="hostgroup-photo-card-list" id="hostgroup_photo_card_list{{accordionCnt}}">
</script>
<script type="text/x-handlebars-template" id="hostgroup_photo_carousel_box_template">
    <div class="hostgroup-photo-card">
        <p class="hostgroup-photo-branchperson-name">{{branchPersonName}}</p>
        <div class="hostgroup-photo-carousel-box">
            <div class="hostgroup-photo-carousel{{accordionCnt}}" id="hostgroup_photo_carousel{{groupCnt}}">
                {{#each photoList}}
                <div>
                    <p><a href="{{filepath}}{{filename}}" data-lightbox="hostgroup-photo-group{{../groupCnt}}"><img src="{{filepath}}{{filename}}" alt="{{comment}}" class="top-carousel-photo"></a></p>
                    <p class="top-carousel-comment"><span class="top-carousel-comment-text">{{comment}}</span></p>
                </div>
                {{/each}}
            </div>
        </div>
    </div>
</script>
</body>
</html>