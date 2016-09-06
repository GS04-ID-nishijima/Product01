<?php

$QUERY_BRANCHPERSONLIST_BASE = 
    "
    SELECT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   BP.branch_person_id     AS  branch_person_id
    ,   US.name                 AS  branch_person_name
    FROM
        HOST_INFO       HI
    ,   BRANCH_PERSON   BP
    ,   USER            US
    WHERE
        HI.host_group_id        =   :host_group_id
    AND HI.branch_person_id     =   BP.branch_person_id
    AND BP.branch_person_id     =   US.id
    AND US.unavailable_flag = '0'
    ";

$QUERY_BRANCHPERSONLIST_SORTBASE =
    "
    ORDER   BY
        holding_date_ymd
    ,   branch_person_name
    ";

$QUERY_BRANCHPERSONLIST_APPOINT =
    "
    SELECT
        :host_group_date   AS  holding_date_ymd
    ,   0                  AS  branch_person_id
    ,   :host_group_name   AS  branch_person_name
    FROM
        DUAL
    UNION ALL (
    " .
    $QUERY_BRANCHPERSONLIST_BASE . 
    "
    AND HI.holding_date_ymd     =   :holding_date_ymd
    ORDER   BY
        holding_date_ymd
    ,   branch_person_name)
    ";


$QUERY_BRANCHPERSONLIST_FUTURE =
    $QUERY_BRANCHPERSONLIST_BASE . 
    "
    AND HI.holding_date_ymd     >=  :holding_date_ymd
    " . 
    $QUERY_BRANCHPERSONLIST_SORTBASE;

$QUERY_BRANCHPERSONLIST_ALL =
    $QUERY_BRANCHPERSONLIST_BASE . $QUERY_BRANCHPERSONLIST_SORTBASE;

?>