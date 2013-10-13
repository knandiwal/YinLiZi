/*
验证表单，添加商品
 */

$(function(){
    /*
    、添加商品验证
    */
    $("#product_form").submit(function(){
        if($("#title").val() == ''){
            alert('商品名不能为空');
            return false;
        }


        if($("#product_category").val() == 0){
            alert("请选择商品类别");
            return false;
        }

        if($("#price").val() == ''){
            alert('商品价格不能为空');
            return false;
        }

        if($("#intro").val() == ''){
            alert('商品简介不能为空');
            return false;
        }


        if($("#editor_id").val() == ''){
            alert('商品详情不能为空');
            return false;
        }
    });



    /*
    添加首页图片验证
     */
    $("#rotation_form").submit(function(){
        if($("#img_name").val() == ''){
            alert("请填写图片名称");
            return false;
        }

        if($("#img_intro").val() == ''){
            alert("请填写简介");
            return false;
        }


    });

})