<?php
    
define("BOOK_CODE_OK",              0);
define("BOOK_CODE_ERR_UNKNOWN",     -10);
define("BOOK_CODE_ERR_FULL",        -11);
define("BOOK_CODE_ERR_INVALID",     -12);
define("BOOK_CODE_ERR_CORRUPT",     -13);
define("BOOK_CODE_ERR_DUP",         -14);

function IS_BOOK_OK($code)
{
    return $code >= BOOK_CODE_OK;
}

function srvc_book_description_4_code($code)
{
    if (IS_BOOK_OK($code))
    {
        return "OK";
    }
    else if ($code == BOOK_CODE_ERR_FULL)
    {
        return "该时段已经爆满了";
    }
    else if ($code == BOOK_CODE_ERR_INVALID)
    {
        return "无效请求";
    }
    else if ($code == BOOK_CODE_ERR_CORRUPT)
    {
        return "数据库挂了";
    }
    else if ($code == BOOK_CODE_ERR_DUP)
    {
        return "已预约过啦";
    }
    else
    {
        // maybe BOOK_CODE_ERR_UNKNOWN
        return "内部错误";
    }
}
    
define("KEY_GUID_TYPE",     "GUID_TYPE");
define("KEY_GUID_VALUE",    "GUID_VAL");
define("KEY_GUID_OID",      "GUID_OID");

define("TYPE_GUID_PHONE",   1000);
define("TYPE_GUID_WX_ID",   1001);
define("TYPE_GUID_MAX",     TYPE_GUID_WX_ID + 1);

class GuestUID
{
    var $type;       // PHONE, WXID, etc.
    var $val;
    var $oid;
    
    function is_equal_to($guid)
    {
        if (($guid instanceof GuestUID) &&
            $this->type == $guid->type &&
            strcmp($this->val, $guid->val) == 0)
        {
            return true;
        }
        
        return false;
    }
    
    function to_string()
    {
        $ret = "UNKNOWN";
        if (TYPE_GUID_PHONE == $this->type)
        {
            $ret = "PHONE";
        }
        else if (TYPE_GUID_WX_ID == $this->type)
        {
            $ret = "WX";
        }
        
        return $ret . "_" . $this->val;
    }
    
    function to_array()
    {
        return array(KEY_GUID_TYPE  => $this->type, 
                     KEY_GUID_OID   => $this->oid,
                     KEY_GUID_VALUE => $this->val);
    }
    
    function from_array($arr)
    {
        if (array_key_exists(KEY_GUID_TYPE, $arr))
        {
            $this->type = $this->valid_type($arr[KEY_GUID_TYPE]);
        }
        
        if (array_key_exists(KEY_GUID_VALUE, $arr))
        {
            $this->val = $arr[KEY_GUID_VALUE];
        }
        
        if (array_key_exists(KEY_GUID_OID, $arr))
        {
            $this->oid = $arr[KEY_GUID_OID];
        }
    }
    
    function __construct($val, $type)
    {
        $this->type = $this->valid_type($type);
        $this->val = $val;
        $this->oid = "";
    }
    
    function valid_type($type)
    {
        if ($type != TYPE_GUID_PHONE && 
            $type != TYPE_GUID_WX_ID)
        {
            return TYPE_GUID_PHONE;
        }
        
        return $type;
    }
    
    function is_valid()
    {
        return (TYPE_GUID_PHONE <= $this->type && $this->type < TYPE_GUID_MAX &&
                is_string($this->val) &&
                strlen($this->val) < 15);
    }
}
    
define("KEY_RTICKET_GUID",          "RTICKET_GUID");
define("KEY_RTICKET_NUM",           "RTICKET_NUM");
define("KEY_RTICKET_V_DATE",        "RTICKET_V_DATE");
define("KEY_RTICKET_V_MINS_SLOT",   "RTICKET_V_MINS_SLOT");
define("KEY_RTICKET_TRADE_TOKEN",   "RTICKET_TRADE_TOKEN");
define("PREFIX_TRADE_TOKEN",        "TOOWX");

define("KEY_RTICKET_BOARD_SMALL",   "RTICKET_BOARD_SMALL");
define("KEY_RTICKET_BOARD_MEDIUM",  "RTICKET_BOARD_MEDIUM");
define("KEY_RTICKET_BOARD_LARGE",   "RTICKET_BOARD_LARGE");

class ReservationTicket
{
    var $guid;
    var $num;
    var $visit_date;
    var $visit_mins_slot;
    var $trade_token;
    var $small_board;
    var $medium_board;
    var $large_board;
    
    function to_array()
    {
        return array(KEY_RTICKET_GUID           => $this->guid->to_array(),
                     KEY_RTICKET_NUM            => $this->num,
                     KEY_RTICKET_TRADE_TOKEN    => $this->trade_token,
                     KEY_RTICKET_V_DATE         => $this->visit_date,
                     KEY_RTICKET_BOARD_SMALL    => $this->small_board,
                     KEY_RTICKET_BOARD_MEDIUM   => $this->medium_board,
                     KEY_RTICKET_BOARD_LARGE    => $this->large_board,
                     KEY_RTICKET_V_MINS_SLOT    => $this->visit_mins_slot);
    }
    
    function from_array($arr)
    {
        $this->guid = new GuestUID("", 0);
        $this->guid->from_array($arr[KEY_RTICKET_GUID]);
        
        $this->trade_token = $arr[KEY_RTICKET_TRADE_TOKEN];
        
        $this->num = $arr[KEY_RTICKET_NUM];
        $this->visit_date = $arr[KEY_RTICKET_V_DATE];
        $this->visit_mins_slot = $arr[KEY_RTICKET_V_MINS_SLOT];
        
        $this->small_board  = $arr[KEY_RTICKET_BOARD_SMALL];
        $this->medium_board = $arr[KEY_RTICKET_BOARD_MEDIUM];
        $this->large_board  = $arr[KEY_RTICKET_BOARD_LARGE];
        
        return $this->is_valid();
    }
    
    function __trade_token($guid)
    {
        if ($guid == null)
        {
            return "";
        }
        
        $prefix_STR5 = PREFIX_TRADE_TOKEN;
        
        $guid_md5_STR8 = md5($guid->to_string());
        if (strlen($guid_md5_STR8) > 8)
        {
            $guid_md5_STR8 = substr($guid_md5_STR8, -8);
        }
        
        $clock_now = time();
        $date_STR14 = date("YmdHis", $clock_now);
        $clock_STR5 = substr("$clock_now", -5);
        
        return $prefix_STR5 . $date_STR14 . $guid_md5_STR8 . $clock_STR5;
    }
    
    function __construct($guid, $num, $v_date, $v_mins_slot, $small_b = 0, $medium_b = 0, $large_b = 0)
    {
        $this->guid = $guid;
        $this->num = $num + 0;
        $this->visit_date = $v_date;
        $this->visit_mins_slot = $v_mins_slot;
        $this->trade_token = $this->__trade_token($guid);
        
        $this->small_board = $small_b + 0;
        $this->medium_board = $medium_b + 0;
        $this->large_board = $large_b + 0;
    }
    
    function is_valid()
    {
        return ($this->guid instanceof GuestUID) &&
               is_int($this->num) &&
               strlen($this->trade_token) > 0 &&
               strpos($this->trade_token, PREFIX_TRADE_TOKEN) == 0 &&
               is_int($this->small_board) &&
               is_int($this->medium_board) &&
               is_int($this->large_board) &&
               ($this->small_board + $this->medium_board + $this->large_board) > 0 &&
               is_numeric($this->visit_date) &&
               is_numeric($this->visit_mins_slot);
    }
}

?>