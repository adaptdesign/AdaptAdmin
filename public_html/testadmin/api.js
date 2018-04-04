if (window.scriptloaded !== true) {
  //console.log("not loaded");
  var scriptloaded = true;
  var load_jquery_callback = function() {
    (function($) {
      $(document).ready(function() {
        $inc_params = false;
        function getParams(script_name) {
          var scripts = document.getElementsByTagName("script");
          for(var i=0; i<scripts.length; i++) {
            if(scripts[i].src.indexOf("/" + script_name) > -1) {
              var pa = scripts[i].src.split("?").pop().split("&");
              var p = {};
              for(var j=0; j<pa.length; j++) {
                var kv = pa[j].split("=");
                p[kv[0]] = kv[1];
              }
              return p;
            }
          }
          return {};
        }
        $inc_params = getParams("api.js");
        $inc_params['incdomain'] = window.location.hostname;
        $inc_params['incpage'] = window.location.pathname;
        $inc_params['increferrer'] = document.referrer;

        if($("#pageLogger").length) { $inc_params['logpage'] = "active"; }

        var url = "https://adaptadmin.com/pipe.php?page=pj4Q8wfz_aPLGqGX5WG7f-Pg8yYGBcgK--eEn8uMpjc&action=9Gs6Fg4vvli3CA-S9VI1Aep98YJzUcN3HnNCoFlWUag";
        var xhr = new XMLHttpRequest();
        xhr.open("get", url, true);
        xhr.onload = function () {
          console.log('Connected');
        };
        xhr.send(null);
    		$.ajax({
    				type: 'POST',
    				url: url,
    				data: $inc_params,
    				cache: false,
    				xhrFields: {
    						withCredentials: false
    				},
    		}).done(function ($result) {
    		  //console.log($inc_params);
    		  //console.log($result);
    		  var myObj = JSON.parse($result);
    		  if(myObj.status == "good") {
    		    $.each(myObj, function(i, item) {
              if($(".ada_"+i).length) { $(".ada_"+i).html(item);
              //window.location.href='http://testsite.com/test';
              }
            });

            if ($("#adminPage").length) {
              if ($("#adminPage iframe").length == 0) {
                var head = document.getElementsByTagName('head')[0];
                var css = 'body{background:#444444;margin:0;padding:0;overflow:hidden;} #adminPage iframe{width:100%;min-height:500px;}';
                var style = document.createElement('style');
                style.type = 'text/css';
                if (style.styleSheet){
                  style.styleSheet.cssText = css;
                } else {
                  style.appendChild(document.createTextNode(css));
                }
                head.appendChild(style);
                $('<iframe frameborder=0 id="adminPageFrame" src="https://adaptadmin.com/"/></iframe>').load(function() { }).appendTo('#adminPage');
                var buffer = 5; //scroll bar buffer
                var iframe = document.getElementById('adminPageFrame');
                function pageY(elem) {
                  return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
                }
                function resizeIframe() {
                  var height = document.documentElement.clientHeight;
                  height -= pageY(document.getElementById('adminPageFrame'))+ buffer ;
                  height = (height < 0) ? 0 : height;
                  document.getElementById('adminPageFrame').style.height = height + 'px';
                }
                if (iframe.attachEvent) {
                    iframe.attachEvent("onload", resizeIframe);
                } else {
                    iframe.onload=resizeIframe;
                }
                window.onresize = resizeIframe;
              }
            }

    		  } else {
    		    console.log("pull failed");
    		  }
    		});
      });
    })(jQuery);
  };
  function hashHandler(){
    this.oldHash = window.location.href;
    this.Check;
    var that = this;
    var detect = function(){
      if(that.oldHash!=window.location.href){
        load_jquery_callback();
        that.oldHash = window.location.href;
      }
    };
    this.Check = setInterval(function(){ detect() }, 1000);
  }
  var hashDetection = new hashHandler();

  if (typeof jQuery != 'undefined') {
    load_jquery_callback();
  }
  else {
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js';

    //calling a function after the js is loaded (IE)
    var loadFunction = function() {
      if (this.readyState == 'complete' || this.readyState == 'loaded') {
        script.onload = script.onreadystatechange = null;
        load_jquery_callback();
      }
    };
    script.onreadystatechange = loadFunction;

    //calling a function after the js is loaded (Firefox)
    script.onload = load_jquery_callback;
    head.appendChild(script);
  }
} else {
  //console.log("loaded");
}
