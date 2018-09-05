<?php
require_once("connection.php");
$srcUrl = "files/";
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
	body { margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; }
	.vpop { position:fixed; width:100%; left:0; right:0; bottom:0; top:0; height:100%; background:#CCC; background-color: black; z-index:1001; -moz-opacity: 0.9; opacity:.90; filter: alpha(opacity=90);  display:none;}
	.poparea { position:fixed; top:50px; bottom:50px; left:50px; right:50px; z-index:1002; display:none; } 
	.vct { position:absolute; right:0; width:300px; height:100%; background:#FFF; border-radius:3px; }
	.stage { position:absolute; right:0; top:0; bottom:0; left:0; height:100%; /*background:#000; border:1px solid #1E1E1E;*/ box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; -o-box-sizing:border-box; text-align:center; }
	.stage img { border:5px solid #999; border-radius:10px; }
	.vbox-close { position:fixed; top:10px; right:10px; z-index:1003; /*width:30px; height:30px;  padding:5px; background:#FFF;*/ display:none;font-size:20px; font-weight:bold; box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box; -o-box-sizing:border-box; font-family:Verdana, Geneva, sans-serif; color:#FFF; cursor:pointer }
	.vbox-next { font-size:45px; color:#FFF; position:fixed; right:10px; top:50%; display:none; z-index:1004; text-decoration:none; margin-top:-30px;  }
	.vbox-previous { font-size:45px; color:#FFF; position:fixed; left:10px; top:50%; display:none; z-index:1004; text-decoration:none; margin-top:-30px;  }
	.vbox-pictitle { position:fixed; top:20px; left:60px; right:60px; text-align:center; font-weight:bold; font-size:16px; color:#FFF; display:none; z-index:1005; }
	
	.loading { position:absolute; height:40px; width:40px; left:50%; top:50%; margin:-20px 0 0 -20px }
	.sn { position:fixed; left:50px; right:50px; bottom:10px; display:none; z-index:1004; color:#FFF; text-align:center; }
</style>
<script src="js/jquery.min.js"></script>
<script type="text/javascript">
function ScaleImage(srcwidth, srcheight, targetwidth, targetheight, fLetterBox) {

    var result = { width: 0, height: 0, fScaleToTargetWidth: true };

    if ((srcwidth <= 0) || (srcheight <= 0) || (targetwidth <= 0) || (targetheight <= 0)) {
        return result;
    }

    // scale to the target width
    var scaleX1 = targetwidth;
    var scaleY1 = (srcheight * targetwidth) / srcwidth;

    // scale to the target height
    var scaleX2 = (srcwidth * targetheight) / srcheight;
    var scaleY2 = targetheight;

    // now figure out which one we should use
    var fScaleOnWidth = (scaleX2 > targetwidth);
    if (fScaleOnWidth) {
        fScaleOnWidth = fLetterBox;
    }
    else {
       fScaleOnWidth = !fLetterBox;
    }

    if (fScaleOnWidth) {
        result.width = Math.floor(scaleX1);
        result.height = Math.floor(scaleY1);
        result.fScaleToTargetWidth = true;
    }
    else {
        result.width = Math.floor(scaleX2);
        result.height = Math.floor(scaleY2);
        result.fScaleToTargetWidth = false;
    }
    result.targetleft = Math.floor((targetwidth - result.width) / 2);
    result.targettop = Math.floor((targetheight - result.height) / 2);

    return result;
}

function OnImageLoad(evt) {
    //var img = evt.currentTarget;
	var img = (evt.currentTarget) ? evt.currentTarget : evt.srcElement;
    // what's the size of this image and it's parent
    var w = $(img).width();
    var h = $(img).height();
    var tw = $(img).parent().closest('div').width();
    var th = $(img).parent().closest('div').height();
    // compute the new size and offsets
    var result = ScaleImage(w, h, tw, th, false);
    // adjust the image coordinates and size
    img.width = result.width;
    img.height = result.height;
    $(img).css("left", result.targetleft);
    $(img).css("top", result.targettop);
}
</script>
<script type="text/javascript">
var img_array = Array();
var img_alt_array = Array();
var img_title_array = Array();
var img_desc_array = Array();
$(document).ready(function(){
  $('.vbox-close').live('click',function(){ 
  $('.vpop').css({'display':'none'});
  $('.poparea').css({'display':'none'});
  $('.vbox-close').css({'display':'none'});
  $('.vbox-previous').css({'display':'none'});
  $('.vbox-pictitle').css({'display':'none'});
  $('.vbox-pictitled').css({'display':'none'});
  $('.vbox-next').css({'display':'none'});
  $('.sn').css({'display':'none'});
  cur_img = 1;
 });
 
 

  $('.vbox-previous').live('click',function(){
	  if(cur_img==1) return false;
	  cur_img--;
	  //srcimg = 'img_'+cur_img;
	  srcimg = img_array[cur_img];
	  setImage(srcimg, cur_img)
	  if(cur_img==1){
		$('.vbox-previous').css({'display':'none'});	  	
	  }
  });	  
  
  $('.vbox-next').live('click',function(){
	  if(cur_img==numImgs) return false;
	  cur_img++;
	  //srcimg = 'img_'+cur_img;
	  srcimg = img_array[cur_img];
	  setImage(srcimg, cur_img)
	  if(cur_img==numImgs){
		$('.vbox-next').css({'display':'none'});
	  }
  });
 
});
</script>
<script type='text/javascript'>
     $(window).load(function(){
            /*we are using $(window).load() here because we want the coding to take
            effect when the image is loaded. */
            //get the width of the parent
            var parent_height = $('#the_image').parent().height();
            var image_height = $('#the_image').height();
            var top_margin = (parent_height - image_height)/2;
            //center it
			//alert(parent_height + "==" + image_height); 
            $('#the_image').css( 'margin-top' , top_margin);
     });
	$(document).ready(function() {
    //do jQuery stuff when DOM is ready
}); 
	$(window).resize(function() {
  		var parent_height = $('#the_image').parent().height();
            var image_height = $('#the_image').height();
            var top_margin = (parent_height - image_height)/2;
            //center it
			//alert(parent_height + "==" + image_height); 
            $('#the_image').css( 'margin-top' , top_margin);
	}); 
	
	$(document).unbind('keydown').bind('keydown',function(e){
			if($(".poparea").is(":visible")){
				switch(e.keyCode){
					case 37:$('.vbox-previous').trigger('click');e.preventDefault();break;
					case 39:$('.vbox-next').trigger('click');e.preventDefault();break;
					case 27:$('.vbox-close').trigger('click');e.preventDefault();break;
		};};});
	
	$(document).unbind('keydown.prettyphoto').bind('keydown.prettyphoto',function(e){if(typeof $pp_pic_holder!='undefined'){if($pp_pic_holder.is(':visible')){switch(e.keyCode){case 37:$.prettyPhoto.changePage('previous');e.preventDefault();break;case 39:$.prettyPhoto.changePage('next');e.preventDefault();break;case 27:if(!settings.modal)
$.prettyPhoto.close();e.preventDefault();break;};};};});

</script>
<script language="javascript">
var numImgs = $('#slide_images img').length;
var cur_img = 1;
function setImage(srcimg, imgCnt)
{
  $('#the_image').attr({src: srcimg});
  $('#the_image').css({'visibility':'hidden'});
  $('#loading_div').css({'display':'block'});
  cur_img = imgCnt;

  $('.vpop').css({'display':'block'});
  $('.poparea').css({'display':'block'});	
  $('.vbox-close').css({'display':'block'});  
  
	   
	   //$('#the_image').load(function(){
	   $('#the_image').imagesLoaded(function(srcimg){	   
		  //$('.vbox-pictitle').html(img_alt_array[imgCnt]);
		  $('.vbox-close').css({'display':'block'});
		  $('.vbox-next').css({'display':'block'});
		  $('.vbox-previous').css({'display':'block'});
		  $('.vbox-pictitle').css({'display':'block'});
		  $('.vbox-pictitled').css({'display':'block'});
		  $('.sn').css({'display':'block'});
		  $('.vbox-pictitle').html(img_title_array[imgCnt]);
		  $('.vbox-pictitled').html(img_desc_array[imgCnt]);
		  
		   var parent_height = $('#the_image').parent().height();
		   var image_height = $('#the_image').height();
		   var top_margin = (parent_height - image_height)/2;
		   $('#the_image').css( 'margin-top' , top_margin);
	   
			$('#loading_div').css({'display':'none'});
	   		$('#the_image').css({'visibility':'visible'});
			
			if(cur_img==1 || imgCnt==1)
		   {
			 $('.vbox-previous').css({'display':'none'}); 
			 $('.vbox-next').css({'display':'block'}); 
		   }
		   
		   if(cur_img==numImgs || imgCnt==numImgs)
		   {
				$('.vbox-next').css({'display':'none'});
				$('.vbox-previous').css({'display':'block'});  
		   }
		   if(numImgs==1)
		   {
			   $('.vbox-next').css({'display':'none'});
			   $('.vbox-previous').css({'display':'none'});
		   }
		});
}
$.fn.imagesLoaded = function(callback){
  var elems = this.filter('img'),
      len   = elems.length,
      blank = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
      
  elems.bind('load.imgloaded',function(){
      if (--len <= 0 && this.src !== blank){ 
        elems.unbind('load.imgloaded');
        callback.call(elems,this); 
      }
  }).each(function(){
     // cached images don't fire load sometimes, so we reset src.
     if (this.complete || this.complete === undefined){
        var src = this.src;
        // webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
        // data uri bypasses webkit log warning (thx doug jones)
        this.src = blank;
        this.src = src;
     }  
  }); 
 
  return this;
};
</script>
<title>Image Gallery Demo</title>
</head>

<body>
<p>
<div id="slide_images">
	<?php
		$cnt=0;
		$img_arr = array();
		
		$sqlQuery = "SELECT * FROM gallery WHERE status = 'Active'";
		$objQuery = mysql_query($sqlQuery);
		$intTotalRows = mysql_num_rows($objQuery);
		if($intTotalRows > 0)
		{
			while($arr = mysql_fetch_assoc($objQuery))
			{
				$cnt++;
				//$img_arr[] = $arr["image"];
				$img_arr[$cnt] = $arr["image"];
				?>
				<script> 
				img_array[<?php echo $cnt;?>] = '<?php echo $srcUrl.$img_arr[$cnt];?>';
				img_title_array[<?php echo $cnt;?>] = '<?php echo $arr['name'];?>';
				img_desc_array[<?php echo $cnt;?>] = '<?php echo $arr['description'];?>';
                img_alt_array[<?php echo $cnt;?>] = ''; 
            </script>
            <div style="width:150px; height:150px; border:thick solid #666666; overflow:hidden; position:relative; float:left; margin-right:10px; margin-top:5px;">
                <a onClick="setImage('<?php echo $srcUrl.$arr["image"];?>','<?php echo $cnt;?>');" class="vbox" style="cursor:pointer;">
                	<img id="img_<?php echo $cnt;?>" src="<?php echo $srcUrl.$arr["image"];?>" border="0" style="position: absolute;" onload="OnImageLoad(event);" />
                </a>
            </div>
				<?php
			}
		}
	?>
</div>
</p>

<div class="vpop"></div>
<div class="vbox-pictitle">Picture Name</div>
<div class="vbox-close">X</div>
<a href="javaScript:;" class="vbox-next">&raquo;</a>
<a href="javaScript:;" class="vbox-previous">&laquo;</a>
<div class="poparea">
    <div class="stage" id="container">
        <img id='the_image' style="max-width:100%; max-height:100%;" alt="" />
        <div class="loading" id="loading_div" style="display:none;"><img src="images/loading_icon.gif" border="0" /></div>
    </div>
</div>
<div class="sn">
    <div class="vbox-pictitled">Description</div>
    <div style="clear:both"></div>
</div>

</body>
</html>