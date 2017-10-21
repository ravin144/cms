$(function(){
	var $upload_file=$('.upload_file').find('input'),index=0;
	$upload_file.fileupload({
		dataType:'json',
		limitMultiFileUploads : 1,
		maxFileSize: 1000000,
		start:function(e,data){
			$upload_file.prop('disabled',true);
			$('.upload_file').addClass('loading').find('span').html('Loading'+'0%');
			$('.upload_group').find('.Validform_checktip').removeClass('Validform_wrong');
		},
		submit:function(e,data){
			var maxnum=$upload_file.attr('maxnum');
			var lastLen=maxnum-$('.upload_list').find('.upload_box').length;
			if(maxnum<=1 && data.originalFiles.length>1){
				if(index==0){
					alert('您只能选择一个文件');index++;
				}else if(index<data.originalFiles.length){
					index++
				}else{
					index=0;
				}
				return false;
			}else{
				if(maxnum>1){
					if(data.originalFiles.length>maxnum || data.originalFiles.length>lastLen){
						if(data.originalFiles.length>lastLen){
							maxnum=lastLen;
						}
						if(index==0){
							alert('最多还能选择并上传'+maxnum+'个文件');index++;
						}else if(index<data.originalFiles.length){
							index++
						}else{
							index=0;
						}
						return false;
					}
				}
			}
			
		},
		done:function(e,data){
			if(data.result.status==true){
				var maxnum=$upload_file.attr('maxnum');
				if(maxnum==1){
					$('.upload_list .img_box').remove();
				}
				var aspectRatio='';//截取框长宽比例 width/height
				if($upload_file.attr('aspectRatio')){
					aspectRatio=$upload_file.attr('aspectRatio');
				}
				var cut='<span class="crop">Cut</span>';
				if($upload_file.attr('disabled_crop')){
					cut='';
				}
				if($upload_file.attr('name')=='file'){
					$('.upload_list').append('<div class="file_box upload_box">'+
												'<p>'+
												'<a href="'+data.result.src+'" target="_blank">'+data.result.name+'</a>'+
												'&nbsp;&nbsp;&nbsp;<span class="delete">×</span>'+
												'</p>'+
												'<input type="hidden" name="'+$upload_file.attr('filename')+'" value="'+data.result.src+'">'+
												'<input type="hidden" name="name_'+$upload_file.attr('filename')+'" value="'+data.result.name+'">'+
											'</div>');
				}else{
					$('.upload_list').append('<div class="img_box upload_box">'+
						  		      		    '<p class="hd">'+cut+'<span class="delete">×</span></p>'+
						      		      		'<img src="'+data.result.src+'" aspectRatio="'+aspectRatio+'">'+
							      		      	'<input type="hidden" name="'+$upload_file.attr('filename')+'" value="'+data.result.src+'"/>'+
							      		      	'<input type="hidden" name="name_'+$upload_file.attr('filename')+'" value="'+data.result.name+'">'+
											'</div>');
				}
			}else{
				alert(data.result.msg)
			}
		},
		progressall:function(e,data){
			var progress = parseInt(data.loaded / data.total * 100, 10);
			if(progress<100){
				$('.upload_file').find('span').html('Loading'+progress+'%');
			}else{
				$upload_file.prop('disabled',false);
				$('.upload_file').removeClass('loading').find('span').html('Upload');
			}
		}
	})
	//删除图片
	$('body').on('click','.upload_list .upload_box .delete',function(){
		$(this).parents('.upload_box').remove();
	})
	//图片裁切
	var jcrop_api,data={x:0,y:0,x2:0,y2:0,w:0,h:0,width:0,height:0};
	var $img_box;
	function align_center($obj){
		var left=($(window).width()-$obj.width())/2,top=($(window).height()-$obj.height())/2;
		$obj.css({'left':left,'top':top});
	}
	function jcrop_img(){
		$('body').css('overflow','hidden');
		$('#crop_img .content img').Jcrop({
			aspectRatio:parseFloat($img_box.find('img').attr('aspectRatio')),//裁切长宽比例
			onChange:function(e){
				e.width=data.width;e.height=data.height;e.img_src=data.img_src;e.crop_src=data.crop_src;
				data=e;
				var $size_input=$('#crop_img .foot_input');
				$size_input.find('input[name=width]').val(e.w);
				$size_input.find('input[name=height]').val(e.h);
			}
		},function(){
			jcrop_api=this;
			data.w=0;data.h=0;
			$('#crop_img .foot .cancle').hide();
			$('#crop_img .jcrop-holder').css('margin-left',-($('#crop_img .jcrop-holder').width()-$('#crop_img').width())/2)
			data.width=$('#crop_img .content img').width();
			data.height=$('#crop_img .content img').height();
		});
	}
	function hide_jcrop_box(){
		$('body').css('overflow','auto');
		jcrop_api.destroy();
		$('#crop_img,.crop_screen').fadeOut();
	}
	$('.upload_list').on('click','.img_box .crop',function(){
		$img_box=$(this).parents('.img_box');
		var img_src=$(this).parents('.img_box').find('img').attr('src');
		$('#crop_img .content .crop_box img').attr('src',img_src).css({'width':'auto','height':'auto'});
		align_center($('#crop_img'));
		$(window).resize(function(){
			align_center($('#crop_img'));
		});
		data.img_src=img_src;
		$('.crop_screen,#crop_img').fadeIn();
		jcrop_img();
	})
	$('#crop_img .close,.crop_screen').click(function(){
		hide_jcrop_box()
		return false;
	})
	//裁切
	var croping=false;
	$('#crop_img .foot .crop').click(function(){
		var $this=$(this);
		if(data && !$this.hasClass('loading') && data.w>0 && data.h>0){
			if(data.w!=$('#crop_img .content img').width() || data.h!=$('#crop_img .content img').height()){
				$this.addClass('loading').html('Loading...').prop('disabled',true);
  				jcrop_api.disable();
  				croping=true;
				$.ajax({
					type:'post',
					data:data,
					url:$upload_file.attr('cutimg-url'),
					dataType:'json',
					success:function(result){
						croping=false;
						$this.removeClass('loading').html('Cut').prop('disabled',false);
						$this.parent().find('.cancle').show();
						if(result.status){
							data.crop_src=result.img_src;
							$('#crop_img .content img').attr('src',result.img_src).css({'width':'auto','height':'auto'});
							jcrop_api.destroy();
							jcrop_img();
						}
					},
					error:function(){
						alert('裁切失败，请重试');
						jcrop_api.destroy();
						jcrop_img();
						$this.removeClass('loading').html('Cut').prop('disabled',false);
					}
				})
			}
		}else{
			alert('请先选择裁切区');
		}
		return false;
	})
	//取消裁切
	$('#crop_img .foot .cancle').click(function(){
		$(this).hide();
		data.crop_src=data.img_src;
		$('#crop_img .content img').attr('src',data.img_src).css({'width':'auto','height':'auto'});
		jcrop_api.destroy();
		jcrop_img();
		return false;
	})
	//确定裁切
	$('#crop_img .foot .ok').click(function(){
		if(croping){
			alert('正在裁切图片，请稍后');
			return false;
		}
		$img_box.find('img').attr('src',data.crop_src);
		$img_box.find('input').val(data.crop_src);
		hide_jcrop_box()
		return false;
	})
})