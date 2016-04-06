<?php
    
require_once "wx_dev.php";
utils_init();

require_once "srvc_book_common.php";
require_once "srvc_book_file.php";
    
// trace the visitor
log_visitor_info();

function srvc_book_max_per_slot()
{
    return 10;
}
    
function srvc_book_rticket_to_string($rticket)
{
    $str = " " . $rticket->guid->to_string();
    $str .= "-" . "$rticket->num" . "人";
    $str .= "-" .
            "$rticket->large_board"     . "大" . 
            "$rticket->medium_board"    . "中" . 
            "$rticket->small_board"     . "小";
    $str .= "-" . $rticket->visit_date;
    $str .= "-" . minutes_to_clock_str($rticket->visit_mins_slot);
    
    return $str;
}

function srvc_book_block($vdate)
{
    $err = impl_book_lock_date($vdate);
    return $err;
}

function srvc_book_unblock($vdate)
{
    $err = impl_book_unlock_date($vdate);
    return $err;
}

function srvc_book_is_blocked($vdate)
{
    $err = impl_book_date_is_locked($vdate);
    return $err;
}

function srvc_book_query_block($next_n_days, &$result_arr)
{
    if ($next_n_days >= 0)
    {
        return impl_book_query_lock(1, $next_n_days, $result_arr);
    }
    
    return BOOK_CODE_ERR_INVALID;
}

function srvc_book_reserve($guid, 
                           $guest_num, 
                           $visit_date, 
                           $visit_slot_in_mins, 
                           $small_board, 
                           $medium_board, 
                           $large_board, 
                           &$trade_token)
{
    $rticket = new ReservationTicket($guid, 
                                     $guest_num, 
                                     $visit_date, 
                                     $visit_slot_in_mins, 
                                     $small_board, 
                                     $medium_board, 
                                     $large_board);
    $trade_token = $rticket->trade_token;
    
    if (!$rticket->is_valid())
    {
        return BOOK_CODE_ERR_INVALID;
    }
    
    $err = impl_book_do_reserve($rticket, srvc_book_max_per_slot());
    
    if (IS_BOOK_OK($err))
    {
        $subject = "[Too塗预约单]";
        $subject .= srvc_book_rticket_to_string($rticket);
        
        email_send_to_many(array(TOO_WX_MAIL_ADMIN_Y, TOO_WX_MAIL_ADMIN_W),
                           $subject,
                           json_encode($rticket->to_array()),
                           notify_email(TOO_HOST_URL));
    }
    
    return $err;
}

function srvc_book_query_schedule($next_n_days, &$result_arr)
{
    if ($next_n_days >= 0)
    {
        return impl_book_query_schedule(1, $next_n_days, $result_arr);
    }
    
    return BOOK_CODE_ERR_INVALID;
}

// main
{
    $err = BOOK_CODE_OK;
    $result_arr = array();
    $result_json_str = json_encode($result_arr);
    $trade_token = "";
    
    // GET param ==> function
    $action = array_string4key($_GET, "action");
    
    if ($action == "reserve")
    {
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
        
        // wx-openid is required
        $oid = array_string4key($_GET, "oid"); 
        $guid->oid = $oid;
        
        $guest_num = array_number4key($_GET, "gnum");
        $visit_date = array_number4key($_GET, "vdate");
        $visit_mins_slot = array_number4key($_GET, "vmins");
        
        $small_board = array_number4key($_GET, "small_b");
        $medium_board = array_number4key($_GET, "medium_b");
        $large_board = array_number4key($_GET, "large_b");
        
        $err = srvc_book_reserve($guid, 
                                 $guest_num, 
                                 $visit_date, 
                                 $visit_mins_slot, 
                                 $small_board, 
                                 $medium_board, 
                                 $large_board, 
                                 $trade_token);
    }
    else if ($action == "query_schedule")
    {
        // by default, query the reservations for the next 2 weeks
        $err = srvc_book_query_schedule(7 * 2, $result_arr);
    }
    else if ($action == "block")
    {
        $visit_date = array_number4key($_GET, "vdate");
        $err = srvc_book_block($visit_date);
    }
    else if ($action == "unblock")
    {
        $visit_date = array_number4key($_GET, "vdate");
        $err = srvc_book_unblock($visit_date);
    }
    else if ($action == "query_block")
    {
        $err = srvc_book_query_block(7 * 4, $result_arr);
    }
    else
    {
        // NO_IMPL
        $err = BOOK_CODE_ERR_INVALID;
    }
    
    if (!IS_BOOK_OK($err))
    {
        $trade_token = "";
        goto ERROR;
    }
    
DONE:
    $err = BOOK_CODE_OK;
    $result_json_str = json_encode($result_arr);
	
ERROR:
    $desc = srvc_book_description_4_code($err);
    echo "{ \"ERROR\" : $err, \"DESC\" : \"$desc\", \"TTOKEN\" : \"$trade_token\", \"RESULT\" : $result_json_str }";
    exit;
}
?>