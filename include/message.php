<?php

define("MSG_HTTP_400_ERROR001", "HTTP/1.1 400 Bad Request");
define("MSG_HTTP_500_ERROR001", "HTTP/1.1 500 Internal Server Error");

define("FLAG_OFF", "0");
define("FLAG_ON", "1");


define("MSG_HOSTINFOLISTAPI_PARAMETER_ERROR001", "mode, rangeMode are required.");
define("MSG_HOSTINFOLISTAPI_PARAMETER_ERROR002", "latitude or longitude are not enough.");
define("MSG_HOSTINFOLISTAPI_PARAMETER_ERROR003", "mode is must be 1 or 2.");
define("MSG_HOSTINFOLISTAPI_PARAMETER_ERROR004", "rangeMode is must be 1 or 2.");

define("MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR001", "userType, mode, id are required.");
define("MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR002", "mode is must be 1 or 2.");
define("MSG_HOLDINGDATEYMDLISTAPI_PARAMETER_ERROR003", "userType is must be 1 or 2.");

define("MSG_HOLDINGDATEYMDPHOTOLISTAPI_PARAMETER_ERROR001", "userType, holdingDateYmd, hostGroupId are required.");
define("MSG_HOLDINGDATEYMDPHOTOLISTAPI_PARAMETER_ERROR002", "userType is must be 1 or 2.");
define("MSG_HOLDINGDATEYMDPHOTOLISTAPI_PARAMETER_ERROR003", "branchPersonId is required.");

define("MSG_HOSTGROUPINFOAPI_PARAMETER_ERROR001", "hostGroupId is required.");

define("MSG_BRANCHPERSONLISTAPI_PARAMETER_ERROR001", "mode, hostGroupId are required.");
define("MSG_BRANCHPERSONLISTAPI_PARAMETER_ERROR002", "mode is must be 1 or 2, 3.");
define("MSG_BRANCHPERSONLISTAPI_PARAMETER_ERROR003", "holdingDateYmd is required.");


define("USERTYPE_HOSTGROUP", "1");
define("USERTYPE_BRANCHPERSON", "2");


?>
