<?php

$QUERY_INS_UPLOAD_INFO =
    "
    INSERT INTO UPLOAD_INFO
    (
        holding_date_ymd
    ,   host_group_id
    ,   photo_id
    ,   branch_person_id
    ,   upload_user_id
    ,   upload_date_ymd
    ,   upload_date_time
    ) VALUES (
        :holding_date_ymd
    ,   :host_group_id
    ,   :photo_id
    ,   :branch_person_id
    ,   :upload_user_id
    ,   :upload_date_ymd
    ,   :upload_date_time
    )
    ";

?>