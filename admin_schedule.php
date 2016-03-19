<?php
    

    
?>

<html>
	<head>
		<title>Too塗画室预定列表</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="email=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
        <script src="./js/book-toolkit.js"></script>
        <link rel="stylesheet" href="./css/base.css" type="text/css"/>
        <link rel="stylesheet" href="./css/book-default.css" type="text/css"/>

        <script>

        </script>
	</head>
	<body>
        <h1>正在加载数据...</h1>
        
        <script>
            book_do_query(function(code, description, json_list) {
                
            var html2go = "";
            
            var k;
            for (k = 0; k < json_list.length; k++)
            {
                var vday = json_list[k];
                html2go += "<h3>" + vday.DATE + "</h3>";
                
                var j;
                for (j = 0; j < vday.SLOTS.length; j++)
                {
                    var vclock = vday.SLOTS[j];
                    html2go += "</br>";
                    html2go += "<b>时间: " + vclock.CLOCK + ",  人数: " + vclock.COUNT + "</b>";
                    
                    var i;
                    for (i = 0; i < vclock.VISITORs.length; i++)
                    {
                        var visitor = vclock.VISITORs[i];
                        html2go += "</br>";
                        html2go += visitor.GUID_STR + ": 预定" + visitor.GUEST_NUM + "人";
                        html2go += " [<a href='./srvc_pay.php?action=query_ttoken&ttoken=" + visitor.T_TOKEN + "'>订单</a>] | [<a href='javascript:void'>退订</a>]";
                    }
                    html2go += "</br>";
                }
                
                html2go += "<hr></br>";
            }
            
            document.body.innerHTML = html2go;
            
            });
        </script>
        
	</body>
</html>
