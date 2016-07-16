<?php

$getHostListScopeMap =
    "
    SELECT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   HG.host_group_id        AS  host_group_id
    ,   HG.host_name            AS  host_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitudo             AS  latitudo
    ,   HG.longitude            AS  longitude
    ,   HG.branch_scale         AS  branch_scale
    FROM
        HOST_GROUP  HG
    ,   HOST_INFO   HI
    WHERE
        HG.host_group_id    =       HI.host_group_id
    AND :current_date_ymd   <=      HI.holding_date_ymd
    AND HG.latitudo         BETWEEN :strLati AND :endLati
    AND HG.longitude        BETWEEN :strLong AND :endLong
    ORDER   BY
        HI.holding_date_ymd
    ,   HG.latitudo DESC
    ,   HG.longitude
    ";

$getHostListScopwMapOneWeek =
    "
    SELECT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   HG.host_group_id        AS  host_group_id
    ,   HG.host_name            AS  host_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitudo             AS  latitudo
    ,   HG.longitude            AS  longitude
    ,   HG.branch_scale         AS  branch_scale
    FROM
        HOST_GROUP  HG
    ,   HOST_INFO   HI
    WHERE
        HG.host_group_id    =       HI.host_group_id
    AND :current_date_ymd   <=      HI.holding_date_ymd
    AND HI.holding_date_ymd <=      :to_date_ymd
    AND HG.latitudo         BETWEEN :strLati AND :endLati
    AND HG.longitude        BETWEEN :strLong AND :endLong
    ORDER   BY
        HI.holding_date_ymd
    ,   HG.latitudo DESC
    ,   HG.longitude
    ";

$getHostList =
    "
    SELECT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   HG.host_group_id        AS  host_group_id
    ,   HG.host_name            AS  host_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitudo             AS  latitudo
    ,   HG.longitude            AS  longitude
    ,   HG.branch_scale         AS  branch_scale
    FROM
        HOST_GROUP  HG
    ,   HOST_INFO   HI
    WHERE
        HG.host_group_id    =       HI.host_group_id
    AND :current_date_ymd   <=      HI.holding_date_ymd
    ORDER   BY
        HI.holding_date_ymd
    ,   HG.latitudo DESC
    ,   HG.longitude
    ";

$getHostListOneWeek =
    "
    SELECT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   HG.host_group_id        AS  host_group_id
    ,   HG.host_name            AS  host_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitudo             AS  latitudo
    ,   HG.longitude            AS  longitude
    ,   HG.branch_scale         AS  branch_scale
    FROM
        HOST_GROUP  HG
    ,   HOST_INFO   HI
    WHERE
        HG.host_group_id    =       HI.host_group_id
    AND :current_date_ymd   <=      HI.holding_date_ymd
    AND HI.holding_date_ymd <=      :to_date_ymd
    ORDER   BY
        HI.holding_date_ymd
    ,   HG.latitudo DESC
    ,   HG.longitude
    ";

?>