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
            callback(xhr.responseText);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}
