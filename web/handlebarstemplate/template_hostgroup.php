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
        <a data-toggle="collapse" data-parent="#hostgroup_branchperson_list_accordion" href="#holdingdate_group{{accordionCnt}}">{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}</a>
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
        <a data-toggle="collapse" data-parent="#hostgroup_branchperson_list_accordion" href="#holdingdate_group{{accordionCnt}}">{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}</a>
        </h4>
    </div>
    <div id="holdingdate_group{{accordionCnt}}" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="hostgroup-branchperson-list">
                <ul>
</script>

<script type="text/x-handlebars-template" id="hostgroup_photo_header_first_template">
    <div class="panel-heading">
        <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#hostgroup_photo_accordion" href="#hostgroup_photo_group{{accordionCnt}}">{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}</a>
        </h4>
    </div>
    <div id="hostgroup_photo_group{{accordionCnt}}" class="panel-collapse collapse in" data-holdingDateYmd="{{holdingDateYmd}}">
        <div class="panel-body">
            <div class="hostgroup-photo-card-list" id="hostgroup_photo_card_list{{accordionCnt}}">
</script>
<script type="text/x-handlebars-template" id="hostgroup_photo_header_template">
    <div class="panel-heading">
        <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#hostgroup_photo_accordion" href="#hostgroup_photo_group{{accordionCnt}}">{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}</a>
        </h4>
    </div>
    <div id="hostgroup_photo_group{{accordionCnt}}" class="panel-collapse collapse" data-holdingDateYmd="{{holdingDateYmd}}">
        <div class="panel-body">
            <div class="hostgroup-photo-card-list" id="hostgroup_photo_card_list{{accordionCnt}}">
</script>
<script type="text/x-handlebars-template" id="hostgroup_photo_carousel_box_template_except_sp">
    <div class="hostgroup-photo-card">
        <p class="hostgroup-photo-branchperson-name">{{branchPersonName}}</p>
        <div class="hostgroup-photo-carousel-box">
            <div class="hostgroup-photo-carousel{{accordionCnt}}" id="hostgroup_photo_carousel{{groupCnt}}">
                {{#each photoList}}
                <div>
                    <p><a href="../{{filepath}}{{filename}}" data-lightbox="hostgroup-photo-group{{../groupCnt}}" data-title="{{comment}}"><img src="../{{filepath}}{{thumbnailFilename}}" alt="{{comment}}" class="top-carousel-photo"></a></p>
                    <p class="top-carousel-comment"><span class="top-carousel-comment-text">{{comment}}</span></p>
                </div>
                {{/each}}
            </div>
        </div>
    </div>
</script>
<script type="text/x-handlebars-template" id="hostgroup_photo_carousel_box_template_sp">
    <div class="hostgroup-photo-card">
        <p class="hostgroup-photo-branchperson-name">{{branchPersonName}}</p>
        <div class="hostgroup-photo-carousel-box">
            <div class="hostgroup-photo-carousel{{accordionCnt}}" id="hostgroup_photo_carousel{{groupCnt}}">
                {{#each photoList}}
                <div>
                    <p><a href="../{{filepath}}{{reductionFilename}}" data-lightbox="hostgroup-photo-group{{../groupCnt}}" data-title="{{comment}}"><img src="../{{filepath}}{{thumbnailFilename}}" alt="{{comment}}" class="top-carousel-photo"></a></p>
                    <p class="top-carousel-comment"><span class="top-carousel-comment-text">{{comment}}</span></p>
                </div>
                {{/each}}
            </div>
        </div>
    </div>
</script>
<script type="text/x-handlebars-template" id="branchperson_list_li_template">
    <li>
        <p class="hostgroup-branchperson-name"><span class="hostgroup-branchperson-text">{{branchPersonName}}</span></p>
        <p><img src="../photo/sample_01.jpg" alt="{{branchPersonName}}" class="hostgroup-branchperson-photo"></p>
    </li>
</script>
<script type="text/x-handlebars-template" id="select_uploadphoto_holdingdate_template">
    {{#each holdingDateYmdList}}
        {{#if @first}}
            <option value="{{holdingDateYmd}}" selected>{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}</option>
        {{else}}
            <option value="{{holdingDateYmd}}">{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}</option>
        {{/if}}
    {{/each}}
</script>
<script type="text/x-handlebars-template" id="select_uploadphoto_branchperson_template">
    {{#each branchPersonList}}
        {{#if @first}}
            <option value="{{branchPersonId}}" selected>{{branchPersonName}}</option>
        {{else}}
            <option value="{{branchPersonId}}">{{branchPersonName}}</option>
        {{/if}}
    {{/each}}
</script>
