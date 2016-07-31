function initMap() {
    try {
        // 初期表示地
        var lat = 35.648971;
        var lon = 139.734873;

        marker_list = new google.maps.MVCArray();

        //div#mapを「GoogleMap」化
        map = new google.maps.Map(document.getElementById('hostinfomap'), {
           center: {lat: lat, lng: lon},
           zoom: 13
        });

    } catch (error) {
        console.log("getGeolocation: " + error);
    }
};