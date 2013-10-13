<div id="right">
<fieldset>
    <legend>用户信息修改</legend>
	<form action="<?php echo site_url('User/edit');?>" method="post" enctype="multipart/form-data">
 	<table>
 		<caption></caption>
 		<tr><td colspan="2"><input type="hidden" name="uid" id="uid" value="<?php echo $uid; ?>" /></td></tr>
 		<tr><td>用户名：</td><td><input type="text" name="username" id="username" value="<?php echo $username; ?>"></td></tr>
 		<tr><td>真实姓名：</td><td><input type="text" name="truename" id="truename" value="<?php echo $truename; ?>"></td></tr>
 		<tr><td>密码：</td><td><input type="password" name="userpassword" id="userpassword"><?php if($uid){echo '密码留空表示不修改'; } ?></td></tr>
 		<tr><td>确认密码：</td><td><input type="password" name="userpassword2" id="userpassword2"></td></tr>
 		<tr><td>邮箱：</td><td><input type="text" name="useremail" id="useremail" value="<?php echo $useremail; ?>"></td></tr>
 		<tr><td>地址：</td><td><input type="text" name="useraddress" id="useraddress" value="<?php echo $useraddress; ?>"></td></tr>
 		<tr><td>是否为管理员：</td><td><input type="checkbox" name="is_admin" id="is_admin" onclick="checkon(this)" <?php if ($uid){ if ($is_admin == '1'){ echo 'checked';} } ?>></td></tr>
 		<tr><td>头像：</td><td><input type="file" name="userhead" id="userhead"></td></tr>
 		<tr><td colspan="2"><input class="btn" type="submit" value="确定" onclick="return checkinfo();"></td></tr>
 	</table>
	</form>
</fieldset>
</div>
<script>
 function checkon(obj){
	if(obj.checked){
		$("#is_admin").attr('value','1');
	}else{
		$("#is_admin").attr('value','0');
	}
 }
 function checkinfo(){
	if($("#username").val() == ''){
		alert('请输入用户名');
		return false;
	}
	if($("#username").val().length > 10){
		alert('用户名长度不能超过10位');
		return false;
	}
	if($("#truename").val() == ''){
		alert('请输入真实姓名');
		return false;
	}
	if($("#uid").val() == ''){
		if($("#userpassword").val() == ''){
			alert('请输入密码');
			return false;
		}
		if($("#userpassword").val().length <= 2){
			alert('密码长度不能短于2位');
			return false;
		}
		if($("#userpassword2").val() == ''){
			alert('请输入确认密码');
			return false;
		}
		if($("#userpassword2").val().length <= 2){
			alert('密码长度不能短于2位');
			return false;
		}
	}
	if($("#userpassword").val() && $("#userpassword2").val()){
		if($("#userpassword").val() != $("#userpassword2").val()){
			alert('两次密码不匹配');
			return false;
		}
	}
	if($("#useremail").val()){
		 var str = /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/;
	  	 var email_val = $("#useremail").val();
	  	 if(!str.test(email_val)){   	 	
	  		alert('请输入正确的邮箱地址！');
	  		return false;		    	 
	     }
	}
	
 }

</script>