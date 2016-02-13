function book_do_reserve(phone, num, vdate, vmins_slot, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "srvc_book.php?action=reserve" + 
              "&gnum=" + num + 
              "&vdate=" + vdate + 
              "&vmins=" + vmins_slot + 
              "&phone=" + phone;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["ERROR"];
            var description = json["DESC"];
            
            callback(code, description);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}
