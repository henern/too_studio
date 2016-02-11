<?php
    
require "utils_time.php";

$guest_num_max = 10;
$guest_num_default = 2;
    
$open_hour_begin = 10 * 60 + 30;    # 10:30
$open_hour_end = 20 * 60 + 0;       # 20:00
$open_hour_slot = 30;
$open_hour_day = 7;
    
$right_now_day = full_date();

?>

<html>
	<head>
		<title>在线订座</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="email=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
        <link rel="stylesheet" href="./css/base.css" type="text/css"/>
        <link rel="stylesheet" href="./css/book-default.css" type="text/css"/>

        <script>
            var _hip = [['_setPageId', 210001]];
            var executionTime = new Date().getTime();
            window.onload=function() {
                var readyTime = new Date().getTime();
                var bodyTag;
                if((readyTime - executionTime) < 3000){
                    if(document.documentElement.scrollHeight <= document.documentElement.clientHeight) {
                        bodyTag = document.getElementsByTagName('body')[0];
                        bodyTag.style.height = document.documentElement.clientWidth / screen.width * screen.height + 'px';
                    }
                    if(screen.width > 980 || screen.height > 980) return;
                    if(window.navigator.standalone === true) return;
                    if(window.innerWidth !== document.documentElement.clientWidth) {
                        if((window.innerWidth - 1) !== document.documentElement.clientWidth) return;
                    }
                    setTimeout(function() {
                        if(window.pageYOffset !== 0) return;
                        window.scrollTo(0, 1);
                        if(bodyTag !== undefined) bodyTag.style.height = window.innerHeight + 'px';
                        window.scrollTo(0, 0);
                    }, 300);
                }
            };
        </script>
		
	</head>
	<body id="top">
        <!--icon for wechat-->
        <div style='display:none;'>
            <img src='./img/too-icon.jpeg'/>
        </div>
        
        <header>
            <div class="placeholder"></div>
            <div class="title">我要预定</div>
        </header>

        <section class="info">
            <div class="people-sel J-person-trigger">
                <label>人数</label>
                <span class="value" id="J-input-person"><?php echo $guest_num_default ?></span>
                <i class="caret"></i>
                <select class="select-overlay" id="J-person-select">
                    <?php
                        for ($j = 1; $j <= $guest_num_max; $j++)
                        {
                            if ($j == $guest_num_default)
                            {
                                echo "<option value='$j' selected>$j</option>";
                            }
                            else
                            {
                                echo "<option value='$j'>$j</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="datetime-sel">
                <div class="date-sel J-date-trigger">
                    <span class="value" id="J-input-date"><?php echo $right_now_day; ?></span>
                    <i class="caret"></i>
                    <select class="select-overlay" id="J-date-select">
                        <?php
                            
                        $clock_cur = time();
                        for ($k = 0; $k < $open_hour_day; $k++)
                        {
                            $ts = $clock_cur + $k * $g_sec_per_day;
                            $str = full_date($ts);
                            
                            if ($str == $right_now_day)
                            {
                                echo "<option value='$str' selected>$str</option>";
                            }
                            else
                            {
                                echo "<option value='$str'>$str</option>";
                            }
                            
                        }
                        
                        ?>
                    </select>
                </div>
                <div class="time-sel J-time-trigger">
                    <span class="value" id="J-input-time"><?php echo minutes_to_clock_str($open_hour_begin); ?></span>
                    <i class="caret"></i>
                    <select class="select-overlay" id="J-time-select">
                        <?php
                            
                        for ($cur = $open_hour_begin; $cur <= $open_hour_end; $cur += $open_hour_slot)
                        {
                            $clock_str = minutes_to_clock_str($cur);
                            
                            if ($clock_str == $right_now_hour)
                            {
                                echo "<option value='$clock_str' selected>$clock_str</option>";
                            }
                            else
                            {
                                echo "<option value='$clock_str'>$clock_str</option>";
                            }
                            
                        }
                        
                        ?>
                    </select>
                </div>
            </div>
            <div class="msg msg-full hide">该时间段已订满，请换个时间</div>
        </section>
                        
        <section class="contact">
            <div class="row-group">
                <div class="row">
                    <div class="radio-group">
                        <span id="J-input-female" class="radio checked">女士</span>
                        <span id="J-input-male" class="radio ">先生</span>
                    </div>
                    <div class="input">
                        <input id="J-input-name" type="text" placeholder="您贵姓" />
                    </div>
                </div>
                <div class="row">
                    <div class="input">
                        <input id="J-input-phone" type="tel" placeholder="请输入手机号" />
                    </div>
                </div>
            </div>
            <div class="msg msg-contact-err hide"></div>
        </section>
        
        <a id="J_submit" class="btn-huge" href="javascript:;">马上订座</a>
        <div class="pop-main-wrap hide" id="J-groupon-select">
			<div class="pop-main pop-tuanph J-pop-tuanph hide">
				<div class="pop-con">
					<h5>团购预订请拨打商户电话</h5>
					<ul>
								<li><a href="tel:010-64300028" class="J-groupon-phone">拨打电话 010-64300028</a></li>
								<li><a href="tel:13371682135" class="J-groupon-phone">拨打电话 13371682135</a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--内容 end-->
<footer class="footer">
    <p class="copyright">Copyright ©2016 Too-Studio</p>
</footer>     
<script type="text/javascript">

		//得到焦点触发事件
		function OnfocusFun(element,elementvalue)
		{
		    if(element.value==elementvalue)
		    {
		        element.value="";
		    }
		}
		//离开输入框触发事件
		function OnBlurFun(element,elementvalue)
		{
		    if(element.value==""||element.value.replace(/\s/g,"")=="")
		    {
		        element.value=elementvalue;
		    }
		}
		</script>
		
	</body>
</html>