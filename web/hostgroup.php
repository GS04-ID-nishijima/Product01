<?php
$hostgroupid = (int)filter_input(INPUT_GET, 'hostgroupid');

include __DIR__ . '/htmlparts/header_parts.php';
?>
<script src="lib/exif/exif.js"></script>
<script src="lib/megapix-image/megapix-image.js"></script>
<!--Copyright (c) 2012 Shinichi Tomita <shinichi.tomita@gmail.com>-->
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
            <div class="row">
                <div class="col-md-12"><h3 class="hostgroup-name" id="hostgrroup_name"></h3></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#hostgroup_top" data-toggle="pill">TOP</a></li>
                        <li><a href="#hostgroup_branchperson_list" data-toggle="pill">出店者一覧</a></li>
                        <li><a href="#hostgroup_photo" data-toggle="pill">写真</a></li>
                        <li><a href="#upload_photo" data-toggle="pill">アップロード</a></li>
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
                        <div id="upload_photo" class="tab-pane">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="col-md-7">
                                  <div class="hostgroup-uploadphoto-box border-solid" id="hostgroup_uploadphoto_box"><span id="hostgroup_uploadphoto_box_comment">写真を選択</span><img src="" alt="" id="hostgroup_uploadphoto_box_img" class="hostgroup-uploadphoto-box-img"></div>

                            </div>
                            <div class="col-md-5 hostgroup-uploadphoto-input-col">
                                <div class="hostgroup-uploadphoto-input-row1">
                                    <div class="hostgroup-uploadphoto-cation1 border-solid">開催日</div><select name="uploadphoto_holdingdate" id="uploadphoto_holdingdate" class="hostgroup-uploadphoto-select border-solid"></select>
                                </div>
                                <div class="hostgroup-uploadphoto-input-row1">
                                    <div class="hostgroup-uploadphoto-cation1 border-solid">出店者</div><div><select name="uploadphoto_branchperson_id" id="uploadphoto_branchperson" class="hostgroup-uploadphoto-select border-solid"></select></div>
                                </div>
                                <div class="hostgroup-uploadphoto-input-row2">
                                    <div class="hostgroup-uploadphoto-cation2 border-solid">コメント</div><div><textarea name="hostgroup_uploadphoto_comment" id="hostgroup_uploadphoto_comment" cols="10" rows="2" maxlength="15" class="hostgroup-uploadphoto-comment border-solid" placeholder="15文字以内で入力して下さい。"></textarea></div>
                                </div>
                                <input type="file" id="upload_photo_form" name="upload_photo_form" accept="image/*" style="opacity:0;">
                                <button class="hostgroup-uploadphoto-button border-solid" name="hostgroup_uploadphoto_button" id="hostgroup_uploadphoto_button" disabled>アップロード</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
include __DIR__ . '/htmlparts/footer_parts.php';
?>

<script>

// 開催日ごとの写真リスト取得状態管理
// ID末尾の番号、100の桁でaccordionの位置を特定。10の桁、1の桁でaccordion内の開催団体ごとのカルーセルを特定
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
        $('#page_title').html('Favorite Marche - ' + responseList['hostGroupName']);
        $('#hostgrroup_name').html(responseList['hostGroupName']);

        insertHandlebarsHtml('#hostgroup_top_subcontents', '#hostgroup_top_subcontents_template', responseList);

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

    // preview用photoが設定されていない場合は、ボタンを無効（主に画面リロード時）
    if(!$('#hostgroup_uploadphoto_box_img').attr('src')){
        $('#hostgroup_uploadphoto_button').prop("disabled", true);
    }
});

$(document).ajaxError(function(){
        console.log('fail');
        console.log(XMLHttpRequest);
});

// 開催情報タブ表示切替
$('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {

    var id = $(e.target).attr("href");

    // 出店者一覧タブ
    if(id === '#hostgroup_branchperson_list') {

        // 出店者リスト取得
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

                var holdingDateYmd = {
                    holdingDateYmd: holdingDateBranchPersonList.holdingDateYmd,
                                     accordionCnt: accordionCnt};
                if(accordionCnt === 1) {
                    branchpersonListHtml += compileHandlebarsTemplate('#branchperson_list_header_first_template', holdingDateYmd);
                } else {
                    branchpersonListHtml += compileHandlebarsTemplate('#branchperson_list_header_template', holdingDateYmd);
                }

                for(var branchPersonListArrayKey in holdingDateBranchPersonList.branchPersonList){
                    var branchPersonList = holdingDateBranchPersonList.branchPersonList[branchPersonListArrayKey];

                    if(listCnt === maxListCnt){
                        branchpersonListHtml += '</ul><ul>';
                        listCnt = 0;
                    }

                    branchpersonListHtml += compileHandlebarsTemplate('#branchperson_list_li_template', branchPersonList);

                    listCnt++;
                }
                branchpersonListHtml += '</ul></div></div></div></div><div class="panel panel-default">';
                listCnt = 0;
                accordionCnt++;
            }

            $('#hostgroup_branchperson_list_accordion').html(branchpersonListHtml);
        });

    // 写真タブ
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

                var holdingDateYmd = {holdingDateYmd: holdingDateYmdList.holdingDateYmd,
                                      accordionCnt: accordionCnt};
                if(accordionCnt === 100) {
                    hostgroupPhotoHtml += compileHandlebarsTemplate('#hostgroup_photo_header_first_template', holdingDateYmd);
                } else {
                    hostgroupPhotoHtml += compileHandlebarsTemplate('#hostgroup_photo_header_template', holdingDateYmd);
                }

                accordionCnt = accordionCnt + 100;
                hostgroupPhotoHtml += '</div></div></div>';
            }

            // 最初の日のみ写真リストを取得
            getPhotoList(responseList['holdingDateYmdList'][0]['holdingDateYmd'], 100);

            hostgroupPhotoHtml += '</div>';
            $('#hostgroup_photo_accordion').html(hostgroupPhotoHtml);

            // 残りのaccordionのオープン時写真リスト取得イベンド処理
            // 100は初期表示時のみ取得。他のaccordionは、データがない場合でも仮置きで定義
            $('#hostgroup_photo_group200, #hostgroup_photo_group300, #hostgroup_photo_group400, #hostgroup_photo_group500').on('shown.bs.collapse', function (e) {
                getPhotoList($('#' +this.id).data('holdingdateymd'), this.id.slice(-3));
            });
        });

    // アップロードタブ
    } else if(id === '#upload_photo') {
        $('#hostgroup_uploadphoto_box').height($('#hostgroup_uploadphoto_box').width());
        
        // 開催日取得
        var sendData;
        sendData = {
            userType: USER_TYPE_HOSTGROUP,
            mode: HOLDINGDATEYMD_LIST_MODE_FUTURE,
            id: <?php echo $hostgroupid ?>,
            onlyPhotoDateFlag: FLAG_OFF
        };

        var requet = $.ajax({
            type: 'GET',
            url: '../api/v1/holdingdateymdlist.php',
            cashe: false,
            dataType: "json",
            data: sendData,
            timeout: 10000,
            async: false
        });
        requet.done(function(responseList){

            insertHandlebarsHtml('#uploadphoto_holdingdate', '#select_uploadphoto_holdingdate_template', responseList);

            // 最初の日の出店者リスト取得
            getholdingDateBranchPersonList(responseList['holdingDateYmdList'][0]['holdingDateYmd']);
        });

    }
});

// アップロード用開催日変更時のイベント処理:出店者リスト再取得
$('select[name="uploadphoto_holdingdate"]').change(function() {
    getholdingDateBranchPersonList($('select[name="uploadphoto_holdingdate"] option:selected').val());
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
            // 取得データに出店者が紐づかない場合は開催者のアップロードのため出店者名を定義
            if(!holdingDateYmdPhotoList.branchPersonName){
                holdingDateYmdPhotoList['branchPersonName'] = "開催者アップロード";
            }
            if(_ua.Mobile) {
                hostgroupPhotoCarouselHtml += compileHandlebarsTemplate('#hostgroup_photo_carousel_box_template_sp', holdingDateYmdPhotoList);
            } else {
                hostgroupPhotoCarouselHtml += compileHandlebarsTemplate('#hostgroup_photo_carousel_box_template_except_sp', holdingDateYmdPhotoList);
            }

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

// アップロード用開催日指定出店者リスト取得
function getholdingDateBranchPersonList(holdingDateYmd) {
    var sendData;
    sendData = {
        mode: BRANCHPERSON_LIST_MODE_APPOINT,
        hostGroupId : <?php echo $hostgroupid ?>,
        holdingDateYmd: holdingDateYmd
    };

    var requet = $.ajax({
        type: 'GET',
        url: '../api/v1/branchpersonlist.php',
        cashe: false,
        dataType: "json",
        data: sendData,
        timeout: 10000,
        async: false
    });
    requet.done(function(responseList){
        insertHandlebarsHtml('#uploadphoto_branchperson', '#select_uploadphoto_branchperson_template', responseList['holdingDateBranchPersonList'][0]);
    });
}

// 写真選択エリアでクリックでファイル選択を起動
$('#hostgroup_uploadphoto_box').on("click", function(){
    $("#upload_photo_form").trigger("click");
});

// 写真縮小
$('#upload_photo_form').on('change', function() {
    var file = $(this).prop('files')[0];

    EXIF.getData(file, function(){
        var orientation = file.exifdata.Orientation;
        var mpImg = new MegaPixImage(file);

        mpImg.render($('#hostgroup_uploadphoto_box_img')[0], {maxWidth: 1024, maxHeight: 1024, orientation: orientation });
    });

    $('#hostgroup_uploadphoto_box_comment').hide();
    $('#hostgroup_uploadphoto_button').prop("disabled", false);
});

// アップロードファイルの表示位置調整
$('#hostgroup_uploadphoto_box_img').bind("load",function(){
    // TODO 396をレスポンシブ向けに可変対応
    if($(this).height() < 396) {
        $('.hostgroup-uploadphoto-box').css('padding-top', (396 - $(this).height()) / 2);
    } else {
        $('.hostgroup-uploadphoto-box').css('padding-top', '0px');
    }
});

// アップロードボタンクリック
$('#hostgroup_uploadphoto_button').on("click", function(){
    $('#hostgroup_uploadphoto_button').prop("disabled", true);
    if($('#hostgroup_uploadphoto_comment').val() === "") {
        alert('コメントは必ず入力して下さい。');
        $('#hostgroup_uploadphoto_button').prop("disabled", false);
        return;
    }

    var formData = new FormData(document.forms[0]);

    var sendData;
    sendData = {
        holdingdate: formData.get('uploadphoto_holdingdate'),
        hostgroupId: <?php echo $hostgroupid ?>,
        branchpersonId: formData.get('uploadphoto_branchperson_id'),
        photoComment: formData.get('hostgroup_uploadphoto_comment'),
        photoFileName: $('#upload_photo_form').prop('files')[0].name,
        photo: $('#hostgroup_uploadphoto_box_img').attr('src')
    };

    var requet = $.ajax({
        type: 'POST',
        url: '../api/v1/photouploading.php',
        cashe: false,
        dataType: "json",
        data: sendData,
        timeout: 20000,
        async: false
    });
    requet.done(function(responseList){
//console.dir(responseList);
    });

    return false;
});

// アップロードボックスのリサイズ
$(window).on('load resize', function(){
    $('#hostgroup_uploadphoto_box').height($('#hostgroup_uploadphoto_box').width());
});

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzNXQMNe9MNNjRc6Ey4Eg-exdJymnaj-w"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>
<?php
include __DIR__ . '/handlebarstemplate/template_hostgroup.php';
?>

</body>
</html>