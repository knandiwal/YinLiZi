<div id="right">
  <fieldset>
    <legend>互动平台</legend>
    <a href="<?php echo site_url('Community/edit_forum')?>" style="margin-bottom: 20px;"><button class="btn" type="button">创建论坛</button></a>
    <form method="post" action="<?php echo site_url('Community')?>" class="form-search" style="margin-top: 20px;">
        <input type="text" name="name" class="input-medium search-query" placeholder="请输入要查询的论坛名" style="height: 30px;width:250px;">
        <button type="submit" class="btn">搜索</button>
    </form>
    <table class="table table-striped table-hover" style="font-size: 13px;margin-top: 20px;">
        <tr><th>论坛名</th><th>创建时间</th><th colspan="2" style="text-align: center">操作</th></tr>
        <?php foreach($list as $value):?>
        <tr><td><a href="<?php echo site_url('Community/read?fid='.$value['fid']);?>"><?php echo $value["name"]?></a></td><td><?php echo date("Y-m-d",strtotime($value["createtime"]));?></td><td  style="text-align: center;" ><a href="<?php echo site_url("Community/edit_forum/").'/'.$value["fid"]?>">修改</a></td><td style="text-align: center;" ><a id="del" href="<?php echo site_url("Community/delete/").'/'.$value["fid"]?>" onclick="return confirm('确定要删除？')">删除</a></td></tr>
        <?php endforeach;?>
     </table>
   </fieldset>
   <div id="page" style="text-align: center"><?php echo $this->pagination->create_links()?></div>

</div>