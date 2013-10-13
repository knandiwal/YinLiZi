<div id="right">
  <fieldset>
    <legend>创建论坛</legend>
    
    <form method="post" action="<?php echo site_url('Community/edit')?>" class="form-search" style="margin-top: 20px;" enctype="multipart/form-data">
        <label>论坛名:</label><input type="text" name="name" id="name" value="<?php echo $name?>"><br>
        <label>背景图:</label><input type="file" name="fimage" id="image" ><br>
        <input type="hidden" name="fid" value="<?php echo $fid;?>">
        <button type="submit" class="btn" onclick="return checkinfo();">创建</button>
    </form>
    
   </fieldset>
</div>
<script>
	function checkinfo(){
		if($("#name").val() == ''){
			alert('请填写论坛名');
			return false;
		}
	}
</script>
