<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="IES Ernest Lluch">
    <meta name="author" content="Alex Barcelo">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
	<!-- Le styles -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Incidències</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
		      <?php if (isset($usuari)) {
				  echo 'Benvingut, <a href="#" class="navbar-link">'.$usuari.'</a>';
		      } else {
				  echo "Has de fer login per utilitzar l'aplicació";
		      } ?>
            </p>
            <ul class="nav">
              <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/login.php">Entrada</a></li>
              <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/operativa.php">Operativa</a></li>
              <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/estadistiques.php">Estadístiques</a></li>
              <?php if (isset($admin)) { ?>
              <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/admin.php">Administració</a></li>
              <?php } ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
	    <?php echo $content; ?>
      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; Alex Barcelo 2013</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
</body>
</html>
