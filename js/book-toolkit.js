function book_do_reserve(phone, 
                         num, 
                         openid,
                         vdate, 
                         vmins_slot, 
                         small_board, 
                         medium_board, 
                         large_board, 
                         callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=reserve" + 
              "&gnum=" + num + 
              "&vdate=" + vdate + 
              "&vmins=" + vmins_slot + 
              "&phone=" + phone +
              "&oid=" + openid +
              "&small_b=" + small_board +
              "&medium_b=" + medium_board +
              "&large_b=" + large_board;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
            var ttoken = json["TTOKEN"];
            
            callback(code, ttoken, description);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}

function book_do_query(callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=query_schedule";
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
            var results = json["RESULT"];
            
            callback(code, description, results);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}

function verify_mobile(phone_num)
{ 
    if ((/^1[3|4|5|8][0-9]\d{4,8}$/.test(phone_num)))
    { 
        return true; 
    } 
    
    return false;
} 

function book_do_query_block(callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=query_block";
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
            var results = json["RESULT"];
            
            callback(code, description, results);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}

function book_do_block(vdate, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=block&vdate=" + vdate;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}

function book_do_unblock(vdate, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=unblock&vdate=" + vdate;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}

function book_do_block_ts(vdate, ts, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=blockts&vdate=" + vdate + "&ts=" + ts;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}

function book_do_unblock_ts(vdate, ts, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=unblockts&vdate=" + vdate + "&ts=" + ts;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}