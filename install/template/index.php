<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title><?= $PAGETITLE ?></title>
  <link rel="stylesheet" href="template/style/style.css" />
  <link rel="stylesheet" href="template/style/admin.css" />
  <link rel="stylesheet" href="template/style/error.css" />
  <link rel="stylesheet" href="template/style/other.css" />
  <link rel="stylesheet" href="template/style/style-leftbar.css" />
  <script language='javascript' src="template/scripts/ajaxbasic.js" ></script>
  <script language='javascript'>
  <?= $PAGESCRIPT ?>
  </script>
</head>
<body onload='<?= $PAGEONLOADFUNC ?>'>
  <div id="outer_wrapper">
    <div id="wrapper">
      <div id="header">
        <h1><?= $CMSTITLE ?></h1>
      </div><!-- /header -->
      <div id="container">
        <div id="left">
           <?= $PAGEMENU ?>
        </div><!-- /left -->

        <div id="main">		
          <div id="content">
          <div id="pageheading"><?= $PAGETITLE ?></div>
          <?= $ERRORMSG ?>
          <?= $INFOMSG ?>
          <?= $WARNINGMSG ?>
          <?= $PAGECONTENT ?>
          
	
          </div>
        </div><!-- /main -->
         <!-- This is for NN6 -->
        <div class="clearing">&nbsp;</div>
      </div><!-- /container -->
      <!-- This is for NN4 -->
      <div class="clearing">&nbsp;</div>

      <div id="footer">
      <center>powered by <?=CMS_VERSION?> by <?=CMS_AUTHOR?><center>
      </div><!-- /footer -->
    </div><!-- /wrapper -->
  </div><!-- /outer_wrapper -->
</body>

</html>
