<div id="right">
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<h3 class="text-center" style="margin-top: 30px;">
				<?php echo $companyname; ?>
			</h3>
			<a style='float:right;margin-right: 30px;margin-bottom: 20px;' href="<?php echo site_url('Company/editcompany') ;?>"><input class="btn" type="button" value="编辑"></a>
        </div>
			<div style="clear:both;"></div>
			
			<p style="text-indent: 30px;line-height: 25px;">
				<?php echo $description; ?>
			</p> 
			 <address>
			 <abbr title="address">地址:</abbr>
			 <?php echo $address; ?><br /> 
			 <abbr title="Phone">电话:</abbr>
			 <?php 
			 	if ($tel){
                    echo "<ul style='padding-left: 15px;'>";
			 		foreach ($tel as $v){
			 			echo '<li>'.$v.'</li>';
			 		}
                    echo "</ul>";
			 	}
			 
			 ?>
			 </address>
		</div>
	</div>
</div>

</div>