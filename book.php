<?php
    
require_once "wx_dev.php";
utils_init();

# trace the visitor
log_visitor_info();

$guest_num_max = 10;
$guest_num_default = 2;
$BOARD_M_DEFAULT = $guest_num_default;
    
$open_hour_begin = 14 * 60 + 0;     # 14:00
$open_hour_end = 20 * 60 + 0;       # 20:00
$open_hour_slot = 30;
$open_hour_day = 7;
    
$right_now_day = full_date();

// try to get wx-openid
$wx_code = array_string4key($_GET, "code");
if ($wx_code == null)
{
    $wx_code = "";
}

?>

<html>
	<head>
		<title>Too塗画室在线预定</title>
		
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
            
            function on_select_changed(select_id, binding2_id)
            {
                var element_select = document.getElementById(select_id);
                var indx_selected = element_select.selectedIndex;
                
                var element_bind2 = document.getElementById(binding2_id);
                
                element_bind2.innerHTML = element_select.options[indx_selected].innerHTML;
            }
            
            function confirm_to_pay(callback, timeout)
            {
                setTimeout(function(){
                    
                    if (confirm("预订成功啦！在线支付更享95折喔，到店支付也行，试试在线支付？"))
                    {
                        callback();
                    }
                    
                }, timeout);
            }
            
            function on_click_to_reserve()
            {
                var g_phone = document.getElementById("J-input-phone").value;
                var g_num   = document.getElementById("J-person-select").value;
                var v_date  = document.getElementById("J-date-select").value;
                var v_slot  = document.getElementById("J-time-select").value;
                
                var v_board_large = document.getElementById("J-board-large").value;
                var v_board_small = document.getElementById("J-board-small").value;
                var v_board_medium = document.getElementById("J-board-medium").value;
                
                var btn_reserve = document.getElementById("J_submit");
                btn_reserve.innerHTML = "正在努力预定...";
                
                book_do_reserve(g_phone, 
                                g_num, 
                                <?php echo "\"" . "$wx_code" . "\"" ?>,
                                v_date, 
                                v_slot, 
                                v_board_small, 
                                v_board_medium, 
                                v_board_large, 
                                function(result_code, result_ttoken, result_desc)
                {
                    
                    if (result_code >= 0)
                    {
                        btn_reserve.innerHTML = "预定成功，恭候大驾";
                        confirm_to_pay(function(){
                            window.location.assign("./srvc_pay_auth.php?count=" + g_num + 
                                                   "&visit_day=" + v_date + 
                                                   "&time_slot=" + v_slot + 
                                                   "&phone=" + g_phone + 
                                                   "&small_b=" + v_board_small + 
                                                   "&medium_b=" + v_board_medium + 
                                                   "&large_b=" + v_board_large + 
                                                   "&ttoken=" + result_ttoken);
                        }, 200);
                    }
                    else
                    {
                        btn_reserve.innerHTML = "预定失败（" + result_desc + "）";
                    }
                });
            }
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
                <select class="select-overlay" id="J-person-select" onchange="javascript:on_select_changed('J-person-select', 'J-input-person')">
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
                    <select class="select-overlay" id="J-date-select" onchange="javascript:on_select_changed('J-date-select', 'J-input-date')">
                        <?php
                            
                        $clock_cur = time();
                        for ($k = 0; $k < $open_hour_day; $k++)
                        {
                            $ts = $clock_cur + $k * SEC_PER_DAY;
                            $str = full_date($ts);
                            $date_val = date("Ymd", $ts);
                            
                            if ($str == $right_now_day)
                            {
                                echo "<option value='$date_val' selected>$str</option>";
                            }
                            else
                            {
                                echo "<option value='$date_val'>$str</option>";
                            }
                            
                        }
                        
                        ?>
                    </select>
                </div>
                <div class="time-sel J-time-trigger">
                    <span class="value" id="J-input-time"><?php echo minutes_to_clock_str($open_hour_begin); ?></span>
                    <i class="caret"></i>
                    <select class="select-overlay" id="J-time-select" onchange="javascript:on_select_changed('J-time-select', 'J-input-time')">
                        <?php
                            
                        $right_now_hour = $open_hour_begin; // TODO: should be in the future
                        
                        for ($cur = $open_hour_begin; $cur <= $open_hour_end; $cur += $open_hour_slot)
                        {
                            $clock_str = minutes_to_clock_str($cur);
                            
                            if ($cur == $right_now_hour)
                            {
                                echo "<option value='$cur' selected>$clock_str</option>";
                            }
                            else
                            {
                                echo "<option value='$cur'>$clock_str</option>";
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
                <!--
                <div class="row">
                    <div class="radio-group">
                        <span id="J-input-female" class="radio checked">女士</span>
                        <span id="J-input-male" class="radio ">先生</span>
                    </div>
                    <div class="input">
                        <input id="J-input-name" type="text" placeholder="您贵姓" />
                    </div>
                </div>
                -->
                <div class="row">
                    <div class="input">
                        <input id="J-input-phone" type="tel" placeholder="请输入手机号" />
                    </div>
                </div>
            </div>
            <div class="msg msg-contact-err hide"></div>
        </section>
        		
        <section class="contact">
        <div class="row-group">
        <div class="row">
		<table id="board-group" style="border:none" cellspacing="0" width="100%">
		<!--小画板-->
		<tr>
			<td class="board_img"><img class="board_img" src='./img/too-board-small.png'/></td>
			<td align="center">
                <span class="value board_sel" id="J-input-board-small">0</span><label>张</label>
                <i class="caret"></i>
                <select class="select-overlay" id="J-board-small" onchange="javascript:on_select_changed('J-board-small', 'J-input-board-small')">
                    <option value='0' selected>0</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                </select>
			</td>
		</tr>	
        </table>
        </div>
        
        <div class="row">
        <table id="board-group" style="border:none" cellspacing="0" width="100%">
		<!--中画板-->
		<tr>
			<td class="board_img"><img class="board_img" src='./img/too-board-medium.png'/></td>
			<td align="center">
                <span class="value board_sel" id="J-input-board-medium"><?php echo $BOARD_M_DEFAULT ?></span><label>张</label>
                <i class="caret"></i>
                <select class="select-overlay" id="J-board-medium" onchange="javascript:on_select_changed('J-board-medium', 'J-input-board-medium')">
                    <?php
                        
                    for ($cur = 0; $cur <= 6; $cur++)
                    {
                        if ($cur == $BOARD_M_DEFAULT)
                        {
                            echo "<option value='$cur' selected>$cur</option>";
                        }
                        else
                        {
                            echo "<option value='$cur'>$cur</option>";
                        }
                    }
                    
                    ?>
                    
                </select>
			</td>
		</tr>
        </table>
        </div>
        
        <div class="row">
        <table id="board-group" style="border:none" cellspacing="0" width="100%">
		<!--大画板-->
		<tr>
			<td class="board_img"><img class="board_img" src='./img/too-board-large.png'/></td>
			<td align="center">
                <span class="value board_sel" id="J-input-board-large">0</span><label>张</label>
                <i class="caret"></i>
                <select class="select-overlay" id="J-board-large" onchange="javascript:on_select_changed('J-board-large', 'J-input-board-large')">
                    <option value='0' selected>0</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                </select>
			</td>
		</tr>
		</table>
        </div>
        </div>
        </section>
    
        <a id="J_submit" class="btn-huge" href="javascript:on_click_to_reserve();">马上预订</a>
		<!--内容 end-->
<footer class="footer">
    <p class="copyright">Copyright ©2016 Too塗Studio</p>
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