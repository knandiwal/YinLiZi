<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>银离子后台管理系统</title>
    <script charset="utf-8" src="<?php echo base_url();?>js/root.js"></script>
    <script charset="utf-8" src="<?php echo base_url();?>js/validate.js"></script>

    <script type="text/javascript">
        $(function(){
            $("#product_category").change(function(){
                var id = this.options[this.selectedIndex].id;
                $.ajax({
                    url:APP_ACTION.GET_CHILD+id,
                    success:function(res){
                        var ob = JSON.parse(res);
                        var html = '';
                        for(i=0;i<ob.length;i++){
                            html = html +"<option value='"+ob[i].id+"'>"+ob[i].name+"</option>";
                        }
                        $("#lower_category").empty();
                        $("#lower_category").append(html);
                    },
                    error:function(){
                        alert('error');
                    }
                })
            })

            var flag = 0;
            $("#add_img").click(function(){
                 flag += 1;
                var html = "<br><input type='file' name='file[]'/>";
                $("#before_img").before(html);
                $("#img_count").attr("value",flag+1);
            })


        })

        KindEditor.ready(function(K) {
            window.editor = K.create('#editor_id');
        });
        var options = {
            cssPath : '/css/index.css',
            filterMode : true
        };
        var editor = K.create('textarea[name="content"]', options);
    </script>
</head>
<body>


<div id="right">

    <form id="product_form"  enctype="multipart/form-data" method="post" action="<?php echo site_url('Product/admin_add'); ?>">
        <fieldset>
            <legend>添加商品</legend>
            <a href="<?php echo site_url('Product/index')?>" style="margin-bottom: 20px;"><button class="btn" type="button">返回商品列表</button></a>
            <label style="margin-top: 20px;">商品名称:</label>
            <input type="text" name="title" id="title" class="input-xxlarge" style="height: 30px;" placeholder="请输入商品名称">
            <label>商品类别:</label>
            <select  id="product_category">
                <option value=0 selected>--选择商品类别--</option>
                <?php foreach($list as $value):?>
                    <option value="<?php echo $value["name"]?>" id="<?php echo $value["id"]?>"><?php echo $value["name"]?></option>
                <?php endforeach;?>
            </select>

            <select name="category" id="lower_category">
            </select>
            <label>商品价格:</label>
            <input type="text" name="price" id="price" style="height: 30px;"/>
            <label>上传图片:</label>
            <input id="img_count" type="hidden" name="img_count"value="1"/>
            <input type="file" name="file[]" /><span id="add_img" title="添加多张图片" style="background:red;font-size: 20px;cursor: pointer">+</span>

            <label id="before_img">商品简介:</label>
            <textarea rows="3" name="intro" id="intro"></textarea>
            <label  >商品详情:</label>
            <textarea id="editor_id" name="content" style="width:700px;height:300px;">

            </textarea>

            <button type="submit" class="btn" style="margin:20px auto;">添加商品</button>
        </fieldset>
    </form>
    <div style="clear:both;"></div>
</div>



<script language="javascript">
    $("#test1").toggle(function(){$("#test").slideDown()},function(){$("#test").slideUp()})
</script>

</body>
</html>