<?php

$queryHoldingDateYmdListBase =
    "
    SELECT
        DISTINCT
        HI.holding_date_ymd     AS holding_date_ymd
    FROM
        HOST_INFO       HI
    ";

$queryHoldingDateYmdListSortBaseFutre =
    "
    ORDER BY
        HI.holding_date_ymd
    LIMIT 5
    ";

$queryHoldingDateYmdListSortBasePast =
    "
    ORDER BY
        HI.holding_date_ymd DESC
    LIMIT 5
    ";

$queryHoldingDateYmdListHostGroupFuture =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd >= :current_date_ymd
    " .
    $queryHoldingDateYmdListSortBaseFutre;

$queryHoldingDateYmdListHostGroupPast =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.host_group_id    = :host_group_id
    AND HI.holding_date_ymd <= :current_date_ymd
    " .
    $queryHoldingDateYmdListSortBasePast;

$queryHoldingDateYmdListBranchPersonFuture =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd >=  :current_date_ymd
    " .
    $queryHoldingDateYmdListSortBaseFutre;

$queryHoldingDateYmdListBranchPersonPast =
    $queryHoldingDateYmdListBase .
    "
    WHERE
        HI.branch_person_id =   :branch_person_id
    AND HI.holding_date_ymd <=  :current_date_ymd
    " .
    $queryHoldingDateYmdListSortBasePast;

?>