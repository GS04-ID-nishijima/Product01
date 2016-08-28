<?php

$QUERY_HOLDINGDATEYMDLIST_BASE =
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd     AS holding_date_ymd
    FROM
        HOST_INFO       HI
    ,   USER            US
    ";

$QUERY_HOLDINGDATEYMDLIST_SORTBASE_FUTURE =
    "
    ORDER BY
        HI.holding_date_ymd
    LIMIT 5
    ";

$QUERY_HOLDINGDATEYMDLIST_SORTBASE_PAST =
    "
    ORDER BY
        HI.holding_date_ymd DESC
    LIMIT 5
    ";

$QUERY_HOLDINGDATEYMDLIST_HOSTGROUP_FUTURE =
    $QUERY_HOLDINGDATEYMDLIST_BASE .
    "
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd >= :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $QUERY_HOLDINGDATEYMDLIST_SORTBASE_FUTURE;

$QUERY_HOLDINGDATEYMDLIST_HOSTGROUP_PAST =
    $QUERY_HOLDINGDATEYMDLIST_BASE .
    "
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd <= :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $QUERY_HOLDINGDATEYMDLIST_SORTBASE_PAST;

$QUERY_HOLDINGDATEYMDLIST_HOSTGROUP_PAST_PHOTODATE =
    $QUERY_HOLDINGDATEYMDLIST_BASE .
    "
    ,   UPLOAD_INFO UI
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd <= :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    AND HI.holding_date_ymd = UI.holding_date_ymd
    AND HI.host_group_id    = UI.host_group_id
    " .
    $QUERY_HOLDINGDATEYMDLIST_SORTBASE_PAST;

$QUERY_HOLDINGDATEYMDLIST_BRANCHPERSON_FUTURE =
    $QUERY_HOLDINGDATEYMDLIST_BASE .
    "
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd >=  :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $QUERY_HOLDINGDATEYMDLIST_SORTBASE_FUTURE;

$QUERY_HOLDINGDATEYMDLIST_BRANCHPERSON_PAST =
    $QUERY_HOLDINGDATEYMDLIST_BASE .
    "
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd <=  :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    " .
    $QUERY_HOLDINGDATEYMDLIST_SORTBASE_PAST;

$QUERY_HOLDINGDATEYMDLIST_BRANCHPERSON_PAST_PHOTODATE =
    $QUERY_HOLDINGDATEYMDLIST_BASE .
    "
    ,   UPLOAD_INFO UI
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd <=  :current_date_ymd
    AND HI.host_group_id    = US.id
    AND US.unavailable_flag = '0'
    AND HI.holding_date_ymd = UI.holding_date_ymd
    AND HI.branch_person_id = UI.branch_person_id
    " .
    $QUERY_HOLDINGDATEYMDLIST_SORTBASE_PAST;

?>