<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>银离子后台管理系统</title>
<script>
    $(function(){
        $("#btn_one").click(function(){
            $("#one").show();
            $("#two").hide();
        })

        $("#btn_two").click(function(){
            $("#two").show();
            $("#one").hide();
        })

        $("#add_one").click(function(){
            $("#top").toggle();
        })

        $("#add_two").click(function(){
            $("#two").toggle();
        })
    })
</script>
</head>

<body>

    <div id="right">

        <fieldset>
            <legend>商品分类管理</legend>
            <button id="btn_one" class="btn" type="button" style="margin-right: 20px;margin-bottom: 20px;">顶级分类管理</button>
            <button id="btn_two" class="btn" type="button" style="margin-bottom: 20px;">二级分类管理</button>
            <div id="one">
                <label>顶级分类管理</label>
                <table class="table table-striped table-hover" style="font-size: 13px;">
                    <tr><th>序号</th><th>分类名称</th><th>类型</th><th>操作</th></tr>
                    <?php $flag='';?>
                    <?php foreach($list as $value):?>
                        <?php $flag++?>
                    <tr><td><?php echo $flag;?></td><td><?php echo $value["name"]?></td><td>顶级分类</td>
                        <td><a href="<?php echo site_url('Product_category/admin_delete_top').'/'.$value["id"]?>" onclick="return confirm('删除顶级分类将默认删除其所有子分类以及此分类的所有商品，请谨慎操作！！')">删除</a></td>
                    </tr>
                    <?php endforeach;?>
                </table>
                <a href="#"><button id="add_one" class="btn" type="button">添加顶级分类</button></a>
                <form method="post" action="<?php echo site_url('Product_category/admin_add_top')?>" id="top">
                    <input type="text" name="top">
                    <input type="submit" value="确认添加">
                </form>
            </div>



            <div id="two" style="display: none">
                <label>二级分类管理</label>

                <table class="table table-striped table-hover" style="font-size: 13px;">
                    <tr><th>序号</th><th>分类名称</th><th>类型</th><th>顶级分类</th><th>操作</th></tr>
                    <?php $flag='';?>
                    <?php foreach($list_two as $value):?>
                        <?php $flag++?>
                        <tr><td><?php echo $flag;?></td><td><?php echo $value["two_name"]?></td><td>二级分类</td>
                            <td><?php echo $value["one_name"]?></td><td><a href="<?php echo site_url('Product_category/admin_delete_two/').'/'.$value["two_id"]?>" onclick="return confirm('请确定此分类下无任何商品，请谨慎操作！！')">删除</a></td></tr>
                    <?php endforeach;?>
                </table>
                <a href="#"><button id="add_one" class="btn" type="button">添加二级分类</button></a><br/>
                <form method="post" action="<?php echo site_url('Product_category/admin_add_two')?>">
                <select name="par">
                    <option>--请选择顶级分类--</option>
                    <?php foreach($list as $value2):?>
                        <option value="<?php echo $value2['id']?>"><?php echo $value2["name"]?></option>
                    <?php endforeach;?>
                </select>
                <input type="text" name="two_name" placeholder="输入二级分类" style="height: 30px;">
                <input type="submit" value="确认添加">
                </form>
            </div>


        </fieldset>


    </div>

</body>
</html>