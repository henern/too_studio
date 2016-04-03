function book_do_reserve(phone, 
                         num, 
                         vdate, 
                         vmins_slot, 
                         small_board, 
                         medium_board, 
                         large_board, 
                         callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book_auth.php?action=reserve" + 
              "&gnum=" + num + 
              "&vdate=" + vdate + 
              "&vmins=" + vmins_slot + 
              "&phone=" + phone +
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
