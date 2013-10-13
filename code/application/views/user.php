<div id="right">
<fieldset>
    <legend>用户管理</legend>
		<form id="formaction" action="<?php echo site_url('User/index') ;?>" method="post">
		    <table width="100%">
		        <tr>       	
		            <td width="45%">
						<a href="<?php echo site_url('User/add_user') ;?>" style="text-decoration: none;"><button class="btn" type="button">添加用户</button></a>            </td>
		            <td>
		                <input class="input-medium search-query" style="height:30px;width:250px;" type="text" name="user_name" id="user_name" placeholder="请输入要查询的用户名"><input class="btn" type="submit" value="搜素">
		            </td>
		        </tr>
		    </table>
		</form>

 
   <?php if (isset($list)){ ?>
    <table class="table table-striped table-hover" style="font-size: 13px;">
        <tr>
            <th width="20%"   style="text-align: center;">用户名</th>
            <th width="20%"   style="text-align: center;">真实姓名</th>
            <th width="20%"   style="text-align: center;">注册时间</th>
            <th width="20%"   style="text-align: center;" colspan="2">操作</th>
            <th   style="text-align: center;">管理员</th>
        </tr>
        <?php
            foreach($list as $item){
                if($item['uid'] == '1'){
         ?>
        <tr>
            <td  style="text-align: center;padding: 0"><font color="#a52a2a"><?php echo $item["username"];?></font></td>
            <td  style="text-align: center;padding: 0"><font color="#a52a2a"><?php echo $item["truename"];?></font></td>
            <td  style="text-align: center;padding: 0"><font color="#a52a2a">——</font></td>
            <td  style="text-align: center;padding: 0"><font color="#a52a2a">——</font></td>
            <td  style="text-align: center;padding: 0"><font color="#a52a2a"><a href="<?php echo site_url('User/add_user/'.$item["uid"]); ?>" >编辑</a></font></td>
            
            <td  style="text-align: center;"><font color="#a52a2a">超级管理员</font></td>
        </tr>
        <?php
            }else{
        ?>
            <tr>
                <td style="text-align: center;padding: 0"><?php echo $item["username"];?></td>
                <td style="text-align: center;padding: 0"><?php echo $item["truename"];?></td>
                <td style="text-align: center;padding: 0"><?php echo $item["createtime"];?></td>
                <td  style="text-align: center;padding: 0"><a href="<?php echo site_url('User/edit_password/'.$item["uid"]); ?>" >修改密码</a></td>
                <td  style="text-align: center;padding: 0"><a href="<?php echo site_url('User/delete/'.$item["uid"]); ?>" onclick="return confirm('是否删除')">删除</a></td>
                <td  style="text-align: center;padding: 0">
                    <form id="check<?php echo $item['uid']; ?>" name="check<?php echo $item['uid']; ?>" action="<?php echo site_url('User/makeadmin/'.$item['uid'])?>" method="post">
                        <input type="radio" id="mkadmin<?php echo $item['uid']; ?>" name="is_admin" value="1"  <?php if($item['is_admin'] == '1') { ?> checked ="checked" <?php } ?> onclick='checkadmin("<?php echo 'check'.$item['uid']; ?>")'>&nbsp;是&nbsp;
                        <input type="radio" id="rmadmin<?php echo $item['uid']; ?>" name="is_admin" value="0"  <?php if($item['is_admin'] == '0') { ?> checked ="checked" <?php } ?> onclick='checkadmin("<?php echo 'check'.$item['uid']; ?>")'>&nbsp;否
                    </form>
                </td>
            </tr>
            	<script>
	            	function checkadmin(id){
	                	document.forms[id].submit();	
	            	}
           		 </script>
            <?php
                }
            }
        ?>
            </table>
        <?php
            }else{
                echo '暂无用户';
            }
        ?>
     </fieldset>
		<div id="page" style="text-align: center"><?php echo $this->pagination->create_links()?></div>
    </div>