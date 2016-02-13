<?php
    
require_once "utils.php";
utils_init();

require_once "srvc_book_common.php";
require_once "srvc_book_file.php";
    
// trace the visitor
log_visitor_info();

function srvc_book_max_per_slot()
{
    return 10;
}
    
function srvc_book_reserve($guid, $guest_num, $visit_date, $visit_slot_in_mins)
{
    $rticket = new ReservationTicket($guid, $guest_num, $visit_date, $visit_slot_in_mins);
    
    if (!$rticket->is_valid())
    {
        return BOOK_CODE_ERR_INVALID;
    }
    
    return impl_book_do_reserve($rticket, srvc_book_max_per_slot());
}

// main
{
    $err = BOOK_CODE_OK;
    
    // GET param ==> function
    $action = array_string4key($_GET, "action");
    $phone = array_string4key($_GET, "phone");
    $wx_id = array_string4key($_GET, "wx_id");
    $guid = null;
    
    if ($phone != null)
    {
        $guid = new GuestUID($phone, TYPE_GUID_PHONE);
    }
    else if ($wx_id != null)
    {
        $guid = new GuestUID($wx_id, TYPE_GUID_WX_ID);
    }
    
    if ($guid == null || !$guid->is_valid())
    {
        $err = BOOK_CODE_ERR_INVALID;
        goto ERROR;
    }
    
    if ($action == "reserve")
    {
        $guest_num = array_number4key($_GET, "gnum");
        $visit_date = array_number4key($_GET, "vdate");
        $visit_mins_slot = array_number4key($_GET, "vmins");
        
        $err = srvc_book_reserve($guid, $guest_num, $visit_date, $visit_mins_slot);
    }
    else
    {
        // NO_IMPL
        $err = BOOK_CODE_ERR_INVALID;
    }
    
    if (!IS_BOOK_OK($err))
    {
        goto ERROR;
    }
    
DONE:
    $err = BOOK_CODE_OK;
    
ERROR:
    $desc = srvc_book_description_4_code($err);
    echo '{ "ERROR" : $err, "DESC" : $desc }';
    exit;
}
?>