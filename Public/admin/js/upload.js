/*引用图片或文件上传前要引入一下文件
jquery
<!-- 编辑器配置文件 -->
<script type="text/javascript" src="__PUBLIC__/statics/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="__PUBLIC__/statics/ueditor/editor_api.js"></script>
//并加入以下代码
<script type="text/plain" id="j_ueditorupload" style="height:5px;display:none;" ></script>

//按钮代码:图片上传，可以再button的data-name属性设置图片列表的name名，data-max-num属性设置最多上传的张数
<div class="form-group">
  	<label>图片列表</label>
  	<div class="uploadimg">
  		<div class="clear"></div>
  		<button type="submit" class="btn btn-info upload-img-btn" data-name="imglist" data-max-num="5" needcut="needcut"<!--需要裁切--> aspectRatio="1"<!--裁切宽高比例限制-->>上传</button>
  	</div>
</div>
//按钮代码:文件上传，可以再button的data-name属性设置文件列表的name名，data-max-num属性设置最多上传的文件个数
<div class="form-group">
  	<label>文件列表</label>
  	<div class="uploadfile">
  		<button type="submit" class="btn btn-info upload-file-btn" data-name="filelist" data-max-num="5">上传</button>
  	</div>
</div>
*/
//创建图片html结构,参数分别是:一条图片数据，需要传到后台的图片input name值，需要传到后台的图片描述input name值，图片裁切比例
function create_img_html(data,uploadImgListBtnName,uploadImgListBtnTitleName,aspectRatio){
	var img_title = data.alt;
	if(data.original && data.original!='')img_title=data.original.replace('.'+data.type,'');
	var html='<div class="col-xs-6 col-md-2 thumb-box">\
		    <div class="thumbnail">\
			  <div class="control">\
			  	<span class="move glyphicon glyphicon-arrow-left" data-position="left" data-toggle="tooltip" data-placement="right" data-original-title="排序左"></span>\
			  	<span class="delete glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="bottom" data-original-title="移除图片"></span>\
			  	<span class="cut-img glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="bottom" data-original-title="裁切图片"></span>\
			  	<span class="move glyphicon glyphicon-arrow-right" data-position="right" data-toggle="tooltip" data-placement="left" data-original-title="排序右"></span>\
			  </div>\
		    <img src="'+data.src+'" alt="'+img_title+'" class="img-rounded" data-aspect-ratio="'+aspectRatio+'">\
		    <input type="hidden" name="'+uploadImgListBtnName+'" value="'+data.src+'">\
		    <input type="text" name="'+uploadImgListBtnTitleName+'" value="'+img_title+'" class="form-control input-sm" placeholder="图片描述"/>\
		  </div>\
		</div>';
	return html;
}
//创建文件html结构
//<span class="glyphicon glyphicon-arrow-up move" data-position="down"></span>
//<span class="glyphicon glyphicon-arrow-down delete"></span>
function create_file_html(data,uploadFileListBtnName,uploadFileListTitleBtnName){
	var html='<div class="file-box">\
				<input type="text" readonly="readonly" class="form-control file-src" name="'+uploadFileListBtnName+'" value="'+data.url+'" placeholder="文件地址"/>\
				<input type="text" class="form-control file-title" name="'+uploadFileListTitleBtnName+'" value="'+data.title+'" placeholder="文件描述"/>\
				<span class="glyphicon glyphicon-remove move" data-position="up"></span>\
			</div>';
	return html;
}
//实例化编辑器
var Ueditorupload = UE.getEditor('j_ueditorupload',{
 	autoHeightEnabled:false
});
Ueditorupload.ready(function (){
	$(function(){
		Ueditorupload.hide();//隐藏编辑器
		//监听图片上传
		Ueditorupload.addListener('beforeInsertImage', function (t,arg){
			//是否需要裁切
			var aspectRatio='default';
			if(uploadImgListBtn.attr('data-aspect-ratio')){//裁切宽高比例，默认不限
				aspectRatio=uploadImgListBtn.attr('data-aspect-ratio');
			}
			if(maxUploadImgNum==1){
				var imgHtml=create_img_html(arg[0],uploadImgListBtnName,uploadImgListBtnTitleName,aspectRatio);
	    		uploadImgListBtn.parents('.thumbnails').find('.thumb-box').remove();
	    		uploadImgListBtn.parents('.thumbnails').find('.row.thumbnails-list').append(imgHtml).find('>input').remove();
			}else{
				var nowLength=uploadImgListBtn.parent().find('dl').length;
				var lastNum=maxUploadImgNum-nowLength;
				if(arg.length>lastNum){
					alert('您本次最多还能能添加'+lastNum+'张图片');
				}
				var imgListHtml='';
				for(a in arg){
					if(a<lastNum){
						imgListHtml+=create_img_html(arg[a],uploadImgListBtnName,uploadImgListBtnTitleName,aspectRatio);
					}
				}
				uploadImgListBtn.parents('.thumbnails').find('.row.thumbnails-list').append(imgListHtml).find('>input').remove();
			}
    	});
		/* 文件上传监听
		 * 需要在ueditor.all.js文件中找到
		 * filelist = utils.isArray(filelist) ? filelist : [filelist];
		 * 之后加入 if(me.fireEvent('afterUpfile', filelist) === true){  return;}
		 * 或者在ueditor.all.min.js文件中找到
		 * function(c,b){b=p.isArray(b)?b:[b];
		 * 之后加入 if(d.fireEvent('afterUpfile',b)===true){return;}
		 */
    	Ueditorupload.addListener('afterUpfile', function (t, arg){
    		if(maxUploadFileNum==1){
    			var fileHtml=create_file_html(arg[0],uploadFileListBtnName,uploadFileListTitleBtnName);
		    	uploadFileListBtn.parent().find('.file-box').remove();
		    	uploadFileListBtn.before(fileHtml);
			}else{
				var nowLength=uploadFileListBtn.parent().find('.file-box').length;
				var lastNum=maxUploadFileNum-nowLength;
				if(arg.length>lastNum){
					alert('您本次最多还能能添加'+lastNum+'张图片');
				}
				var fileListHtml='';
				for(a in arg){
					if(a<lastNum){
						fileListHtml+=create_file_html(arg[a],uploadFileListBtnName,uploadFileListTitleBtnName);
					}
				}
				uploadFileListBtn.before(fileListHtml);
			}
    		return false;
    	});
	});
});
  //多图上传按钮,name属性名称
  var uploadImgListBtn,uploadImgListBtnName='',uploadImgListBtnTitleName='',maxUploadImgNum=100;
  var uploadFileListBtn,uploadFileListBtnName='',uploadFileListTitleBtnName='',maxUploadFileNum=10;
  $(function(){
	  if($('.upload-img').length){
		  $('.upload-img').click(function(){
			  uploadImgListBtn=$(this)
			  uploadImgListBtnName=$(this).attr('data-name');
			  if($(this).attr('data-title-name')){
				  uploadImgListBtnTitleName=$(this).attr('data-title-name');
			  }else{
				  //如果图片input name值有[],比如img_list[],则没有设置图片描述name时自动添加img_list_title[]
				  if(/\[\]/.test(uploadImgListBtnName)){
					  uploadImgListBtnTitleName=uploadImgListBtnName.replace('[]','')+'_title'+'[]';
				  }else{
					  uploadImgListBtnTitleName=uploadImgListBtnName+'_title'+'[]';
				  }
			  }
			  maxUploadImgNum=$(this).attr('data-max-upload')?$(this).attr('data-max-upload'):maxUploadImgNum;
			  upImage();
			  return false;
		  })
		  //是否自动填充，主要修改时候用
		  $('.upload-img').each(function(i,v){
			  if($(v).attr('data-auto-create') && $(v).attr('data-auto-create') == 1){
				  var upload_img_name = $(v).attr('data-name');
				  var upload_img_title_name = '';
				  if($(v).attr('data-title-name')){
					  upload_img_title_name=$(v).attr('data-title-name');
				  }else{
					  //如果图片input name值有[],比如img_list[],则没有设置图片描述name时自动添加img_list_title[]
					  if(/\[\]/.test(upload_img_name)){
						  upload_img_title_name=upload_img_name.replace('[]','')+'_title'+'[]';
					  }else{
						  upload_img_title_name=upload_img_name+'_title';
					  }
				  }
				  var max_upload_img_num=$(v).attr('data-max-upload')?$(v).attr('data-max-upload'):maxUploadImgNum;
				  var aspect_ratio='default';
				  if($(v).attr('data-aspect-ratio')){//裁切宽高比例，默认不限
					  aspect_ratio=$(v).attr('data-aspect-ratio');
				  }
				  var html = '';
				  $(v).parents('.thumbnails').find('.thumbnails-list>img').each(function(index,value){
					  if(index < max_upload_img_num){
						  var data = {};
						  data.src = $(value).attr('src');
						  data.alt = $(value).attr('alt')?$(value).attr('alt'):'';
						  html += create_img_html(data,upload_img_name,upload_img_title_name,aspect_ratio);
					  }
				  })
				  $(v).parents('.thumbnails').find('.thumbnails-list').html(html);
			  }
		  })
	  }
	  //删除图片
	  $('.thumbnails').on('click','.delete',function(){
		var $this=$(this);
		var isdelete=confirm('真的要移除这张图片吗？');
		if(isdelete){
			if($this.parents('.thumbnails-list').find('.thumb-box').length==1){
				var upload_img_name = $(this).parents('.thumbnails').find('.upload-img').attr('data-name');
				$this.parents('.thumbnails-list').append('<input type="hidden" name="'+upload_img_name+'" value="">');
			}
			$this.parents('.thumb-box').remove();
		}
	  })
	  //图片排序
	  $('.thumbnails').on('click','.move',function(){
		  var $this = $(this);
		  var data_position = $this.attr('data-position');
		  if(data_position == 'left'){
			  if($this.parents('.thumb-box').prev('.thumb-box').length){
				  $this.parents('.thumb-box').prev('.thumb-box').before($this.parents('.thumb-box').clone());
				  $this.parents('.thumb-box').remove();
			  }
		  }else{
			  if($this.parents('.thumb-box').next('.thumb-box').length){
				  $this.parents('.thumb-box').next('.thumb-box').after($this.parents('.thumb-box').clone());
				  $this.parents('.thumb-box').remove();
			  }
		  }
	  })
	  //上传文件
	  if($('.upload-file').length){
		  $('.upload-file').click(function(){
			  uploadFileListBtn=$(this);
			  uploadFileListBtnName=$(this).attr('data-name');
			  uploadFileListTitleBtnName='';
			  if($(this).attr('data-title-name')){
				  uploadFileListTitleBtnName=$(this).attr('data-title-name');
			  }else{
				  //如果input name值有[],比如img_list[],则没有设置图片描述name时自动添加img_list_title[]
				  if(/\[\]/.test(uploadFileListBtnName)){
					  uploadFileListTitleBtnName=uploadFileListBtnName.replace('[]','')+'_title'+'[]';
				  }else{
					  uploadFileListTitleBtnName=uploadFileListBtnName+'_title';
				  }
			  }
			  maxUploadFileNum=$(this).attr('data-max-upload')?$(this).attr('data-max-upload'):maxUploadFileNum;
			  upFiles();
			  return false;
		  })
		  $('.upload-file').each(function(i,v){
			  if($(v).attr('data-auto-create') && $(v).attr('data-auto-create') == 1){
				  var html="";
				  var uploadFileListBtnName = $(v).attr('data-name');
				  var uploadFileListTitleBtnName='';
				  if($(this).attr('data-title-name')){
					  uploadFileListTitleBtnName=$(this).attr('data-title-name');
				  }else{
					  //如果input name值有[],比如img_list[],则没有设置图片描述name时自动添加img_list_title[]
					  if(/\[\]/.test(uploadFileListBtnName)){
						  uploadFileListTitleBtnName=uploadFileListBtnName.replace('[]','')+'_title'+'[]';
					  }else{
						  uploadFileListTitleBtnName=uploadFileListBtnName+'_title';
					  }
				  }
				  $(v).parent().find('input').each(function(index,value){
					  var data = {};
					  data.url = $(value).attr('value');
					  data.title = $(value).attr('title');
					  html+=create_file_html(data,uploadFileListBtnName,uploadFileListTitleBtnName);
				  })
				  $(v).parent().find('input').remove();
				  $(v).before(html);
			  }
		  })
	  }
	   //删除文件
	  $('body').on('click','.glyphicon.glyphicon-remove.move',function(){
	  	var $this=$(this);
	  	var isdelete=confirm('真的要移除这个文件吗？');
	  	if(isdelete){
	  		$this.parent('div').remove();
	  	}
	  });
	  	//裁切图片
	  	var jcrop_api,data={x:0,y:0,x2:0,y2:0,w:0,h:0,width:0,height:0};
		var $img_box;
		function align_center($obj){
			var left=($(window).width()-$obj.width())/2,top=($(window).height()-$obj.height())/2;
			$obj.css({'left':left,'top':top});
		}
		//初始化裁切
		function jcrop_img(){
			var aspectRatio = 0;
			if($img_box.find('img').attr('data-aspect-ratio') && $img_box.find('img').attr('data-aspect-ratio')!='default'){
				var data_aspect_ratio = $img_box.find('img').attr('data-aspect-ratio');
				//裁切比例可以是宽高比的小数，或者宽高比如1:1.5
				if(/:/.test(data_aspect_ratio)){
					var data_aspect_ratio_arr = data_aspect_ratio.split(':');
					data_aspect_ratio = parseInt(data_aspect_ratio_arr[0])/parseInt(data_aspect_ratio_arr[1]);
				}else{
					data_aspect_ratio = parseFloat(data_aspect_ratio);
				}
				aspectRatio = data_aspect_ratio;
			}
			$('.modal-crop-img .content .crop_box img').Jcrop({
				aspectRatio:aspectRatio,
				onChange:function(e){
					e.width=data.width;e.height=data.height;e.img_src=data.img_src;e.crop_src=data.crop_src;
					data=e;
					var $size_input=$('.modal-crop-img .modal-footer');
					$size_input.find('input[name=width]').val(e.w);
					$size_input.find('input[name=height]').val(e.h);
				}
			},function(){
				jcrop_api=this;
				data.w=0;data.h=0;
				data.width=$('.modal-crop-img .content img').width();
				data.height=$('.modal-crop-img .content img').height();
			});
		}
		//隐藏裁切窗口，并销毁裁切
		$('.modal-crop-img').on('hidden.bs.modal', function (e) {
			jcrop_api.destroy();
		})
		//点击裁切图标
		$('body').on('click','.cut-img',function(){
			$img_box=$(this).parents('.thumb-box');
			var img_src=$img_box.find('img').attr('src');
			$('.modal-crop-img .content .crop_box img').attr('src',img_src);
			$('.modal-crop-img').modal('show')
			data.img_src=img_src;
			jcrop_img();
		})
		//裁切
		var croping=false;
		$('.modal-crop-img .modal-footer .crop').click(function(){
			var $this=$(this);
			if(data && !$this.hasClass('loading') && data.w>0 && data.h>0){
				if(data.w!=$('.modal-crop-img .content .crop_box>img').width() || data.h!=$('.modal-crop-img .content .crop_box>img').height()){
					$this.button('loading');
	  				jcrop_api.disable();
	  				croping=true;
					$.ajax({
						type:'post',
						data:data,
						url:cut_img_url,
						dataType:'json',
						success:function(result){
							croping=false;
							$this.button('reset');
							if(result.status){
								data.crop_src=result.img_src;
								$('.modal-crop-img .content .crop_box>img').attr('src',result.img_src).css({'width':'auto','height':'auto'});
								$this.parent().find('.cancle').show();
								jcrop_api.destroy();
								jcrop_img();
							}
						},
						error:function(){
							alert('裁切失败，请重试');
							jcrop_api.destroy();
							jcrop_img();
							$this.button('reset');
						}
					})
				}
			}else{
				alert('请先选择裁切区');
			}
			return false;
		})
		//取消裁切
		$('.modal-crop-img .modal-footer .cancle').click(function(){
			$(this).hide();
			data.crop_src=data.img_src;
			$('.modal-crop-img .content img').attr('src',data.img_src).css({'width':'auto','height':'auto'});
			jcrop_api.destroy();
			jcrop_img();
			return false;
		})
		//确定裁切
		$('.modal-crop-img .modal-footer .ok').click(function(){
			if(croping){
				alert('正在裁切图片，请稍后');
				return false;
			}
			$img_box.find('img').attr('src',data.crop_src);
			$img_box.find('input[type=hidden]').val(data.crop_src);
			jcrop_api.destroy();
			$('.modal-crop-img').modal('hide')
			return false;
		})
  })
  //弹出图片上传的对话框
  function upImage(){
    var myImage = Ueditorupload.getDialog("insertimage");
    myImage.open();
  }
  //弹出文件上传的对话框
  function upFiles(){
    var myFiles = Ueditorupload.getDialog("attachment");
    myFiles.open();
  }
