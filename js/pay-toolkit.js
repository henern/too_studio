function pay_do_query_ttoken(ttoken, callback)
{
    var xhr = new XMLHttpRequest();
    
    var url = "./srvc_pay.php?action=query_ttoken&ttoken=" + ttoken;
    
    xhr.onreadystatechange = function() {
        
        if (xhr.readyState == 4 && xhr.status == 200)
        {
            var code = "ERROR";
            var fee = 0;
            try
            {
                var json = JSON.parse(xhr.responseText);
                code = json["result_code"];
                fee = json["total_fee"];
            }
            catch(err)
            {
                code = "ERROR";
            }
            
            callback(code, fee);
        }
    };
    xhr.open("GET", url, true);
    xhr.send(null);
}
