const FLAG_OFF = '0';
const FLAG_ON = '1';

const USER_TYPE_HOSTGROUP = '1';
const USER_TYPE_BRANCHPERSON = '2';

const HOSTINFO_LIST_MODE_SCOPEMAP = '1';

const HOSTINFO_LIST_RANGEMODE_ONE = '1';
const HOSTINFO_LIST_RANGEMODE_ALL = '2';

const BRANCHPERSON_LIST_MODE_FUTURE = '2';

const HOLDINGDATEYMD_LIST_MODE_FUTURE = '1';
const HOLDINGDATEYMD_LIST_MODE_PAST = '2';


function formatHostinfoDate(holdingDateYmd) {
    var objDate = new Date(holdingDateYmd.substring(0, 4) + "/" + holdingDateYmd.substring(4, 6) + "/" + holdingDateYmd.substring(6));
    var month = objDate.getMonth() + 1;
    var day = objDate.getDate();
    var dayOfWeek = objDate.getDay();
    var week = new Array("日","月","火","水","木","金","土");
    
    return "" + month + "月" + day + "日（" + week[dayOfWeek] + "）";
}