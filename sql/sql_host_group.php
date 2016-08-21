<?php

$queryHostListBase = 
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd     AS  holding_date_ymd
    ,   US.id                   AS  host_group_id
    ,   US.name                 AS  host_group_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitude             AS  latitude
    ,   HG.longitude            AS  longitude
    ,   HG.branch_scale         AS  branch_scale
    FROM
        USER        US
    ,   HOST_GROUP  HG
    ,   HOST_INFO   HI
    ";

$queryHostListSortBase =
    "
    ORDER BY
        HI.holding_date_ymd
    ,   HG.latitude DESC
    ,   HG.longitude
    ";

$getHostListScopeMap =
    $queryHostListBase .
    "
    WHERE
        US.user_type_division   =   '1'
    AND US.unavailable_flag     =   '0'
    AND US.id                   =   HG.host_group_id
    AND US.id                   =   HI.host_group_id
    AND :current_date_ymd       <=  HI.holding_date_ymd
    AND HG.latitude             BETWEEN :strLati AND :endLati
    AND HG.longitude            BETWEEN :strLong AND :endLong
    " . 
    $queryHostListSortBase;

$getHostListScopwMapOneWeek =
    $queryHostListBase .
    "
    WHERE
        US.user_type_division   =   '1'
    AND US.unavailable_flag     =   '0'
    AND US.id                   =   HG.host_group_id
    AND US.id                   =   HI.host_group_id
    AND :current_date_ymd       <=  HI.holding_date_ymd
    AND HI.holding_date_ymd     <=  :to_date_ymd
    AND HG.latitude             BETWEEN :strLati AND :endLati
    AND HG.longitude            BETWEEN :strLong AND :endLong
    " . 
    $queryHostListSortBase;

$getHostList =
    $queryHostListBase .
    "
    WHERE
        US.user_type_division   =   '1'
    AND US.unavailable_flag     =   '0'
    AND US.id                   =   HG.host_group_id
    AND US.id                   =   HI.host_group_id
    AND :current_date_ymd       <=  HI.holding_date_ymd
    " . 
    $queryHostListSortBase;

$getHostListOneWeek =
    $queryHostListBase .
    "
    WHERE
        US.user_type_division   =   '1'
    AND US.unavailable_flag     =   '0'
    AND US.id                   =   HG.host_group_id
    AND US.id                   =   HI.host_group_id
    AND :current_date_ymd       <=  HI.holding_date_ymd
    AND HI.holding_date_ymd     <=  :to_date_ymd
    " . 
    $queryHostListSortBase;

$queryHostGroupInfo = 
    "
    SELECT
        US.name                 AS  host_group_name
    ,   HG.place_name           AS  place_name
    ,   HG.holding_schedule     AS  holding_schedule
    ,   HG.holding_time         AS  holding_time
    ,   HG.latitude             AS  latitude
    ,   HG.longitude            AS  longitude
    ,   HG.directions           AS  directions
    ,   HG.branch_scale         AS  branch_scale
    ,   US.formal_hp_url        AS  formal_hp_url
    ,   US.facebook_url         AS  facebook_url
    ,   US.twitter_url          AS  twitter_url
    FROM
        USER        US
    ,   HOST_GROUP  HG
    WHERE
        US.id                   =   :host_group_id
    AND US.user_type_division   =   '1'
    AND US.unavailable_flag     =   '0'
    AND US.id                   =   HG.host_group_id
    ";


?>