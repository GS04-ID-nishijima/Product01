<?php

$GET_HOSTLIST_BASE = 
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd                           AS  holding_date_ymd
    ,   US.id                                         AS  host_group_id
    ,   US.name                                       AS  host_group_name
    ,   HG.place_name                                 AS  place_name
    ,   HG.holding_schedule                           AS  holding_schedule
    ,   HG.holding_time                               AS  holding_time
    ,   HG.latitude                                   AS  latitude
    ,   HG.longitude                                  AS  longitude
    ,   HG.branch_scale                               AS  branch_scale
    ,   COALESCE(
            CONCAT(PH.filepath, PH.filename),
            'photo/now_printing.jpg')                 AS  thumbnail_photo_url
    "
;

$GET_HOSTLIST_SORTBASE =
    "
    ORDER BY
        HI.holding_date_ymd
    ,   HG.latitude DESC
    ,   HG.longitude
    ";

$GET_HOSTLIST_SCOPEMAP =
    $GET_HOSTLIST_BASE .
    "
    FROM
        USER                    AS US
        INNER JOIN  HOST_GROUP  AS HG ON
            (
                US.user_type_division   =   '1'
            AND US.unavailable_flag     =   '0'
            AND US.id                   =   HG.host_group_id
            AND HG.latitude             BETWEEN :strLati AND :endLati
            AND HG.longitude            BETWEEN :strLong AND :endLong
            )
        INNER JOIN  HOST_INFO   AS HI ON
            (
                US.id                   =   HI.host_group_id
            AND :current_date_ymd       <=  HI.holding_date_ymd
            )
        LEFT JOIN PHOTO         AS PH ON
            (
                HG.thumbnail_photo_id    = PH.photo_id
            )
    " . 
    $GET_HOSTLIST_SORTBASE;


$GET_HOSTLIST_SCOPWMAP_ONEWEEK =
    $GET_HOSTLIST_BASE .
    "
    FROM
        USER                    AS US
        INNER JOIN  HOST_GROUP  AS HG ON
            (
                US.user_type_division   =   '1'
            AND US.unavailable_flag     =   '0'
            AND US.id                   =   HG.host_group_id
            AND HG.latitude             BETWEEN :strLati AND :endLati
            AND HG.longitude            BETWEEN :strLong AND :endLong
            )
        INNER JOIN  HOST_INFO   AS HI ON
            (
                US.id                   =   HI.host_group_id
            AND :current_date_ymd       <=  HI.holding_date_ymd
            AND HI.holding_date_ymd     <=  :to_date_ymd
            )
        LEFT JOIN PHOTO         AS PH ON
            (
                HG.thumbnail_photo_id    = PH.photo_id
            )
    " . 
    $GET_HOSTLIST_SORTBASE;

$GET_HOSTLIST =
    $GET_HOSTLIST_BASE .
    "
    FROM
        USER                    AS US
        INNER JOIN  HOST_GROUP  AS HG ON
            (
                US.user_type_division   =   '1'
            AND US.unavailable_flag     =   '0'
            AND US.id                   =   HG.host_group_id
            )
        INNER JOIN  HOST_INFO   AS HI ON
            (
                US.id                   =   HI.host_group_id
            AND :current_date_ymd       <=  HI.holding_date_ymd
            )
        LEFT JOIN PHOTO         AS PH ON
            (
                HG.thumbnail_photo_id    = PH.photo_id
            )
    " . 
    $GET_HOSTLIST_SORTBASE;

$GET_HOSTLIST_ONEWEEK =
    $GET_HOSTLIST_BASE .
    "
    FROM
        USER                    AS US
        INNER JOIN  HOST_GROUP  AS HG ON
            (
                US.user_type_division   =   '1'
            AND US.unavailable_flag     =   '0'
            AND US.id                   =   HG.host_group_id
            )
        INNER JOIN  HOST_INFO   AS HI ON
            (
                US.id                   =   HI.host_group_id
            AND :current_date_ymd       <=  HI.holding_date_ymd
            AND HI.holding_date_ymd     <=  :to_date_ymd
            )
        LEFT JOIN PHOTO         AS PH ON
            (
                HG.thumbnail_photo_id    = PH.photo_id
            )
    " . 
    $GET_HOSTLIST_SORTBASE;

$QUERY_HOSTGROUPINFO = 
    "
    SELECT
        US.name                                    AS  host_group_name
    ,   HG.place_name                              AS  place_name
    ,   HG.holding_schedule                        AS  holding_schedule
    ,   HG.holding_time                            AS  holding_time
    ,   HG.latitude                                AS  latitude
    ,   HG.longitude                               AS  longitude
    ,   HG.directions                              AS  directions
    ,   HG.branch_scale                            AS  branch_scale
    ,   US.formal_hp_url                           AS  formal_hp_url
    ,   US.facebook_url                            AS  facebook_url
    ,   US.twitter_url                             AS  twitter_url
    ,   COALESCE(
            CONCAT(PH.filepath, PH.filename),
            'photo/now_printing.jpg')              AS  introduction_photo_url
    ,   HG.introduction_text                       AS  introduction_text
    FROM
        USER                    AS   US
        INNER JOIN  HOST_GROUP  AS   HG  ON
            (
                US.id                   =   :host_group_id
            AND US.user_type_division   =   '1'
            AND US.unavailable_flag     =   '0'
            AND US.id                   =   HG.host_group_id
            )
        LEFT JOIN PHOTO         AS   PH ON
            (
                HG.introduction_photo_id    = PH.photo_id
            )
    ";

?>