<div id="right">
<fieldset>
    <legend>问答平台</legend> 
    <a href="#" onclick="show();"><input class="btn" type="button" value='提问'></a> 
	<div id="create" style="display:none;">
		<form action="<?php echo site_url('Faq/edit'); ?>" method="post" id="faq_form">
		  <input style="height:25px;" type="text" name="message" id="message">
		  <input class="btn" type="submit" value="确定">
		</form>
	</div>
	<div>
	<?php 
	if($lists){ 
		
			foreach ($lists as $list){
		?>
			<div><a onclick="showresult(<?php echo $list['id']; ?>)"><?php echo $list['message']; ?></a><span style="margin-left:100px;"><?php //echo '提问者:'.$list['username'].'&nbsp;&nbsp;&nbsp;'.date('Y-m-d',strtotime($list['createtime']));?></span>
			<span ><a href="<?php echo site_url('Faq/delete/'.$list['id']);?>" onclick="return confirm('确定要删除？')">删除</a></span>
			</div>
			<div id='showall<?php echo $list['id'] ?>' style='display:none;'>
				<div id="show<?php echo $list['id'] ?>" style='display:none;'><form action="<?php echo site_url('Faq/solve_question/'.$list['id']);?>" method="post"><input  style="height:25px;" type="text" name="message" id="message<?php echo $list['id'];?>">
                        <br/>
                        <input type="submit" class="btn" value="提交" onclick="return checkinfo('<?php echo $list['id']; ?>');"></form></div>
				<div id="list<?php echo $list['id']; ?>"></div>
				
			</div>
			<hr  style="border:1px solid #CCCCCC; margin-bottom: 15px;">
		<?php } ?>
		<script>
		    function checkinfo(id){
				if($("#message"+id).val() == ''){
					alert('内容不可以为空');
					return false;
				}
		    }
			function showresult(id){				
				if($("#showall"+id).css('display') == 'none'){
				
				if($("#list"+id).html() == ''){
					$("#showall"+id).css('display','');
					$.ajax({
				        url:"<?php echo site_url('Faq/get_solve/');?>"+'/'+id,
				        success:function(res){
					            var ob = JSON.parse(res);
					            var html = '';
					            if(ob.length > 0){
						            html += '<span>回答：</span>';
					            for(i=0;i<ob.length;i++){
					           		html = html +"<div>"+ob[i].message+"&nbsp;&nbsp;&nbsp;&nbsp;"+ob[i].create_time+"&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:20px;' onclick='removetr(this,"+ob[i].id+")'><img src='<?php echo base_url()."images/minimize.png" ;?>' /></span></div>";
					            }
					           	$("#list"+id).html(html);
					            }else{
					            	$("#show"+id).css('display','');
					            }
				        },
				        error:function(){
				            alert('error');
				        }
				    })
				}
				}else{
					$("#showall"+id).css('display','none');
				}
			}

			</script>
	<?php }?>
	</div>

</fieldset>
</div>
<script>
function show(){
	$("#create").css('display','');
}
$("#faq_form").submit(function(){
	if($("#message").val() == ''){
		alert('请填写问题');
		return false;
	}
});
function removetr(obj,id){
	$(obj).parent().remove();
	 $.ajax({
         url:"<?php echo site_url('Faq/delete_answer/');?>"+"/"+id,
         success:function(res){
            
         },
         error:function(){
             alert('error');
         }
     })
}
    


</script>