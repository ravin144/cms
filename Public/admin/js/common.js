var valid_form_data={};
function ajaxResultDo(result,locationHref,locationTitle){
	if(!result.status){
		$('.modal-alert .alert-status.loading').removeClass('show').addClass('hide');
		$('.modal-alert .alert-status.error').addClass('show').find('.alert-msg').html(result.msg);
	}else{
		$('.modal-alert .alert-status.loading').removeClass('show').addClass('hide');
		$('.modal-alert .alert-status.success').addClass('show').find('.alert-msg').html(result.msg);
		if(locationHref && locationTitle && locationHref!='' && locationTitle!=''){
			$('.modal-alert .modal-footer .callback-location').html(locationTitle).attr('href',locationHref).addClass('show-btn');
		}
		var time_out = 4000;
		if(locationHref == 'reload'){
			$('.modal-alert').on('hidden.bs.modal',function(e){
				window.location.reload();
			})
			time_out = 2500;
		}
		setTimeout(function(){
			$('.modal-alert').modal('hide');
		},time_out)
	}
}
$(function(){
	// 全选
	$('.allcheck').change(function(){
		$('tr td:first-child input[type=checkbox]').prop('checked', $(this).is(":checked"));
	});
	//bootstrap 工具提示
	if($('[data-toggle="tooltip"]').length){
		$('[data-toggle="tooltip"]').tooltip()
	}
	//时间选择插件
	// if($('.date-select').length){
	// 	$('.date-select').datetimepicker({
	// 		language:  'zh-CN',
	// 		format: 'yyyy-mm-dd',
	// 		autoclose:true,
	// 		minView:2
	// 	});
	// }
	if($('.date-select').length){
		$('.date-select').datetimepicker({
			language:  'zh-CN',
			format: 'yyyy-mm-dd hh:ii:ss',
			autoclose:true,
			minView:2
		});
	}
	//提示框弹出后的loading
	$('.modal-alert').on('show.bs.modal', function (e) {
		$('.modal-alert .alert-status').removeClass('show hide');
		$('.modal-alert .modal-footer .callback-location').removeClass('show-btn').html('').attr('href','');
	})
	$('.modal-alert').on('shown.bs.modal', function (e) {
		$('.modal-alert .alert-status.loading').addClass('show');
	})
	//关闭提示匡时候如果正在执行ajax则不允许关闭
	$('.modal-alert').on('hide.bs.modal', function (e) {
		if(isLoading){
			alert('数据正在提交，请稍后...');
			return false;
		}
	})
	/**
	 * 在需要ajax  post 提交的地方加上class:ajax-post,需要有form表单
	 * data-url:请求地址
	 * data-location:成功后跳转的链接，三种形式
	 * 			go-back:返回上一页，并刷新，
	 * 			reload:刷新当前页
	 * 			url地址:跳转到该地址
	 */
	var isLoading=false;
	if($('.ajax-post').length){
		$('.ajax-post').each(function(index,value){
			var $this=$(value),
				url=$this.attr('data-url')?$this.attr('data-url'):window.location,
				locationHref=$this.attr('data-location')?$this.attr('data-location'):'',
				locationTitle=$this.attr('data-location-title')?$this.attr('data-location-title'):'';
			var form=$this.parents('form');
			function submitForm(){
				$('.modal-alert').modal('show');
				$this.button('loading');
				isLoading=true;
				var data=form.serialize();
				$.ajax({
					type:'post',
					url:url,
					data:data,
					dataType:'json',
					success:function(result){
						isLoading=false;
						$this.button('reset');
						ajaxResultDo(result,locationHref,locationTitle);
					},
					error:function(){
						isLoading=false;
						$this.button('reset');
						var result = {};
						result.status = false;
						result.msg = '请求失败，请确认地址是否存在，请重试';
						locationHref='';
						locationTitle='';
						ajaxResultDo(result,locationHref,locationTitle);
					}
				})
				return false;
			}
			if($this.hasClass('is-validform')){
				var defaultData={
					btnSubmit:".ajax-post.is-validform",
					//tiptype:1,
					btnSubmit:$this,
					callback:submitForm,
					tiptype:function(msg,o,cssctl){
						o.obj.popover('destroy');
						if(o.type==1 || o.type==3){
							if(o.type==1){
								o.obj.popover({
									content:'<span class="glyphicon glyphicon-info-sign" style="color:#a94442"></span>&nbsp;&nbsp;正在检测数据，请稍后',
									html:true,
									placement:'right',
									trigger:'focus'
								})
							}else{
								o.obj.popover({
									content:'<span class="glyphicon glyphicon-info-sign" style="color:#a94442"></span>&nbsp;&nbsp;'+msg,
									html:true,
									placement:'right',
									trigger:'focus'
								})
							}
							o.obj.popover('show');
						}
					}
				}
				var data=$.extend({},valid_form_data,defaultData);
				form.Validform(data);
			}else{
				$this.click(function(){
					submitForm();
					return false;
				})
			}
		})
	}
	/**
	 * 在需要ajax  get 提交的地方加上class:ajax-get
	 * data-url:请求地址
	 * data-location:成功后跳转的链接，三种形式
	 * 			go-back:返回上一页，并刷新，
	 * 			reload:刷新当前页
	 * 			url地址:跳转到该地址
	 * data-confirm:确认提示信息，如果有则弹出确认框，没有则直接执行ajax
	 */
	$('.ajax-get').click(function(){
		var $this=$(this),
			url=$this.attr('data-url')?$this.attr('data-url'):window.location,
			locationHref=$this.attr('data-location')?$this.attr('data-location'):'',
			locationTitle=$this.attr('data-location-title')?$this.attr('data-location-title'):'';
		if($this.attr('data-confirm')){
			var a=confirm($this.attr('data-confirm'));
			if(!a){
				return false;
			}
		}
		$('.modal-alert').modal('show');
		isLoading=true;
		$.ajax({
			type:'get',
			url:url,
			data:{},
			dataType:'json',
			success:function(result){
				isLoading=false;
				ajaxResultDo(result,locationHref,locationTitle);
			},
			error:function(){
				isLoading=false;
				$this.button('reset');
				var result = {};
				result.status = false;
				result.msg = '请求失败，请确认地址是否存在，请重试';
				locationHref='';
				locationTitle='';
				ajaxResultDo(result,locationHref,locationTitle);
			}
		})
		return false;
	})
    $('body').on('click','.btn.add',function(){
		var $parent=$(this).parents('.form-group');
		var id=$(this).attr('data-id');
		var data={};
		var html=template(id,data);
		$parent.after(html);
		return false;
	})
	$('body').on('click','.btn.minus',function(){
		$(this).parents('.form-group').remove();
		return false;
	})
})
/**
*$addObj 添加按钮
*$delObj 删除按钮
*startSelectName 原始数据select name名称
*endSelectName   要插入的数据select name名称
**/
function selectMany($addObj,$delObj,startSelectName,endSelectName){
	var countryId=[];
	$('select[name='+endSelectName+'] option').each(function(i,v){
		countryId.push($(v).attr('value'));
	})
	if(!$('input[name='+endSelectName+']').length){
		$('select[name='+endSelectName+']').after('<input type="hidden" name="'+endSelectName+'" value="'+countryId+'">');
		$('input[name='+endSelectName+']').val(countryId);
	}
	//新增
	$addObj.click(function(){
		var selectVal=$('select[name='+startSelectName+']').val();
		var html='';
		$.each(selectVal,function(i,v){
			if($('select[name='+endSelectName+'] option').length){
				var isExist=false;
				$('select[name='+endSelectName+'] option').each(function(index,value){
					if($(value).attr('value')==v){
						isExist=true;
					}
				})
				if(!isExist){
					html+=getOption(v,startSelectName);
				}
			}else{
				html+=getOption(v,startSelectName);
			}
		})
		countryId=[];
		$('select[name='+endSelectName+']').append(html).find('option').prop('selected',false).each(function(i,v){
			countryId.push($(v).attr('value'));
		})

		$('input[name='+endSelectName+']').val(countryId)
		return false;
	})
	//删除
	$delObj.click(function(){
		var selectVal=$('select[name='+endSelectName+']').val();
		$('select[name='+endSelectName+'] option').each(function(i,v){
			if(in_array($(v).attr('value'),selectVal)){
				$(v).remove();
			}
		})
		var countryId=[];
		$('select[name='+endSelectName+']').find('option').each(function(i,v){
			countryId.push($(v).attr('value'));
		})
		$('input[name='+endSelectName+']').val(countryId)
		return false;
	})
	function getOption(value,selectName){
		var html='';
		$('select[name='+selectName+'] option').each(function(i,v){
			if($(v).attr('value')==value){
				if(parseInt(delitery_id)==2){
					html='<option value='+value+'>'+$(v).html()+'</option>';
				}else{
					html='<option value='+value+'>'+country_name+'：'+$(v).html()+'</option>';
				}
			}
		})
		return html;
	}
}
//是否存在指定函数
function isExitsFunction(funcName) {
    try {
        if (typeof(eval(funcName)) == "function") {
            return true;
        }
    } catch(e) {}
    return false;
}
//判断数组种是否存在指定的值
function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
function getCountry($countryObj,$cityObj,countrySelect,citySelect,countryName,cityName){
	var countrySelectid=countrySelect?countrySelect:false;
	var countryName=countryName?countryName:false;
	var cityName=cityName?cityName:false;
	var citySelectid=citySelect?citySelect:false;
	$.getJSON(get_country_state_url,function(data){
		var countryHtml='';
		var citys={};
		$.each(data,function(i,v){
			var selected="";
			if(countrySelectid){
				if(v.country.id==countrySelectid){
					citys=v.citys;
					selected="selected";
				}
			}else if(countryName){
				if(v.country.c_name==countryName){
					citys=v.citys;
					selected="selected";
				}
			}else{
				if(i==0){
					citys=v.citys;
				}
			}
			countryHtml+='<option value="'+v.country.id+'" '+selected+'>'+v.country.c_name+'</option>';
		})
		if(!citys){
			citys=data[0].citys;
		}
		$countryObj.html(countryHtml);
		var stateHtml='';
		$.each(citys,function(i,v){
			if(v){
				var selected='';
				if(citySelect){
					if(v.id==citySelect){
						selected="selected";
					}
				}else if(cityName){
					if(v.c_name==cityName){
						selected="selected";
					}
				}
				stateHtml+='<option value="'+v.id+'" '+selected+'>'+v.c_name+'</option>'
			}
		})
		if(stateHtml!=''){
			$cityObj.html(stateHtml);
		}else{
			$cityObj.html('<option value="Null">Null</option>');
		}
		$countryObj.change(function(){
			var val=$(this).val();
			var stateHtml='';
			$.each(data,function(i,v){
				if(v.country.id==val){
					$.each(v.citys,function(ii,vv){
						if(vv){
							stateHtml+='<option value="'+vv.id+'">'+vv.c_name+'</option>';
						}
					})
				}
			})
			if(stateHtml!=''){
				$cityObj.html(stateHtml);
			}else{
				$cityObj.html('<option value="Null">Null</option>');
			}
		})
	});
}
