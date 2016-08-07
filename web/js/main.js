function formatHostinfoDate(holdingDateYmd) {
    var objDate = new Date(holdingDateYmd.substring(0, 4) + "/" + holdingDateYmd.substring(4, 6) + "/" + holdingDateYmd.substring(6));
    var month = objDate.getMonth() + 1;
    var day = objDate.getDate();
    var dayOfWeek = objDate.getDay();
    var week = new Array("日","月","火","水","木","金","土");
    
    return "" + month + "月" + day + "日（" + week[dayOfWeek] + "）開催";
}