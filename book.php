<!DOCTYPE html>
<html class="G_N">
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
            <header>
                <div class="placeholder"></div>
                <div class="title">我要订座</div>
			</header>
		<!--内容-->
                <section class="info">
            <div class="people-sel J-person-trigger">
                <label>人数</label>
                <span class="value" id="J-input-person">4</span>
                <i class="caret"></i>
                <select class="select-overlay" id="J-person-select">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4" selected>4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                </select>
            </div>
            <div class="datetime-sel">
                <div class="date-sel J-date-trigger">
                    <span class="value" id="J-input-date">2016-02-12 星期五</span>
                    <i class="caret"></i>
                </div>
                <div class="time-sel J-time-trigger">
                    <span class="value" id="J-input-time">11:00</span>
                    <i class="caret"></i>
                    <select class="select-overlay" id="J-time-select">
                            <option value="00:00">00:00</option>
                            <option value="00:15">00:15</option>
                            <option value="00:30">00:30</option>
                            <option value="00:45">00:45</option>
                            <option value="01:00">01:00</option>
                            <option value="01:15">01:15</option>
                            <option value="01:30">01:30</option>
                            <option value="01:45">01:45</option>
                            <option value="02:00">02:00</option>
                            <option value="02:15">02:15</option>
                            <option value="02:30">02:30</option>
                            <option value="02:45">02:45</option>
                            <option value="03:00">03:00</option>
                            <option value="03:15">03:15</option>
                            <option value="03:30">03:30</option>
                            <option value="03:45">03:45</option>
                            <option value="04:00">04:00</option>
                            <option value="04:15">04:15</option>
                            <option value="04:30">04:30</option>
                            <option value="04:45">04:45</option>
                            <option value="05:00">05:00</option>
                            <option value="05:15">05:15</option>
                            <option value="05:30">05:30</option>
                            <option value="05:45">05:45</option>
                            <option value="06:00">06:00</option>
                            <option value="06:15">06:15</option>
                            <option value="06:30">06:30</option>
                            <option value="06:45">06:45</option>
                            <option value="07:00">07:00</option>
                            <option value="07:15">07:15</option>
                            <option value="07:30">07:30</option>
                            <option value="07:45">07:45</option>
                            <option value="08:00">08:00</option>
                            <option value="08:15">08:15</option>
                            <option value="08:30">08:30</option>
                            <option value="08:45">08:45</option>
                            <option value="09:00">09:00</option>
                            <option value="09:15">09:15</option>
                            <option value="09:30">09:30</option>
                            <option value="09:45">09:45</option>
                            <option value="10:00">10:00</option>
                            <option value="10:15">10:15</option>
                            <option value="10:30">10:30</option>
                            <option value="10:45">10:45</option>
                            <option value="11:00" selected>11:00</option>
                            <option value="11:15">11:15</option>
                            <option value="11:30">11:30</option>
                            <option value="11:45">11:45</option>
                            <option value="12:00">12:00</option>
                            <option value="12:15">12:15</option>
                            <option value="12:30">12:30</option>
                            <option value="12:45">12:45</option>
                            <option value="13:00">13:00</option>
                            <option value="13:15">13:15</option>
                            <option value="13:30">13:30</option>
                            <option value="13:45">13:45</option>
                            <option value="14:00">14:00</option>
                            <option value="14:15">14:15</option>
                            <option value="14:30">14:30</option>
                            <option value="14:45">14:45</option>
                            <option value="15:00">15:00</option>
                            <option value="15:15">15:15</option>
                            <option value="15:30">15:30</option>
                            <option value="15:45">15:45</option>
                            <option value="16:00">16:00</option>
                            <option value="16:15">16:15</option>
                            <option value="16:30">16:30</option>
                            <option value="16:45">16:45</option>
                            <option value="17:00">17:00</option>
                            <option value="17:15">17:15</option>
                            <option value="17:30">17:30</option>
                            <option value="17:45">17:45</option>
                            <option value="18:00">18:00</option>
                            <option value="18:15">18:15</option>
                            <option value="18:30">18:30</option>
                            <option value="18:45">18:45</option>
                            <option value="19:00">19:00</option>
                            <option value="19:15">19:15</option>
                            <option value="19:30">19:30</option>
                            <option value="19:45">19:45</option>
                            <option value="20:00">20:00</option>
                            <option value="20:15">20:15</option>
                            <option value="20:30">20:30</option>
                            <option value="20:45">20:45</option>
                            <option value="21:00">21:00</option>
                            <option value="21:15">21:15</option>
                            <option value="21:30">21:30</option>
                            <option value="21:45">21:45</option>
                            <option value="22:00">22:00</option>
                            <option value="22:15">22:15</option>
                            <option value="22:30">22:30</option>
                            <option value="22:45">22:45</option>
                            <option value="23:00">23:00</option>
                            <option value="23:15">23:15</option>
                            <option value="23:30">23:30</option>
                            <option value="23:45">23:45</option>
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