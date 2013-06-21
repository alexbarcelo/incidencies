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
              <?php if (Yii::app()->user->isGuest) {
                  echo "Has d'entrar al sistema abans d'utilitzar-lo";
              } else {
                  echo 'Benvingut, <a href="'. Yii::app()->request->baseUrl .'/index.php/site/logout" class="navbar-link">'.Yii::app()->user->name.'</a>';
              } ?>
            </p>
            <ul class="nav">
              <li><a id="entrada" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/login">Entrada</a></li>
              <li><a id="operativa" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/operativa">Operativa</a></li>
              <li><a id="estadistiques" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/estadistiques">Estadístiques</a></li>
              <?php if (Yii::app()->user->getState('equipDirectiu',false)) { ?>
              <li><a id="admin" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/admin">Administració</a></li>
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
    <?php if (isset($scriptFile)) {
        echo '<script src="'. Yii::app()->request->baseUrl . '/js/' . $scriptFile . '"></script>';
    } ?>

</body>
</html>
