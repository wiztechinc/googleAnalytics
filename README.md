# googleAnalytics
Gtags mod for Zen Cart

This is a very simple mod that removes old google analytics mods from the database and the files. The resulting insertion in the html_header.php prints out the required gtags code and nothing more. 

insert html_header code just before the body tag: 	  <?php 
	 if	 (GA_ENABLED == 'True') {
	  { 
	  ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo GOOGLE_ANALYTICS_UACCT ?>"></script>
	 
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo GOOGLE_ANALYTICS_UACCT ?>');
</script>
	  <?php }
?>
