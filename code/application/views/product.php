<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>银离子后台管理系统</title>
</head>
<body>

    <div id="right">

        <fieldset>
            <legend>商品管理</legend>
            <a href="<?php echo site_url('Product/admin_add_show')?>" style="margin-bottom: 20px;"><button class="btn" type="button">添加商品</button></a>
            <form method="post" action="<?php echo site_url('Product/admin_search')?>" class="form-search" style="margin-top: 20px;">
                <input type="text" name="input_title" class="input-medium search-query" placeholder="请输入要查询的商品名" style="height: 30px;">
                <button type="submit" class="btn">搜索</button>
            </form>
            <table class="table table-striped table-hover" style="font-size: 13px;margin-top: 20px;">
               <tr><th>商品名称</th><th>价格</th><th>详情</th><th colspan="2" style="text-align: center">操作</th></tr>
                <?php foreach($product as $value):?>
                <tr><td><?php echo $value["title"]?></td><td><?php echo $value["price"]?></td><td><?php echo mb_substr(strip_tags($value["detail"]),0,10,"utf8")."..."?></td><td  style="text-align: center;" ><a href="<?php echo site_url("Product/admin_update_show/").'/'.$value["id"]?>">修改</a></td><td style="text-align: center;" ><a id="del" href="<?php echo site_url("Product/admin_delete/").'/'.$value["id"]?>" onclick="return confirm('确定要删除？')">删除</a></td></tr>
                <?php endforeach;?>
            </table>

        </fieldset>
<div id="page" style="text-align: center"><?php echo $this->pagination->create_links()?></div>

    </div>

</body>
</html>