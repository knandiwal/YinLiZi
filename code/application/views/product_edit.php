<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>银离子后台管理系统</title>
    <script charset="utf-8" src="<?php echo base_url();?>js/root.js"></script>
    <script charset="utf-8" src="<?php echo base_url();?>js/validate.js"></script>
    <style type="text/css">


        .imgWrap{
            position: relative;
            display: inline-block;
        }
        .main_img:hover+ .delete_img{
            display: block;

        }
        .delete_img{
            display: none;
            position: absolute;
            top:0px;
            right:0px;
            cursor: pointer
        };


    </style>
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


            $(".imgWrap").dblclick(function(){
                var target = $(this);
                var id = target.attr("id")
                $.ajax({
                    url:APP_ACTION.DELETE_IMG+id,
                    success:function(res){
                        if(res == 1){
                            alert("删除成功");
                            target.remove();
                        }
                    },
                    error:function(){
                        alert("error");
                    }
                })
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
    <form id="product_form" enctype="multipart/form-data" method="post" action="<?php echo site_url('Product/admin_update'); ?>">
        <fieldset>
            <legend>商品修改</legend>
            <a href="<?php echo site_url('Product/index')?>" style="margin-bottom: 20px;"><button class="btn" type="button">返回商品列表</button></a>
            <label style="margin-top: 20px;">商品名称:</label>
            <input type="text" name="title" id="title" class="input-xxlarge" style="height: 30px;" placeholder="请输入商品名称" value="<?php echo $info["title"]?>">
            <label>商品类别:</label>
            <select  id="product_category">
                <option value = 1 selected>--选择商品类别--</option>
                <?php foreach($list as $value):?>
                    <option value="<?php echo $value["name"]?>" id="<?php echo $value["id"]?>"><?php echo $value["name"]?></option>
                <?php endforeach;?>
            </select>

            <select name="category" id="lower_category">

                <option value="<?php echo $category_id;?>"><?php echo $category?></option>
            </select>
            <label>商品价格:</label>
            <input type="text" name="price" id="price" value="<?php echo $info["price"]?>" style="height: 30px;"/>
            <label>缩略图:</label>
            <?php if($info["img1"]){?>
            <img src="<?php echo base_url().'uploads/'.$info["img1"]?>" style="width: 80px;height: 80px;border: 1px solid #ccc;">
            <?php }else{?>
                <div class="alert">
                    <strong>此商品没有缩略图</strong>
                </div>
            <?php }?>
            <label>重新上传商品缩略图:</label>
            <input  type="file" name="img_thumb"/>
            <label>商品详图:</label>

            <?php if($img_list){ foreach($img_list as $value):?>
                <div class='imgWrap' title="双击删除此图片" id="<?php echo $value["id"]?>" style="cursor: pointer;">
                    <img  class="main_img"  src="<?php echo base_url().'uploads/'.$value["img_url"]?>" style="width: 80px;height: 80px;border: 1px solid #cccccc;"/>
                    <img class="delete_img"  src="<?php echo base_url().'images/delete.png'?>" style="">
                </div>
            <?php endforeach;}else {?>
                <div class="alert">
                    <strong>未查到此商品的相关图片</strong>
                </div>
            <?php }?>
            <label>上传商品详图:</label>
            <input id="img_count" type="hidden" name="img_count"value="1"/>
            <input  type="hidden" name="reference_url" value="<?php echo $_SERVER["HTTP_REFERER"]?>"/>
            <input type="file" name="file[]" /><span id="add_img" title="添加多张图片" style="background:red;font-size: 20px;cursor: pointer">+</span>
            <label id="before_img">商品简介:</label>
            <textarea rows="3" name="intro" id="intro"><?php echo $info["intro"]?></textarea>
            <label >商品详情:</label>
            <textarea id="editor_id" name="content" style="width:700px;height:300px;">
                <?php echo $info["detail"]?>
            </textarea>
            <input type="hidden" name="id" value="<?php echo $info["id"];?>">
            <button type="submit" class="btn" style="margin:20px auto;">确认修改</button>
        </fieldset>
    </form>
    <div style="clear:both;"></div>
</div>


</body>
</html>