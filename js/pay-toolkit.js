function pay_do_query_ttoken(ttoken, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "./srvc_pay.php?action=query_ttoken&ttoken=" + ttoken;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var json = JSON.parse(xhr.responseText);
            var code = json["result_code"];
            var fee = json["total_fee"];
            
            callback(code, fee);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}
