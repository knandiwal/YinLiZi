<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>银离子后台管理系统</title>
    <script charset="utf-8" src="<?php echo base_url();?>js/validate.js"></script>
<script type="text/javascript">

    function change(id){
        $("#form_"+id).submit();
    }


</script>
</head>

<body>

    <div id="right">


            <fieldset>
                <legend>首页轮播图片管理</legend>
                <button class="btn " type="button"><a href="<?php echo site_url("Rotation/admin_add_show")?>">添加图片</a></button>
                <table class="table table-striped table-hover" style="font-size: 13px;margin-top: 30px;">
                    <tr><th>编号</th><th>名称</th><th>简介</th><th>图片预览</th><th colspan="3"style="text-align: center">操作</th></tr>
                    <?php $flag = 1?>
                    <?php foreach($list as $value):?>
                        <form id="form_<?php echo $value["id"]?>" method="post" action="<?php echo site_url('Rotation/admin_if_on/'.$value["id"])?>">
                        <?php if($value["is_on"] == 1){?>
                        <tr><td><?php echo $flag;?></td><td><?php echo $value["name"]?></td><td><?php echo $value["intro"]?></td><td><a href="<?php echo base_url().'uploads/'.$value["url"]?>" target="_blank">点击预览</a></td>
                        <td style="text-align: center"><input type="radio" name="if_on<?php echo $value["id"];?>" value="1" checked="checked">启用</td><td><input type="radio" name="if_on<?php echo $value["id"];?>" value="0" onclick="change(<?php echo $value['id']?>)">禁用</td><td><a href="<?php echo site_url('Rotation/admin_delete').'/'.$value["id"]?>"onclick="return confirm('确认要删除？')">删除</a></td>
                    </tr>
                    <?php }else{?>
                    <tr><td><?php echo $flag;?></td><td><?php echo $value["name"]?></td><td><?php echo $value["intro"]?></td><td><a href="<?php echo base_url().'uploads/'.$value["url"]?>" target="_blank">点击预览</a></td>
                        <td style="text-align: center"><input type="radio" name="if_on<?php echo $value["id"];?>" value="1" onclick="change(<?php echo $value['id']?>)">启用</td><td><input type="radio" name="if_on<?php echo $value["id"];?>" value="0" checked="checked">禁用</td><td><a href="<?php echo site_url('Rotation/admin_delete').'/'.$value["id"]?>" onclick="return confirm('确认要删除？')">删除</a></td>
                    </tr>
                    <?php }$flag++?>
                    </form>
                    <?php endforeach;?>
                </table>
            </fieldset>


    </div>

</body>
</html>