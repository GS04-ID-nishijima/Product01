const FLAG_OFF = '0';
const FLAG_ON = '1';

const USER_TYPE_HOSTGROUP = '1';
const USER_TYPE_BRANCHPERSON = '2';

const HOSTINFO_LIST_MODE_SCOPEMAP = '1';

const HOSTINFO_LIST_RANGEMODE_ONE = '1';
const HOSTINFO_LIST_RANGEMODE_ALL = '2';

const BRANCHPERSON_LIST_MODE_APPOINT = '1';
const BRANCHPERSON_LIST_MODE_FUTURE = '2';

const HOLDINGDATEYMD_LIST_MODE_FUTURE = '1';
const HOLDINGDATEYMD_LIST_MODE_PAST = '2';

// 日付を表示用に変換
function formatHoldingDateYmd(holdingDateYmd) {
    var objDate = new Date(holdingDateYmd.substring(0, 4) + "/" + holdingDateYmd.substring(4, 6) + "/" + holdingDateYmd.substring(6));
    var month = objDate.getMonth() + 1;
    var day = objDate.getDate();
    var dayOfWeek = objDate.getDay();
    var week = new Array("日","月","火","水","木","金","土");
    
    return "" + month + "月" + day + "日（" + week[dayOfWeek] + "）";
};

// Handlebarsのコンパイル
function compileHandlebarsTemplate(templateId, data) {
    var template = Handlebars.compile($(templateId).html());
    return template(data);
};

// Handlebarsのコンパイル&HTML挿入
function insertHandlebarsHtml(htmlId, templateId, data) {
    $(htmlId).html(compileHandlebarsTemplate(templateId, data));
};

// ユーザーエージェント判断
var _ua = (function(u){
  return {
    Tablet:(u.indexOf("windows") != -1 && u.indexOf("touch") != -1 && u.indexOf("tablet pc") == -1) 
      || u.indexOf("ipad") != -1
      || (u.indexOf("android") != -1 && u.indexOf("mobile") == -1)
      || (u.indexOf("firefox") != -1 && u.indexOf("tablet") != -1)
      || u.indexOf("kindle") != -1
      || u.indexOf("silk") != -1
      || u.indexOf("playbook") != -1,
    Mobile:(u.indexOf("windows") != -1 && u.indexOf("phone") != -1)
      || u.indexOf("iphone") != -1
      || u.indexOf("ipod") != -1
      || (u.indexOf("android") != -1 && u.indexOf("mobile") != -1)
      || (u.indexOf("firefox") != -1 && u.indexOf("mobile") != -1)
      || u.indexOf("blackberry") != -1
  }
})(window.navigator.userAgent.toLowerCase());

// Handlebars向け関数
// 日付フォーマット変換
Handlebars.registerHelper('formatHoldingDateYmd', function(holdingDateYmd, opt) {
  return formatHoldingDateYmd(holdingDateYmd);
});
