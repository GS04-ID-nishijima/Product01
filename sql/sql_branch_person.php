<?php

$queryBranchPersonListBase = 
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

$queryBranchPersonListSortBase =
    "
    ORDER   BY
        HI.holding_date_ymd
    ,   US.name
    ";

$queryBranchPersonListAppoint =
    $queryBranchPersonListBase . 
    "
    AND HI.holding_date_ymd     =   :holding_date_ymd
    " . 
    $queryBranchPersonListSortBase;

$queryBranchPersonListFuture =
    $queryBranchPersonListBase . 
    "
    AND HI.holding_date_ymd     >=  :holding_date_ymd
    " . 
    $queryBranchPersonListSortBase;

$queryBranchPersonListAll =
    $queryBranchPersonListBase . $queryBranchPersonListSortBase;

?>