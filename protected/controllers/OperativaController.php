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
                    'filtraAlumnes', 'llistatClasses', 'novaIncidencia',
                    'consulta', 'llistatHores', 'creaEscrita'),
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
    public function actionCreaEscrita()
    {
        if (!isset($_POST['incidencies'])) {
            // AJAX, bad call, let's quit
            return;
        } else {
			echo var_dump($_POST['incidencies']);
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
     * Funcio generica per a obtenir informacio
     *
     * Es retorna un JSON que el Javascript triturarà addientment.
     * En funció del paràmetre ID que es passarà a la consulta.
     */
    public function actionConsultaAlumne($id)
    {
        $data = array();
        $header = array();

		$data = Yii::app()->db->createCommand()
			->select('id, idProfesores, idTipoIncidencias, idHorasCentro, dia, comentarios')
            ->from('faltasalumnos')
			->where('idAlumnos=:idalumne', array(':idalumne' => $id) )
			->order(array ('dia desc', 'idHorasCentro asc'))
			->queryAll();

        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
		header('Content-type: application/json');
        print_r (CJSON::encode($data));
    }
}
