<script type="text/x-handlebars-template" id="hostinfo_date_header_template">
    <div class="hostinfo-date-header">
        <span class="hostinfo-date">{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}開催</span>
    </div>
    <div class="hostinfo-space"></div>
</script>
<script type="text/x-handlebars-template" id="hostinfo_list_template">
    <div class="hostinfo-card">
        <p>
            <a href="javascript:hostinfolist_click({{markerCnt}})" class="hostinfo-name">{{hostGroupName}}</a>
        </p>
        <div class="clearfix">
            <img src="../{{thumbnailPhotoUrl}}" alt="{{hostGroupName}}" class="hostinfo-photo">
            <ul class="hostinfo-contents">
                <li>{{placeName}}</li>
                <li>{{branchScale}}</li>
            </ul>
        </div>
    </div>
    <div class="hostinfo-space"></div>
</script>
<script type="text/x-handlebars-template" id="infoWindowText_template">
    <p><a href="hostgroup?hostgroupid={{hostGroupId}}" class="hostinfo-name">{{hostGroupName}}</a></p>
    <ul class="infoWindow-ul">
        <li>{{#formatHoldingDateYmd holdingDateYmd}}{{/formatHoldingDateYmd}}開催</li>
        <li>{{placeName}}</li>
        <li>{{branchScale}}</li>
    </ul>
</script>
<script type="text/x-handlebars-template" id="carousel_photo_template_except_sp">
    <div>
        <p class="top-carousel-hostgroup"><a href="hostgroup?hostgroupid={{hostGroupId}}">{{hostGroupName}}</a></p>
        <p><a href="../{{filepath}}{{filename}}" data-lightbox="latest-photo-group" data-title="{{comment}}"><img src="../{{filepath}}{{thumbnailFilename}}" alt="{{comment}}" class="top-carousel-photo"></a></p>
        <p class="top-carousel-comment"><span class="top-carousel-comment-text">{{comment}}</span></p>
    </div>
</script>
<script type="text/x-handlebars-template" id="carousel_photo_template_sp">
    <div>
        <p class="top-carousel-hostgroup"><a href="hostgroup?hostgroupid={{hostGroupId}}">{{hostGroupName}}</a></p>
        <p><a href="../{{filepath}}{{reductionFilename}}" data-lightbox="latest-photo-group" data-title="{{comment}}"><img src="../{{filepath}}{{thumbnailFilename}}" alt="{{comment}}" class="top-carousel-photo"></a></p>
        <p class="top-carousel-comment"><span class="top-carousel-comment-text">{{comment}}</span></p>
    </div>
</script>