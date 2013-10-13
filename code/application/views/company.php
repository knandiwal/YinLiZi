<div id="right">
<a href="<?php echo site_url('Company/editcompany') ;?>"><input class="btn" type="button" value="编辑"></a>
<table style="width:900px;">
	<caption style="width:600px;"><?php echo $companyname; ?></caption>
	<tr><td>联系人：</td><td><?php echo $contact; ?></td></tr>
	<tr><td>电话</td><td><?php if ($tel){ foreach ($tel as $t){ echo $t."&nbsp;&nbsp;&nbsp;&nbsp;"; }}?></td></tr>
	<tr><td>简介</td><td><?php echo $description; ?></td></tr>
	<tr><td>qq号</td><td><?php echo $qq_account ?></td></tr>
	<tr><td></td><td></td></tr>
	<tr><td></td><td></td></tr>
</table>


<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<h3 class="text-center">
				h3. 这是一套可视化布局系统.
			</h3>
			<dl>
				<dt>
					Rolex
				</dt>
				<dd>
					劳力士创始人为汉斯.威尔斯多夫，1908年他在瑞士将劳力士注册为商标。
				</dd>
				<dt>
					Vacheron Constantin
				</dt>
				<dd>
					始创于1775年的江诗丹顿已有250年历史，
				</dd>
				<dd>
					是世界上历史最悠久、延续时间最长的名表之一。
				</dd>
				<dt>
					IWC
				</dt>
				<dd>
					创立于1868年的万国表有“机械表专家”之称。
				</dd>
				<dt>
					Cartier
				</dt>
				<dd>
					卡地亚拥有150多年历史，是法国珠宝金银首饰的制造名家。
				</dd>
			</dl>
			<blockquote>
				<p>
					github是一个全球化的开源社区.
				</p> <small>关键词 <cite>开源</cite></small>
			</blockquote> <address><strong>Twitter, Inc.</strong><br /> 795 Folsom Ave, Suite 600<br /> San Francisco, CA 94107<br /> <abbr title="Phone">P:</abbr> (123) 456-7890</address>
		</div>
	</div>
</div>

</div>