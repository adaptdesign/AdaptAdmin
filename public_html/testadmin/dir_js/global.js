$(document).ready(function() {
	var reloadcount = 0;
	var baseurl = window.location.protocol + "//" + window.location.host + "/";
	//=====================================================================
	$(window).load(function() {
		$('.page-loader > .loader').fadeOut();
		$('.page-loader').delay(350).fadeOut('slow');
		if($(window).width() < 600) { $("#co_collapse_button").trigger("click"); }
	});
	//=====================================================================
	$(document).on("shown.bs.tab", 'a[data-toggle="tab"]', function(e) {
		$('.feedlooper').each(function(){ if($(this).is(":visible") && !$(this).hasClass("loaded")){ load_contents($(this).attr("id")); $(this).addClass("loaded"); } });
	});
	//=====================================================================
	$(document).on('hide.bs.modal','#doc_editor', function () {
		tinyMCE.editors=[];
	});
	//=====================================================================
	$("#back-top").hide();
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 200) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
		$('#back-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
	//=====================================================================
	$(document).on("click", "#chat_hide", function(e) {
		if($('.chat_window_box').hasClass("chat_hide")){
			$('.chat_window_box').removeClass("chat_hide");
			$.ajax({url:$("#pre_config").attr("ses_link"),type:"POST",data:{'chat_minimized':'no'},success: function(data) {
			}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
			page_load();
		} else {
			$('.chat_window_box').addClass("chat_hide");
			$.ajax({url:$("#pre_config").attr("ses_link"),type:"POST",data:{'chat_minimized':'yes'},success: function(data) {
			}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
		}
	});
	//=====================================================================
	$(document).on("mouseenter", "fra", function(e) {
		$(this).addClass("animated swing");
		$(this).delay(900).queue(function(nxt) {
			$(this).removeClass('animated swing');
			nxt();
		});
	});
	//=====================================================================
	$(document).on("click", ".elementlink", function(e) {
    window.location = $(this).attr('href');
    return false;
  });
	//=====================================================================
	var waitForFinalEvent=function(){var b={};return function(c,d,a){a||(a="I am a banana!");b[a]&&clearTimeout(b[a]);b[a]=setTimeout(c,d)}}();
	var fullDateString = new Date();
	function isBreakpoint(alias) {
		return $('.device-' + alias).is(':visible');
	}
	function resizeGrid() {
		$('.grid-stack').each(function(){
			if (isBreakpoint('xs')) {
				//ddddddd
			} else if (isBreakpoint('sm')) {
				//grid.setGridWidth(3);
			} else if (isBreakpoint('md')) {
				//grid.setGridWidth(6);
			} else if (isBreakpoint('lg')) {
				//grid.setGridWidth(12);
			}
			$(this).children('.grid-stack-item').each(function(){
				var grid = $(this).closest('.grid-stack').data('gridstack');
				var gsi = $(this).find(".grid-stack-item-content");
	      var newHeight = Math.ceil((gsi[0].scrollHeight + grid.opts.verticalMargin) / (grid.cellHeight() + grid.opts.verticalMargin));
	      grid.resize($(this),null,newHeight);
			});
		});
	};
	$(window).resize(function () {
		waitForFinalEvent(function() {
			resizeGrid();
		}, 300, fullDateString.getTime());
	});
	$(document).on("resizestop dragstop", '.grid-stack', function(event, items) {
		this.serializedData = _.map($('.grid-stack > .grid-stack-item:visible'), function (el) {
			el = $(el);
			var node = el.data('_gridstack_node');
			//console.log(node);
			if(node) {
				return {
					x: node.x,
					y: node.y,
					width: node.width,
					height: node.height,
					id: el.data('id')
				};
			}
		}, this);
		var data = JSON.stringify(this.serializedData);
		//console.log(data);
		$.ajax({
			type: "POST",
			url: $(this).attr('link'),
			data: {'gridArray': JSON.stringify(this.serializedData, null, '    ')},
			success: function (msg) {
				result = msg;
				result = unescape(result);
				result = result.split("|");
				outcome = result[0];
				note = result[1];
				if(outcome=='true') {

				} else {
					new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+msg }).show();
				}
			}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); }
		});
	});
	//=====================================================================
	$(document).on("click", '.nav-hash > li [data-toggle="tab"]', function(e) {
		$.ajax({
			url: $('#pre_config').attr("link"),
			data: {nav:$(this).closest("ul").attr('hash_id'),item:(parseInt($(this).closest("li").index())+1)},
			type: 'POST',
			dataType: 'JSON',
			success: function(data){
				if(data.message) { new Noty({ text: data.message }).show(); }
			}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); }
		});
	});
	//=====================================================================
	$(document).on("click", ".nav-active-only", function(e) {
		$(this).toggleClass("open");
	});
	//=====================================================================  TABS PAGES FADE TEST
	$(document).on("click", ".nav-unselect > li.active [data-toggle=tab]", function(e) {
		if($(this).closest("ul").hasClass("title_nav")) {
			if($(this).is($(this).closest("ul").find("li:first > a")) == false) { $(this).closest("ul").find("li:first > a").tab('show'); }
	 	} else {
			$(this).parent().removeClass('active');
	  	$($(this).attr("href")).removeClass('active');
		}
	});
	//=====================================================================
	$(document).on("click", ".nav-multi > li [data-toggle=tab]", function(e) {
		$(".nav-multi > li.active").not($(this).parent()).removeClass('active');
	});
	//=====================================================================
	$(document).on("click", "li .nav-load[data-toggle=tab]", function(e) {
		if(!$($(this).attr("target")+" > "+$(this).attr("href")).length) {
			$($(this).attr("target")+" > .tab-pane.active").removeClass('active');
			$($(this).attr("target")).append('<div id="'+$(this).attr("href").replace('#','')+'" class="tab-pane fade active in"><div class="loader"></div></div>');
			var target = $(this).attr("target")+" > "+$(this).attr("href");
			$.ajax({url:$(this).attr("link"),type:"GET",dataType:"HTML",success: function(data) {
				$(target).html(data);
				page_load();
			}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
		}
	});
	//=====================================================================
	$(document).on("click", ".feedlooper_showmore", function(e) {
		$('#'+$(this).attr("target")).attr("page",parseInt($('#'+$(this).attr("target")).attr("page"))+1);
		load_contents($(this).attr("target"));
	});
	var load_running = false;
	function load_contents(target,placement='append',load_type=null){
		var t0 = performance.now();
		var params="",perpage="20";
		var curdate = new Date().toISOString().slice(0, 19).replace('T', ' ');
		load_running = true;
		$('#'+target+'_msgbox').html("<div class='loader'></div><a class='feedlooper_showmore' target='"+target+"'>Show More</a>");
		$('#'+target+'_msgbox > .feedlooper_showmore').hide();
		if(!params) {
			$(".feedfilter[target*=\""+target+"\"]").each(function(){
				if($(this).val() != null) {
					params = params+$(this).val()+",";
					if($(this).val().indexOf("perpage") !== -1) { var split = $(this).val().split("|"); if(parseInt(split[1]) > 0) { perpage = split[1]; } }
				}
			});
		}
		if(load_type=="update") { var update_date = $('#'+target).attr("update_date"); } else { var update_date = ""; $('#'+target).attr("update_date",curdate); }
		$.ajax({
		  url: $('#'+target).attr("link"),
		  data: {'page':$('#'+target).attr("page"),'params': params,'perpage':perpage,'update_date':update_date},
		  type: 'POST',
			dataType: 'JSON',
		  success: function(data){
				//console.log(data);
				var aj_data=JSON.parse(data.data);
				var t1 = performance.now(); console.log("Ajax for "+target+" took " + Math.floor((t1 - t0)) + " ms.");
				if(aj_data.length === 0 && load_type==null){
					$('#'+target+'_msgbox').html('<div class="p-t-30 p-b-30 b-s-0 b-dashed b-c-lightgray b-r-2 f-c-lightgray text-center f-s-20"><i class="fa fa-file-o"></i> NO CONTENT FOUND</div>');
					load_running = false; return;
				}
				if(data.status == "success") {
					$.each(aj_data, function(key, value) {
						if($("#"+target+" [r_id='"+value.r_id+"']").length) {
							$("#"+target+" [r_id='"+value.r_id+"']").replaceWith(value.html);
						} else if(placement=='prepend') {
							$("#"+target).prepend(value.html);
						} else {
							$("#"+target).append(value.html);
						}
					});
				}
				//if(data.message) { new Noty({ text: data.message }).show(); }
				if(data.message) { 	$("#"+target).prepend(data.message); }
				$('#'+target+'_msgbox').append(data.js);
				if(perpage > "0" && $('#'+target+'').children().length < perpage){
					$('#'+target+'_msgbox > .feedlooper_showmore').hide();
				} else { $('#'+target+'_msgbox > .feedlooper_showmore').show(); }

				$('.page-loader > .loader').fadeOut();
				$('.page-loader').delay(350).fadeOut('slow');
				if(typeof resizeGrid == 'function') { resizeGrid(); }
				//if(totalReloadTime > 0 && typeof updateNotifications == 'function') { updateNotifications(); }
				$('#'+target+'_msgbox > .loader').hide();
				page_load();
				load_running = false;
		  },
			error: function (jqXhr, status, error) {
				$('#'+target+'_msgbox').html('<div class="p-t-30 p-b-30 b-s-0 b-dashed b-c-lightgray b-r-2 f-c-lightgray text-center f-s-20"><i class="fa fa-file-o"></i> NO CONTENT FOUND</div>');
				console.log(status+':'+error+':'+jqXhr.responseText);
				load_running = false; return;
			}
		});
	}
	//=====================================================================
	$(document).on("keyup", ".numbersOnly", function(e) {
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	//=====================================================================
	$(document).on("keyup", ".feedlooper_search input", function(e) {
		this.value = this.value.replace(/[^a-z A-Z0-9-_\.\/\:\\@]/g,'');
	});
	//=====================================================================
	$(document).on("change", ".inputfile", function(e) {
		var $input	 = $(this),
				$label	 = $(this).next( 'label' ),
				labelVal = $label.html();
		var fileName = '';
		if( this.files && this.files.length > 1 ){
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
		} else if( e.target.value ){
			fileName = e.target.value.split( '\\' ).pop();
		}
		if( fileName ) {
			$label.find( 'span' ).html( fileName );
		} else {
			$label.html( labelVal );
		}
	});
	//=====================================================================
	$(document).on("select2:select", ".selectgroup .select2child", function(e) {
		var thispar = $(this);
		$(e.currentTarget).find("option:selected").each(function (index, el, list) {
			var element = el;
			element.setAttribute("parent", "#"+thispar.attr("id"));
			var $element = $(element);
			$element.detach();
			thispar.closest(".selectgroup").find(".select2parent").append($element);
			thispar.trigger("change");
			thispar.closest(".selectgroup").find(".select2parent").trigger("change");
			var thisval = $(element).text();
			setTimeout(() => {
				thispar.closest(".selectgroup").find(".select2-selection").find("[title=\""+thisval+"\"]").addClass("animated rubberBand");
			}, 200);
    });

	});
	$(document).on("select2:unselect", ".selectgroup .select2parent", function(e) {
		$(this).data('select2').options.set('disabled', true);
		setTimeout(() => {
			$(this).data('select2').options.set('disabled', false);
		}, 0);
		var element = e.params.data.element;
		var parent = element.getAttribute("parent");
		element.setAttribute("parent", "");
		var $element = $(element);
		$element.detach();
		$(parent).append($element);
		$(this).trigger("change");
		$(parent).trigger("change");
	});
	$(document).on("select2:opening", ".selectgroup .select2parent", function(e) {
		e.stopPropagation();
		e.preventDefault();
	});
	$(document).on("click", ".selectgroup", function(e) {
		if($(this).find('.dropdown-menu').is(":hidden") && !$(this).find(".select2parent").is(":disabled")){
			$(this).find('.dropdown-menu').removeClass('hide_dropdown');
			$(this).addClass('open');
		}
	});
	$(document).on('mousedown', function (e) {
		$(".tooltip").remove();
		var container1 = $(".select2-results__options");
		var container2 = $(".date-picker-wrapper");
		$(".selectgroup.open").each(function(){
			if(!$(this).is(e.target) && $(this).has(e.target).length === 0 && !container1.is(e.target) && container1.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0) {
				if(!$(this).find('.dropdown-menu').is(":hidden")){
					$(this).find('.dropdown-menu').addClass('hide_dropdown');
					setTimeout(() => {
						$(this).removeClass('open');
					}, 500);
				}
			}
		});
	});
	//=====================================================================
	$(document).on("datepicker-close", ".selectgroup .datepicker", function(e) {
		var thisval = e.currentTarget.value;
		if(thisval.length < 1) { return; }
    var newOption = new Option(thisval, "daterange|"+thisval, true, true);
		newOption.setAttribute("parent", "#"+$(this).attr("id"));
		$(this).closest(".selectgroup").find(".select2parent").find('[parent="#'+$(this).attr("id")+'"]').remove();
		$(this).closest(".selectgroup").find(".select2parent").append(newOption).trigger('change');
		setTimeout(() => {
			$(this).closest(".selectgroup").find(".select2-selection").find("[title=\""+thisval+"\"]").addClass("animated rubberBand");
		}, 200);
		$(this).val("");
	});
	//=====================================================================
	$(document).on("click", ".selectgroup .addsearch", function(e) {
    var thisval = $(this).closest(".input-group").find("input").val().replace(/,/g, "");
		if(thisval.length < 1) { return; }
		var newOption = new Option(thisval, "search|"+thisval, true, true);
		newOption.setAttribute("parent", "#"+$(this).closest(".input-group").find("input").attr("id"));
		$(this).closest(".selectgroup").find(".select2parent").find('[parent="#'+$(this).closest(".input-group").find("input").attr("id")+'"]').remove();
		$(this).closest(".selectgroup").find(".select2parent").append(newOption).trigger('change');
		setTimeout(() => {
			$(this).closest(".selectgroup").find(".select2-selection").find("[title=\""+thisval+"\"]").addClass("animated rubberBand");
		}, 200);
		$(this).closest(".input-group").find("input").val("");
	});
	//=====================================================================
	var feedfilter_array = new Object();
	$(document).on("change", ".feedfilter", function(e) {
		var target = $(this).attr("target").split("|");
		for(var i = 0; i < target.length; i++) {
			feedfilter_array[target[i]] = {'name':target[i]};
		}
	});
	var filterIncStart = setInterval(function () {
		var firstKey = Object.keys(feedfilter_array)[0];
		if(load_running == false && firstKey) {
			$('#'+feedfilter_array[firstKey].name).html("");
			$('#'+feedfilter_array[firstKey].name).attr("page",1);
			load_contents(feedfilter_array[firstKey].name);
			delete feedfilter_array[firstKey];
		}
	}, 1000);
	//=====================================================================
	$(document).on("click", ".do_ajax", function(e) {
		if($(this).attr("da_data") == "form") { da_ele = $(this).closest("form"); da_data = new FormData(da_ele[0]);
		} else { da_ele = $(this); da_data = da_ele.data(); }
		if(da_data.hasOwnProperty('toggle')){ delete da_data.toggle; } if(da_data.hasOwnProperty('bs.tab')){ delete da_data["bs.tab"]; }
		if(da_ele.attr('requestRunning') == true) { return; } else { da_ele.attr('requestRunning', true); }
		if(da_ele.attr("da_verify")) { var da_verify = da_ele.attr("da_verify"); } else { var da_verify = 0; }
		if(da_ele.attr("da_link")) { var da_link = da_ele.attr("da_link"); } else { console.log("missing link"); da_ele.attr('requestRunning',false); return; }
		if(da_ele.attr("da_type")) { var da_type = da_ele.attr("da_type"); } else { var da_type = "message"; }
		if(da_ele.attr("da_target")) { var da_target = da_ele.attr("da_target"); } else { var da_target = da_ele.attr("id"); }
		if(da_ele.attr("da_append")) { var da_append = da_ele.attr("da_append"); } else { var da_append = "prepend"; }
		if(da_type=="load"){ $("#"+da_target).html("<div class='loader'></div>"); }
		if(da_target=="doc_editor_content"){ $("#doc_editor").modal('show'); } else if(da_target=="main_modal_content"){ $("#main_modal").modal('show'); }
		if(da_verify > 0) {
			if(da_ele.attr("da_message")) { var da_message = da_ele.attr("da_message"); } else { var da_message = "Are you sure you want to continue?"; }
			da_message = "<strong class='f-s-14'>"+da_message+"</strong>";
			if(da_verify == "2") { da_message = da_message+'<div class="m-b-10">Please input the word "<strong>confirm</strong>" to continue.</div><input class="form-control" id="notyconfirminp" type="text"><div id="notyconfirmerr"></div>'; }
			var n = new Noty({ timeout: false, modal: true, type: 'default', layout: 'center', closeWith: ['button'], text: da_message,
				buttons: [
					Noty.button('YES', 'btn btn-themed m-r-sm', function () {
						var opt_error = "no";
						if(da_verify == "2" && $("#notyconfirminp").val().toLowerCase() != "confirm") {
							opt_error = "yes";
							$("#notyconfirmerr").html('<div class="alert alert-danger m-t-xs">Please input "<strong>confirm</strong>" to coninue.</div>');
						}
						if(opt_error == "no") {
							if($(this).attr("da_data") == "form") {
								$.ajax({url:da_link,data:da_data,type:"POST",dataType:"JSON",cache:false,contentType: false,processData:false,success: function(data) {
									do_ajax_data(da_ele,da_type,da_target,da_append,data);
								}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
							} else {
								$.ajax({url:da_link,data:da_data,type:"POST",dataType:"JSON",success: function(data) {
									do_ajax_data(da_ele,da_type,da_target,da_append,data);
								}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
							}
							n.close(); da_ele.attr('requestRunning',false); return;
						}
					}, {id: 'button1', 'data-status': 'ok'}),
					Noty.button('NO', 'btn btn-default', function () {
						n.close(); da_ele.attr('requestRunning',false); return;
					})
				]
			}).show();
		} else {
			if($(this).attr("da_data") == "form") {
				$.ajax({url:da_link,data:da_data,type:"POST",dataType:"JSON",cache:false,contentType: false,processData:false,success: function(data) {
					do_ajax_data(da_ele,da_type,da_target,da_append,data);
				}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
			} else {
				$.ajax({url:da_link,data:da_data,type:"POST",dataType:"JSON",success: function(data) {
					do_ajax_data(da_ele,da_type,da_target,da_append,data);
				}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
			}
			da_ele.attr('requestRunning',false); return;
		}
	});
	function do_ajax_data(da_ele,da_type,da_target,da_append,data) {
		//console.log(data);
		if(data.status == "success") {
			if(da_type=="reload") {
				if(da_target=="location"){
					location.reload();
				} else if($('#'+da_target).hasClass("feedlooper")) {
					$('#'+da_target).html(""); $('#'+da_target).attr("page",1); load_contents(da_target);
				} else if($('#'+da_target).hasClass("nav-load")) {
					$($('#'+da_target).attr("target")+" > "+$('#'+da_target).attr("href")).remove();
					$($('#'+da_target).attr("target")+" > "+$('#'+da_target).attr("href")).click();
				} else {
					$('#'+da_target).html(""); load_contents(da_target);
				}
			} else if(da_type=="load") {
				$('#'+da_target).html(""); $('#'+da_target).html(data.data);
				if(da_target=="chat_window"){ $('.chat_window_box').removeClass("chat_hide"); }
				page_load();
			} else if(da_type=="update") {
				load_contents(da_target,da_append,"update");
			}
			da_ele.closest('.modal').modal('hide');
		}
		if(data.message) { new Noty({ text: data.message }).show(); }
	}
	//=====================================================================







	page_load();
	function page_load() {
		$('[title]').tooltip({ container:'body', placement:'bottom' });
		//=====================================================================
		$('.scrollbars').overlayScrollbars({ });
	  $('.scrollbars-x').overlayScrollbars({ overflowBehavior : { x : "scroll", y : "hidden" } });
	  $('.scrollbars-y').overlayScrollbars({ overflowBehavior : { x : "hidden", y : "scroll" } });
		//=====================================================================
		$(".profile_img").initial();
		//=====================================================================
		$(".select2").select2();
		//=====================================================================
		$('.cropme').simpleCropper();
		//=====================================================================
		autosize(document.querySelectorAll('textarea'));
	  //=====================================================================
	  $(".colorpicker").spectrum({ preferredFormat: "hex" });
		//=====================================================================
		$(".make_sortable").sortable({
			scroll: false,
			items: ".sortable",
			placeholder: "sortable_placeholder",
			handle: '.hand',
			zIndex: 500,
			forcePlaceholderSize: true,
			update: function(event, ui) {
				$.post($(this).attr('link'), { type: "orderPages", pages: $(this).sortable('serialize') } );
			}
		});
		//=====================================================================
		$(".make_grid_sortable").sortable({
			scroll: false,
			items: ".mgs_item",
	    placeholder: "sortable_grid_placeholder",
	    cancel: ".disable-sort-item",
			sort: function(event, ui) {
				var $target = $(event.target);
				if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
					var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) / 2);
					ui.helper.css({'top' : top + 'px'});
				}
	    },
	    start: function(event, ui){
	      ui.placeholder.height(ui.item.height());
	      ui.placeholder.width(ui.item.width());
	    },
			update: function(event, ui) {
				$.post($(this).attr('link'), { type: "orderPages", pages: $(this).sortable('serialize') } );
			}
		});
		//=====================================================================
	  tinymce.init({ selector: '.tinymce', skin : "custom", menubar: false,
	    plugins: [
	      'autoresize advlist autolink lists link image charmap print preview hr anchor pagebreak',
	      'searchreplace wordcount visualblocks visualchars fullscreen',
	      'insertdatetime media save table contextmenu directionality',
	      'emoticons template paste textcolor colorpicker textpattern imagetools codesample'
	    ],
	    toolbar: 'undo redo | fontselect | fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat'
	  });
	  $.fn.modal.Constructor.prototype.enforceFocus = function() {};
		//=====================================================================
		$('.feedlooper').each(function(){ if($(this).is(":visible") && !$(this).hasClass("loaded")){ load_contents($(this).attr("id")); $(this).addClass("loaded"); } });
		//=====================================================================
		$('li.active a.nav-load').each(function(){
			if(!$($(this).attr("target")+" > "+$(this).attr("href")).length) {
				$($(this).attr("target")+" > .tab-pane.active").removeClass('active');
				$($(this).attr("target")).append('<div id="'+$(this).attr("href").replace('#','')+'" class="tab-pane fade active in"><div class="loader"></div></div>');
				var target = $(this).attr("target")+" > "+$(this).attr("href");
				$.ajax({url:$(this).attr("link"),type:"GET",dataType:"HTML",success: function(data) {
					$(target).html(data);
					page_load();
				}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
			}
		});
		//=====================================================================
		if($('#pre_config').attr("get_link")) {
			$.ajax({url:$('#pre_config').attr("get_link"),type:"GET",dataType:"JSON",success: function(data) {
				if(data.status == "success") {
					var hashs=JSON.parse(data.data);
					$.each(hashs, function(index, item) {
						if(item.length) {
							var target = $("ul.nav-hash[hash_id='"+index+"'] li:eq("+(item-1)+") > a[data-toggle=tab]");
							if(!target.parent().hasClass("active")) {
								if(target.hasClass("nav-load")) {
									if($(target).closest("ul").hasClass("nav-multi")) {
										$(".nav-multi > li.active").not($(target).parent()).removeClass('active');
									} else {
										$(target).closest("ul").find("li.active").removeClass("active");
									}
									if(!$(target).closest("li").hasClass("active")) {
										$(target).closest("li").addClass("active");
									}
									if(!$($(target).attr("target")+" > "+$(target).attr("href")).length) {
										$($(target).attr("target")+" > .tab-pane.active").removeClass('active');
										$($(target).attr("target")).append('<div id="'+$(target).attr("href").replace('#','')+'" class="tab-pane fade active in"><div class="loader"></div></div>');
										$.ajax({
										  url: $(target).attr("link"),
											type : 'GET',
							      	dataType : 'html',
										  success: function(data){
												$($(target).attr("target")+" > "+$(target).attr("href")).html(data);
												$($(target).attr("target")+" > "+$(target).attr("href")+' .feedlooper').each(function(){
													if($(this).is(":visible") && !$(this).hasClass("loaded")){ load_contents($(this).attr("id")); $(this).addClass("loaded"); }
												});
										  }, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); }
										});
									}
								} else {
									target.tab('show');
								}
							}
						}
					});
				}
			}, error: function (jqXhr, status, error) { console.log(status+':'+error+':'+jqXhr.responseText); } });
		}
		//=====================================================================
	}
	//=====================================================================
	var totalReloadTime = 0, idleMax = 60, idleTime = 0;
	var idleInterval = setInterval(timerIncrement, 60000);
	$(document).on("mousemove mousedown keydown", "body", function(e) {
		idleTime = 0;
	});
	function timerIncrement() {
		totalReloadTime = totalReloadTime + 1;
		//console.log("tic");

		idleTime = idleTime + 1;
		if(idleTime >= idleMax) {
			$("#logout").click();
		}

		$('.feedlooper').each(function(){
			if($(this).is("[r_max]") && $(this).hasClass("loaded")){
				console.log('doing inc: '+$(this).attr("id"));
				if(parseInt($(this).attr("r_count")) > 0) { $(this).attr("r_count",parseInt($(this).attr("r_count"))+1); } else { $(this).attr("r_count",1); }
				if(parseInt($(this).attr("r_max")) <= parseInt($(this).attr("r_count"))) {
					$(this).attr("r_count",0);
					if($(this).attr("da_append")) { var da_append = $(this).attr("da_append"); } else { var da_append = "prepend"; }
					load_contents($(this).attr("id"),da_append,"update");
				}
			}
		});

	}

});
