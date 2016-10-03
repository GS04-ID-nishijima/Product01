<?php

$QUERY_BRANCHPERSONLIST_BASE = 
    "
    SELECT
        HI.holding_date_ymd                           AS  holding_date_ymd
    ,   BP.branch_person_id                           AS  branch_person_id
    ,   US.name                                       AS  branch_person_name
    ,   COALESCE(
            CONCAT(PH.filepath, PH.filename),
            'photo/hostgroup/now_printing.jpg')       AS  thumbnail_photo_url
    FROM
        HOST_INFO                AS  HI
        INNER JOIN BRANCH_PERSON AS  BP ON
            (
                HI.host_group_id        =   :host_group_id
            AND HI.branch_person_id     =   BP.branch_person_id
            )
        INNER JOIN USER          AS  US ON
            (
                BP.branch_person_id     =   US.id
            AND US.unavailable_flag     = '0'
            )
        LEFT JOIN PHOTO         AS PH ON
            (
                BP.thumbnail_photo_id    = PH.photo_id
            )
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
    ,   null               AS  thumbnail_photo_url
    FROM
        DUAL
    UNION ALL
    (
        SELECT
            HI.holding_date_ymd                           AS  holding_date_ymd
        ,   BP.branch_person_id                           AS  branch_person_id
        ,   US.name                                       AS  branch_person_name
        ,   COALESCE(
                CONCAT(PH.filepath, PH.filename),
                'photo/hostgroup/now_printing.jpg')       AS  thumbnail_photo_url
        FROM
            HOST_INFO                AS  HI
            INNER JOIN BRANCH_PERSON AS  BP ON
                (
                    HI.host_group_id        =   :host_group_id
                AND HI.branch_person_id     =   BP.branch_person_id
                AND HI.holding_date_ymd     =   :holding_date_ymd
                )
            INNER JOIN USER          AS  US ON
                (
                    BP.branch_person_id     =   US.id
                AND US.unavailable_flag     = '0'
                )
            LEFT JOIN PHOTO         AS PH ON
                (
                    BP.thumbnail_photo_id    = PH.photo_id
                )
        ORDER   BY
            holding_date_ymd
        ,   branch_person_name
    )
    ";


$QUERY_BRANCHPERSONLIST_FUTURE =
    "
    SELECT
        HI.holding_date_ymd                           AS  holding_date_ymd
    ,   BP.branch_person_id                           AS  branch_person_id
    ,   US.name                                       AS  branch_person_name
    ,   COALESCE(
            CONCAT(PH.filepath, PH.filename),
            'photo/hostgroup/now_printing.jpg')       AS  thumbnail_photo_url
    FROM
        HOST_INFO                AS  HI
        INNER JOIN BRANCH_PERSON AS  BP ON
            (
                HI.host_group_id        =   :host_group_id
            AND HI.branch_person_id     =   BP.branch_person_id
            AND HI.holding_date_ymd     >=  :holding_date_ymd
            )
        INNER JOIN USER          AS  US ON
            (
                BP.branch_person_id     =   US.id
            AND US.unavailable_flag     = '0'
            )
        LEFT JOIN PHOTO         AS PH ON
            (
                BP.thumbnail_photo_id    = PH.photo_id
            )
    " . 
    $QUERY_BRANCHPERSONLIST_SORTBASE;

$QUERY_BRANCHPERSONLIST_ALL =
    $QUERY_BRANCHPERSONLIST_BASE . $QUERY_BRANCHPERSONLIST_SORTBASE;

?>