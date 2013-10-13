<?php
if(!$this->session->userdata("user_id")){
    redirect("User/return_login");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>银离子后台管理系统</title>
    <script language="javascript" src="<?php echo base_url()?>js/plug/jquery.js"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>style/plug/bootstrap.min.css"/>
  	<script charset="utf-8" src="<?php echo base_url();?>editor/kindeditor.js"></script>
    <script charset="utf-8" src="<?php echo base_url();?>editor/lang/zh_CN.js"></script>
    <script language="javascript" src="<?php echo base_url()?>js/plug/message.js"></script>
    <script charset="utf-8" src="<?php echo base_url();?>js/plug/bootstrap.min.js"></script>
</head>
<style type="text/css">
    *{
        padding: 0;
        margin: 0;
    }


    #topDiv{
        width:100%;
        height:56px;
        background:url(<?php echo base_url()?>/images/head-bg.jpg) repeat-x;
        position:absolute;
        top:0px;
        overflow:hidden;
        float: left;
    }

    #lhead{
        background:url(<?php echo base_url()?>/images/left-head.jpg) left top no-repeat;
        height:25px;
        font-size:14px;
        color:#FF9933;
        text-align:center;
        line-height:25px;
    }
    #left{
        padding-top: 56px;;
        width:150px;
        height:550px;
        background:url(<?php echo base_url()?>/images/slide.jpg) repeat-y;
        float: left;
    }
    #left ul{
        list-style:none;
        font-size:12px;
        margin-top: 56px;
        margin-left: 5px;

    }
    #left ul li a{
        display:block;
        width:140px;
        height:25px;
        line-height:25px;
        background:url(<?php echo base_url()?>/images/menu-bg.jpg) repeat-x;
        color:#FFFFFF;
        direction:none;
        text-align:center;
        border-bottom:1px #000066 solid;
        border:1px #06597D solid;
    }
    #left ul li a:hover{

        background:url(<?php echo base_url()?>/images/menu-bg.jpg) 0px 25px;
        color:#99FFCC;
        direction:none;
        text-align:center;
        border-bottom:1px #000066 solid;
    }
        #right{
            height: 550px;
            border: 1px solid #333;
            padding-left: 20px;
            width:1000px;
            position: absolute;
            top:60px;
            left: 150px;
            overflow: auto;
        }
     .flashmessage{
		margin: 10px auto;
		width:300px;
		border:2px solid green;
		text-align:center;
		color:blue;
	}

</style>


<div id="topDiv">
	<div id="flashMessage" >
		<div id="addmessage"><?php echo $this->session->flashdata('flashmessage') ;?></div>
	</div>
</div>
    <div id="left">
        <div id="lhead">管理菜单</div>
        <ul>
            <li ><a href="<?php echo site_url('Index'); ?>">首页</a></li>
            <li ><a href="<?php echo site_url('Product'); ?>">商品管理</a></li>
            <li ><a href="<?php echo site_url('Product_category'); ?>">商品分类管理</a></li>
            <li ><a href="<?php echo site_url('Article'); ?>">文章管理</a></li>
            <li ><a href="<?php echo site_url('Article_category'); ?>">文章分类管理</a></li>
<!--            <li ><a href="--><?php //echo site_url('Rotation'); ?><!--">首页图片管理</a></li>-->
            <li ><a href="<?php echo site_url('User');?>">用户管理</a></li>
            <li ><a href="<?php echo site_url('Faq');?>">问答平台</a></li>
            <li ><a href="<?php echo site_url('Community');?>">互动平台</a></li>
            <li ><a href="<?php echo site_url("User/admin_logout")?>">退出管理</a></li>
        </ul>
    </div>
</html>
<script>
 messagefadeout();
</script>