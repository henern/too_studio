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
                html2go += "<h1>" + vday.DATE + "</h1>";
                
                var j;
                for (j = 0; j < vday.SLOTS.length; j++)
                {
                    var vclock = vday.SLOTS[j];
                    html2go += "</br>";
                    html2go += "<h3>" + vclock.CLOCK + "</h3>, 共" + vclock.COUNT + "人";
                    
                    var i;
                    for (i = 0; i < vclock.VISITORs.length; i++)
                    {
                        var visitor = vclock.VISITORs[i];
                        html2go += "</br>";
                        html2go += visitor.GUID_STR + ": 预定" + visitor.GUEST_NUM + "人";
                        html2go += " [" + visitor.T_TOKEN + "]";
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
