<?php

class OperativaController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        // Personalitzades per equipDirectiu com a administradors
        return array(
            array('allow',  // allow profes (per ara, els unics que fan login)
                'actions'=>array('index','llistatProfes', 'llistatTipus',
                    'filtraAlumnes', 'llistatClasses', 'consultaClasse',
                    'consultaAlumne', 'llistatHores', 'creaEscrita',
                    'consultaPDF'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin (equipDirectiu) la resta
                'actions'=>array(),
                'users'=>array('@'),
                'expression'=>'$user->getState("equipDirectiu",false)'
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Render principal, tota la resta es jQuery/AJAX/JS
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * AJAX, triggered by filtres
     */
    public function actionFiltraAlumnes()
    {
        if (!isset($_POST['query'])) {
            // AJAX, bad call, let's quit
            return;
        } else {
            $val = $_POST['query'];
            $res = Yii::app()->db->createCommand()
                ->select('id, nombre')
                ->from('alumnos')
                ->where('nombre LIKE :querynombre', array(':querynombre'=>"%$val%"))
                ->order('nombre asc')
                ->queryAll();
                //"SELECT `id`,`nombre` from `alumnos` WHERE `nombre` LIKE %:querynombre% ORDER BY `alumnos`.`nombre` ASC"
            if ( $res ) {
                foreach ($res as $a) {
                    echo '<option value="' . $a['id'] .'">'. $a['nombre'] . '</option>';
                }
            }
    }
    }

    /**
     * AJAX, triggered by generacio d'una amonestacio escrita
     *
     * Correctament s'haurien d'haver enviat una llista (space-separated)
     * de identificadors d'amonestacions i amb aquesta llista s'hauria
     * de generar una nova amonestació escrita.
     */
    public function actionCreaEscrita($id)
    {
        if (!isset($_POST['incidencies']) ) {
            // AJAX, bad call, let's complain and quit
            echo <<<EOF
<div class="alert alert-block">
    <h4>Error en la petició</h4>
  <p>Assegureu-vos que heu seguit el procediment correcte per a generar
    l'amonestació escrita.</p>
    <p>No s'ha generat cap amonestació.</p>
</div>
EOF;
      return;
        } elseif (!is_array($_POST['incidencies']) ||
            count ($_POST['incidencies']) < 1 ) {
      // Hem rebut una cosa que no és un array, o un array buit
            echo <<<EOF
<div class="alert alert-block">
    <h4>Error en el recull d'incidències</h4>
  <p>No s'ha enviat correctament el recull d'incidències.</p>
    <p>No s'ha generat cap amonestació.</p>
</div>
EOF;
    } else {
      // let's transaction it! (s'han de fer múltiples coses)
      $transact = Yii::app()->db->beginTransaction();
      try {
        // Per a fer nombres correlatius per alumne, fem una consulta
        $cmdCounter = Yii::app()->db->createCommand()
          ->select("COUNT(1)")
          ->from("escrites")
          ->where("idAlumnos=:idAlumnos",
            array(":idAlumnos" => $id));
        // Aquí guardem el nombre correlatiu de la següent escrita
        $numC = $cmdCounter->queryScalar() + 1;
        // Creem la nova amonestació escrita
        Yii::app()->db->createCommand()
          ->insert("escrites",
            array(
              "idAlumnos" => $id,
              "numCorrelatiu" => $numC,
            ));
        $idEscrites = Yii::app()->db->getLastInsertID();
        // Comprovem que ningú ha fet coses rares mentrestant a la BD
        if ($numC != $cmdCounter->queryScalar() ) {
          throw new Exception("Problema en la numeració correlativa d'amonestacions escrites");
        }
        foreach ($_POST['incidencies'] as $inc) {
          Yii::app()->db->createCommand()
            ->insert("escritesRel", array(
              "idIncidencias" => $inc,
              "idEscrites" => $idEscrites
              ));
        }
        $transact->commit();
        $PDFlink = Yii::app()->createUrl('operativa/consultaPDF', array("id" => $idEscrites));
        echo <<<EOF
<div class="alert alert-block alert-success">
    <h4>Amonestació generada amb èxit</h4>
  <p>Es notificarà a cap d'estudis per aquesta amonestació escrita.</p>
  <p>Podeu imprimir el document generat <a href="$PDFlink">aquí</a></p>
</div>
EOF;
      } catch(Exception $e) {
        $transact->rollback();
        echo <<<EOF
<div class="alert alert-block">
  <h4>Error en el recull d'incidències</h4>
  <p>No s'ha pogut introduir a la base de dades l'amonestació escrita amb
  les incidències sel·leccionades. Assegureu-vos que les incidències
  són vigents i no han estat ja assignades a una amonestació escrita.</p>
  <p>Codi intern de l'error: <p><pre>{$e->getMessage()}</pre>
  <p>No s'ha generat cap amonestació.</p>
</div>
EOF;
      }
    }
    }

    /**
     * AJAX, triggered by validació d'una amonestacio escrita
     */
    public function actionvalidaEscrita($id)
    {
      try {
        // Per a fer nombres correlatius per alumne, fem una consulta
        $c = Yii::app()->db->createCommand()
          ->update(
            "escrites",
            array("validada" => 1),
            "id=:id",
            array(":id" => $id)
          );
        if ( $c == 0 ) throw new Exception("No s'ha modificat cap línia --probableme error en l'identificador de l'amonestació");
        echo <<<EOF
<div class="alert alert-block alert-success">
    <h4>Amonestació validada amb èxit</h4>
  <p>L'amonestació s'ha marcat com a validada des de cap d'estudis</p>
</div>
EOF;
      } catch(Exception $e) {
        echo <<<EOF
<div class="alert alert-block">
  <h4>Error en el procés de validació</h4>
  <p>Codi intern de l'error: <p><pre>{$e->getMessage()}</pre>
</div>
EOF;
      }
    }

    /**
     * AJAX, triggered by generacio d'una amonestacio escrita
     *
     * Correctament s'haurien d'haver enviat una llista (space-separated)
     * de identificadors d'amonestacions i amb aquesta llista s'hauria
     * de generar una nova amonestació escrita.
     */
    public function actionEliminaEscrita($id)
    {
      // let's transaction it! (s'han de fer múltiples coses)
      $transact = Yii::app()->db->beginTransaction();
      try {
        /*
         * NOTA: si en una situació s'elimina, per exemple, l'amonestació
         * amb numeració correlativa #3 havent-hi números correlatius superiors
         * (p.ex. la #4) els números no s'actualitzaran. Això vol dir que
         * quedarà un forat a la correlació d'escrites per a un alumne
         * determinat. I, segurament, la següent amonestació tindrà
         * una mala numeració. It's not a bug, it's a feature!
         */

        // eliminem les relacions
        Yii::app()->db->createCommand()
          ->delete(
            "escritesRel",
            "idEscrites=:id",
            array(
              ":id" => $id,
            )
          );

        // i també eliminem l'amonestació pròpiament
        Yii::app()->db->createCommand()
          ->delete(
            "escrites",
            "id=:id",
            array(
              ":id" => $id,
            )
          );

        $transact->commit();
        echo <<<EOF
<div class="alert alert-block alert-success">
    <h4>Amonestació eliminada amb èxit</h4>
  <p>Les incidències associades estan disponibles per a ser assignades
  a noves amonestacion escrites</p>
</div>
EOF;
      } catch(Exception $e) {
        $transact->rollback();
        echo <<<EOF
<div class="alert alert-block">
  <h4>Error en el procés d'eliminació</h4>
  <p>No s'ha pogut eliminar la informació associada de la base de dades.
  Assegureu-vos que no s'han fet modificacions simultàniament.</p>
  <p>Codi intern de l'error: <p><pre>{$e->getMessage()}</pre>
</div>
EOF;
      }
    }

    /**
     * AJAX, triggered at start.
     *
     * Carrega la llista de classes disponibles a l'institut, i les
     * deixa a la comboBox de sel·lecció (filtratge d'alumnes)
     */
    public function actionLlistatClasses()
    {
        // línia buida, placeholder
        echo '<option value="-1">Escollir...</option>';

        $classes = Yii::app()->db->createCommand()
                ->select('descripcion, grupoGestion')
                ->from('grupos')
                ->order('descripcion asc')
                ->queryAll();

        foreach ($classes as $c) {
            echo '<option value="' . $c['grupoGestion'] .'">' . $c['descripcion'] .'</option>';
        }
    }

    /*
     * Obtencio d'una llista de profes, per a guardar de forma estatica
     */
    public function actionLlistatProfes()
    {
        $data= Yii::app()->db->createCommand()
                ->select('id, nombre')
                ->from('profesores')
                ->queryAll();
        header('Content-type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        echo CJSON::encode($data);
    }

    /*
     * Obtencio d'una llista de les hores de classe del centre
     */
    public function actionLlistatHores()
    {
        $data= Yii::app()->db->createCommand()
            ->select('id, inicio')
            ->from('horascentro')
            ->queryAll();
        header('Content-type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        echo CJSON::encode($data);
    }

    /**
     * Llistat de tipus, per a ús intern de JavaScript o altres
     */
    public function actionLlistatTipus()
    {
        $data= Yii::app()->db->createCommand()
            ->select('id, simbolo, descripcion, peso, tipo')
            ->from('tipoincidencias')
            ->where("tipo='faltas'")
            ->queryAll();
        header('Content-type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        echo CJSON::encode($data);
    }

    /**
     * Funcio per a obtenir informacio d'un alumne
     *
     * Es retorna un JSON que el Javascript triturarà addientment.
     * En funció del paràmetre ID que es passarà a la consulta.
     */
    public function actionConsultaAlumne($id) {
      $data = array (
        "incidencias" => array(),
        "escrites" => array(),
      );

      $data["incidencias"] = Yii::app()->db->createCommand()
        ->leftJoin('escritesRel', 'faltasalumnos.id=escritesRel.idIncidencias')
        ->select(array('faltasalumnos.id', 'faltasalumnos.idProfesores',
          'faltasalumnos.idTipoIncidencias', 'faltasalumnos.idHorasCentro',
          'faltasalumnos.dia', 'faltasalumnos.comentarios',
          // i afegim el que volem del join: la escrita associada (si s'aplica)
          'escritesRel.idEscrites'))
        ->from('faltasalumnos')
          // ens assegurem que coincideix l'alumne i que no estan
          // ja assignades a cap amonestació escrita
        ->where('idAlumnos=:idalumne',
          array(':idalumne' => $id) )
        ->order(array ('dia desc', 'idHorasCentro asc'))
        ->queryAll();

      $data["escrites"] = Yii::app()->db->createCommand()
        ->select(array('id', 'idAlumnos', 'validada', 'numCorrelatiu'))
        ->from('escrites')
        ->where('idAlumnos=:idalumne',
          array(':idalumne' => $id) )
        ->queryAll();

      header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
      header('Pragma: no-cache'); // HTTP 1.0.
      header('Expires: 0'); // Proxies.
      header('Content-type: application/json');
      print_r (CJSON::encode($data));
    }

  /**
     * Funcio per a obtenir informacio d'un alumne
     *
     * Es retorna un JSON que el Javascript triturarà addientment.
     * En funció del paràmetre ID que es passarà a la consulta.
     */
  public function actionConsultaClasse($id) {
    $data = array (
      "alumnos" => array(),
      "incidencias" => array(),
      "escrites" => array(),
    );

    $llista_alumnes = array();

    $data["alumnos"] = Yii::app()->db->createCommand()
      // Join entre alumnos i gruposalumnos
            ->join('alumnos', 'gruposalumno.idAlumnos=alumnos.id')
      ->select(array('alumnos.nombre', 'alumnos.id'))
            ->from('gruposalumno')
        // condició: que la classe sigui la que volem
      ->where('grupoGestion=:grupoGestion ',
        array(':grupoGestion' => $id) )
      ->order('alumnos.nombre asc')
      ->queryAll();

    foreach ($data["alumnos"] as $alumne) {
      $llista_alumnes[] = $alumne["id"];
    }

    $data["incidencias"] = Yii::app()->db->createCommand()
            ->leftJoin('escritesRel', 'faltasalumnos.id=escritesRel.idIncidencias')
      ->select(array('faltasalumnos.idTipoIncidencias', 'faltasalumnos.idAlumnos'))
            ->from('faltasalumnos')
        // ens assegurem que coincideix l'alumne i que no estan
        // ja assignades a cap amonestació escrita
      ->where('idAlumnos IN (' + implode(",",$llista_alumnes) + ')')
      ->andWhere ('escritesRel.id IS NULL')
      ->queryAll();

    $data["escrites"] = Yii::app()->db->createCommand()
      ->select(array('idAlumnos', 'validada'))
            ->from('escrites')
      ->where('idAlumnos IN (' + implode(",",$llista_alumnes) + ')')
      ->queryAll();

    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
    header('Content-type: application/json');
        print_r (CJSON::encode($data));
  }

  /**
     * Funcio per a obtenir la llista d'amonestacions escrites
     * pendents de validar.
     *
     * Es retorna un JSON que el Javascript triturarà addientment.
     * Aquest inclourà la llista d'escrites i també la llista
     * d'incidències associades.
     */
  public function actionEscritesPendents() {
    $data = array (
      "incidencias" => array(),
      "escrites" => array(),
    );

    $llista_escrites = array();

    $data["escrites"] = Yii::app()->db->createCommand()
      // Join entre alumnos i gruposalumnos
      ->join('alumnos', 'escrites.idAlumnos=alumnos.id')
      ->select(array('alumnos.nombre', 'escrites.idAlumnos',
        'escrites.numCorrelatiu', 'escrites.id'))
      ->from('escrites')
        // condició: que la classe sigui la que volem
      ->where('validada=0')
      ->order(array ('alumnos.nombre asc', 'escrites.numCorrelatiu'))
      ->queryAll();

    foreach ($data["escrites"] as $escrita) {
      $llista_escrites[] = $escrita["id"];
    }

    $data["incidencias"] = Yii::app()->db->createCommand()
      ->join('faltasalumnos', 'faltasalumnos.id=escritesRel.idIncidencias')
      ->select(array('faltasalumnos.idTipoIncidencias', 'escritesRel.idEscrites'))
      ->from('escritesRel')
        // ens assegurem que són incidencias d'amonestacions escrites no validades
      ->where('idEscrites IN (' + implode(",",$llista_escrites) + ')')
      ->queryAll();

    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
    header('Pragma: no-cache'); // HTTP 1.0.
    header('Expires: 0'); // Proxies.
    header('Content-type: application/json');
    print_r (CJSON::encode($data));
  }

  public function actionConsultaPDF($id) {
    $escrita = Yii::app()->db->createCommand()
      // Seleccionar de la taula d'amonestacions escrites
      ->select(array('alumnos.nombre', 'escrites.idAlumnos','escrites.numCorrelatiu'))
      ->from('escrites')
      // obtenint el nom de la taula alumnos
      ->join('alumnos', 'alumnos.id=escrites.idAlumnos')
      ->where("escrites.id=:id" , array('id' => $id))
      ->queryRow();
    $pdftitle = $escrita["nombre"] . ' - #' . $escrita["numCorrelatiu"];
    $pdfname = $escrita["nombre"] . '_' . $escrita["numCorrelatiu"];

    // voldrem filtrar i obtenir els tipus
    $raw_tipus = Yii::app()->db->createCommand()
      ->select('id, simbolo, descripcion, peso, tipo')
      ->from('tipoincidencias')
      ->where("tipo='faltas'")
      ->queryAll();
    $tipus = array();
    foreach ($raw_tipus as $tipustype) {
      $tipus[$tipustype["id"]] = $tipustype["simbolo"];
    }

    // escriure correctament la hora del centre
    $raw_hores = Yii::app()->db->createCommand()
      ->select('id, inicio')
      ->from('horascentro')
      ->queryAll();
    $hores = array();
    foreach ($raw_hores as $horestype) {
      $hores[$horestype["id"]] = $horestype["inicio"];
    }

    // saber la classe
    $classe = Yii::app()->db->createCommand()
      ->select('grupos.descripcion')
      ->from('gruposalumno')
      ->join('grupos', 'grupos.grupoGestion=gruposalumno.grupoGestion')
      ->where("idAlumnos=:id", array('id' => $escrita["idAlumnos"]))
      ->queryRow();

    // llistat d'incidencies associades a l'escrita
    $incidencies = Yii::app()->db->createCommand()
      // seleccionem de la relació d'escrites
      ->select(array('faltasalumnos.idTipoIncidencias', 'faltasalumnos.idHorasCentro',
          'faltasalumnos.dia', 'faltasalumnos.comentarios'))
      ->from('escritesRel')
      ->join('faltasalumnos', 'escritesRel.idIncidencias=faltasalumnos.id')
      ->where('escritesRel.idEscrites=:id', array('id' => $id))
      ->order(array ('faltasalumnos.dia asc',
        'faltasalumnos.idHorasCentro asc'))
      ->queryAll();

    $style = "<style>p {text-align: justify;} .punts {color: 0xCCCCCC; font-family: monospace;}</style>";

    // processem les dades en funció de si és escrita directa o acumulació
    $escritaDirecta = false;
    $textAmonestacio = "";
    if (count($incidencies) == 0) {
      throw new CHttpException(404);
    } elseif (count($incidencies) > 1) {
      $escritaDirecta = false;
      $totalF = 0;
      $totalR = 0;
      $totalAm = 0;
      $taulaF = "<h1>Faltes</h1>" .
        "<table><thead><tr><th>Dia</th><th>Hora</th></tr></thead><tbody>";
      $taulaR = "<h1>Retards</h1>" .
        "<table><thead><tr><th>Dia</th><th>Hora</th></tr></thead><tbody>";
      $taulaAm = "<h1>Amonestacions Orals</h1>" .
        "<table><thead><tr><th>Dia</th><th>Hora</th><th>Comentaris</th></tr></thead><tbody>";
      foreach ($incidencies as $inc) {
        if ($inc["idTipoIncidencias"]>0 && $tipus[$inc["idTipoIncidencias"]] == "AM") {
          $totalAm++;
          $taulaAm .= "<tr>" .
            "<td>" . $inc["dia"] . "</td>" .
            "<td>" . $hores[$inc["idHorasCentro"]] . "</td>" .
            "<td>" . $inc["comentarios"] . "</td>" .
            "</tr>";
        } else if ($inc["idTipoIncidencias"]>0 && $tipus[$inc["idTipoIncidencias"]] == "FA") {
          $totalF++;
          $taulaF .= "<tr>" .
            "<td>" . $inc["dia"] . "</td>" .
            "<td>" . $hores[$inc["idHorasCentro"]] . "</td>" .
            "</tr>";
        } else if ($inc["idTipoIncidencias"]>0 && $tipus[$inc["idTipoIncidencias"]] == "RE") {
          $totalR++;
          $taulaR .= "<tr>" .
            "<td>" . $inc["dia"] . "</td>" .
            "<td>" . $hores[$inc["idHorasCentro"]] . "</td>" .
            "</tr>";
        }
      }
      $textAmonestacio = $style . "<p>Acumulació de les següents incidències:</p>";
      $textAmonestacio .= "<ul>";
      if ($totalF > 0) {
        $textAmonestacio .= "<li><strong>$totalF</strong> Faltes injustificades</li>";
      } else {
        $taulaF = "";
      }
      if ($totalR > 0) {
        $textAmonestacio .= "<li><strong>$totalR</strong> Retards</li>";
      } else {
        $taulaR = "";
      }
      if ($totalAm > 0) {
        $textAmonestacio .= "<li><strong>$totalAm</strong> Amonestacions orals</li>";
      } else {
        $taulaAm = "";
      }
      $textAmonestacio .= "</ul>";
    } else {
      $escritaDirecta = true;
      $inc = $incidencies[0];
      $textAmonestacio = $style . "<p>En data " . $inc["dia"] .
        " a l'hora " . $hores[$inc["idHorasCentro"]] . "</p>" .
        "<p>Motiu:<br><strong>" . $inc["comentarios"] . "</strong></p>";
    }

    // Per a tenir la data en català sense haver de barallar-nos amb locales extranys
    $dies=array("diumenge","dilluns","dimarts","dimecres","dijous","divendres","dissabte");

    $mesos=array("de gener","de febrer","de març","d'abril","de maig","de juny",
      "de juliol", "d'agost","de setembre","d'octubre","de novembre","de desembre");

    $today = "".$dies[date('w')]." ".date('d')." ".$mesos[date('n')-1]." de ".date('Y');

    $textInici = $style . <<<EOT
<p></p><p>Sr./Sra. </p>

<p>El seu fill/a <strong>{$escrita["nombre"]}</strong>
del curs <strong>{$classe["descripcion"]}</strong> ha incorregut en la següent conducta
contrària a les normes de convivència del centre:</p>
EOT;

    $textFinal = $style . <<<EOT
<p style="text-align: justify;">Per aquest motiu, d’acord amb allò que s’estableix en  la Llei 12/2009,
de 10 de juliol (Llei d’Educació Catalana) i el Decret 102/2010, de 3 d’agost
(Decret d’autonomia de centres)  li ha estat imposada, com a mesura correctora,
la present amonestació escrita. Així mateix, els informen que la repetició
de conductes contràries a les normes de convivència podrà ser considerada
una falta greument perjudicial per a la convivència del centre, que podria
motivar la iniciació d’un expedient disciplinari.</p>

<p>Els informem que disposen d’un termini de 2 dies per reclamar contra
aquesta mesura correctora davant la Direcció del centre.</p>

<p>Els preguem que retornin signada aquesta comunicació i sol·licitin
una entrevista amb el tutor/a.</p>

<p>Barcelona, $today</p>
EOT;

  $textPareMare = $style . <<<EOT
<p>El Sr./Sra. <span class="punts">....................................</span>
amb DNI núm <span class="punts">............</span><br>
pare/mare de l’alumne/a <strong>{$escrita["nombre"]}</strong>
del curs <strong>{$classe["descripcion"]}</strong>
he rebut notificació de la present amonestació escrita.</p>

<p>Barcelona, <span class="punts">...................</span> de <span class="punts">...............</span> de <span class="punts">...........</span><br>
&nbsp;&nbsp;&nbsp;&nbsp;Signatura</p>
EOT;

    $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf',
                            'P', 'cm', 'A4', true, 'UTF-8');
    $pdf->setPageOrientation('P',true,'1');
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor("INS Ernest Lluch");
    $pdf->SetMargins(2,1.5,1);
    $pdf->SetTitle($pdftitle);
    $pdf->SetSubject("Notificació d'amonestació escrita");
    $pdf->SetKeywords("institut, Ernest Lluch, notificació, amonestació");
    $pdf->setPrintHeader(false);
    // default footer print and data values
    $pdf->setPrintFooter(false);

    $pdf->AddPage();
    $pdf->Image("../incidencies/escrita/consorci.jpg",1,1,'',2.4);
    $pdf->Ln(2.8);
    $pdf->SetFont("times", "B", 18);
    $pdf->Cell(0,1,"#".$escrita["numCorrelatiu"]." - Notificació d'amonestació escrita",1,1,'C');

    $pdf->SetFont("times", "", 11);
    $pdf->writeHTML($textInici);

    $pdf->Ln(0.5);
    // problema que la funció writeHTMLCell no deixa tocar $maxh
    // això, segons la documentació de la API, NO es pot fer
    $h = 4;
    $pdf->MultiCell(0,$h,$textAmonestacio,1,'J',false,1,'','',true,
      0, /*!!*/ true /*!!*/, true, $h, 'T', false);
    $pdf->Ln(0.5);

    $pdf->writeHTML($textFinal, true,false,true);

    // Les tres signatures
    $h = 3;
    $pdf->MultiCell(5,$h,"Tutor/a"             ,0,'J',false,0,2,'',true,0,false,true,$h,'B',false);
    $pdf->MultiCell(6,$h,"Direcció"            ,0,'J',false,0,'','',true,0,false,true,$h,'B',false);
    $pdf->MultiCell(3.7,$h,"Prefectura d'estudis",0,'J',false,0,'','',true,0,false,true,$h,'B',false);
    $pdf->Image("../incidencies/escrita/signaturabrosa.gif", 6,19.5,0,1.7);

    $pdf->Ln($h+1);
    $pdf->Line(1,22.5,20,22.5,
      array(
        "dash" => "5,2",
      ));
    $pdf->writeHTML($textPareMare, true,false,true);

    // Algo semblant a un peu, només per la primera pàgina
    $pdf->Image("../incidencies/escrita/signaturalluch.jpg", 1, 26.5,0,2);
    $pdf->SetFont("times", "", 9);
    $pdf->MultiCell(0,0.5,
      "Carrer Diputació, 15  08015 Barcelona  Tel. 93 426 06 76  Fax 93 426 39 10  www.insernestlluch.cat",
      0,'C',false,0, 1,28);

    // Si cal posar la llista detallada, la posem
    if (!$escritaDirecta) {
      $pdf->AddPage();
      $pdf->writeHTML("". $taulaAm . $taulaF . $taulaR, true,false,true);
    }

    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
    header('Pragma: no-cache'); // HTTP 1.0.
    header('Expires: 0'); // Proxies.
    $pdf->Output($pdfname, "I");
  }


  public function actionTestPDF() {

    $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf',
                            'P', 'cm', 'A4', true, 'UTF-8');
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor("Nicola Asuni");
    $pdf->SetTitle("TCPDF Example 002");
    $pdf->SetSubject("TCPDF Tutorial");
    $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->SetFont("times", "BI", 20);
    $pdf->Cell(0,10,"Example 002",1,1,'C');
    $pdf->Output("example_002.pdf", "I");
  }
}
