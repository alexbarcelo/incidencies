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
                    'consultaAlumne', 'llistatHores', 'creaEscrita'),
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
				Yii::app()->db->createCommand()
					->insert("escrites", array("idAlumnos" => $id));
				$idEscrites = Yii::app()->db->getLastInsertID();
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
    <p>No s'ha generat cap amonestació.</p>
</div>
EOF;
			}
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
    public function actionConsultaAlumne($id)
    {
        $data = array();
        $header = array();

		$data = Yii::app()->db->createCommand()
            ->leftJoin('escritesRel', 'faltasalumnos.id=escritesRel.idIncidencias') 
			->select(array('faltasalumnos.id', 'faltasalumnos.idProfesores', 
				'faltasalumnos.idTipoIncidencias', 'faltasalumnos.idHorasCentro', 
				'faltasalumnos.dia', 'faltasalumnos.comentarios', 
				// i afegim les del join
			    'escritesRel.idIncidencias', 'escritesRel.id as RelID'))
            ->from('faltasalumnos')
				// ens assegurem que coincideix l'alumne i que no estan
				// ja assignades a cap amonestació escrita
			->where('idAlumnos=:idalumne AND escritesRel.id IS NULL',
				array(':idalumne' => $id) )
			->order(array ('dia desc', 'idHorasCentro asc'))
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
			->select(array('faltasalumnos.id', 'faltasalumnos.idProfesores', 
				'faltasalumnos.idTipoIncidencias', 'faltasalumnos.idHorasCentro', 
				'faltasalumnos.dia', 'faltasalumnos.comentarios', 
				// i afegim les del join
			    'escritesRel.idIncidencias', 'escritesRel.id as RelID'))
            ->from('faltasalumnos')
				// ens assegurem que coincideix l'alumne i que no estan
				// ja assignades a cap amonestació escrita
			->where('idAlumnos IN (:idalumnes) AND escritesRel.id IS NULL',
				array(':idalumnes' => implode(",",$llista_alumnes) ))
			->queryAll();
		
		header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
		header('Content-type: application/json');
        print_r (CJSON::encode($data));
	}
}
