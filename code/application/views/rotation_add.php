<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>银离子后台管理系统</title>
    <script charset="utf-8" src="<?php echo base_url();?>js/root.js"></script>
    <script charset="utf-8" src="<?php echo base_url();?>js/validate.js"></script>


</head>
<body>


<div id="right">
    <form id="rotation_form" method="post" action="<?php echo site_url('Rotation/admin_add')?>" enctype="multipart/form-data">
        <fieldset>
            <legend>添加图片</legend>
            <button class="btn" type="button"><a href="<?php echo site_url("Rotation")?>">返回列表</a></button>
            <label style="margin-top: 20px;">图片名称:</label>
            <input type="text" id="img_name" name="img_name" class="input-xlarge" placeholder="" style="height: 30px;">
            <label style="margin-top: 20px;">图片简介:</label>
            <textarea rows="3"id="img_intro" name="img_intro"></textarea>
            <label style="margin-top: 20px;">上传图片:</label>
            <input type="file" name="img_rotation"/>
            <br />
            <button type="submit" class="btn" style="margin-top: 20px;">添 加</button>
        </fieldset>
    </form>

</div>

</body>
</html>