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
                    'consulta'),
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
            $criteria=new CDbCriteria;
            $criteria->select = '`id`, `nom`, `cognom`';
            // Deleguem al JS que ens doni la query adequada
            $criteria->condition = $_POST['query'];
            $criteria->limit = 100;
            if (isset($_POST['offset'])) {
                $criteria->offset = $_POST['offset'];
            } // per defecte, comencem a offset 0

            // Cerquem
            $alumnes=Alumnes::model()->findAll($criteria);

            foreach ($alumnes as $a) {
                echo '<option value="' . $a->id .'">'. $a->cognom.', '.$a->nom . '</option>';
            }
        }
    }

    /**
     * AJAX function
     *
     * Es crida via jQuery quan hi ha una intenció (per part de l'usuari)
     * de guardar una incidència.
     *
     * La resposta és codi HTML
     */
    public function actionNovaIncidencia()
    {
        $amonestacio = new Amonestacions();
        $amonestacio->attributes = $_POST;
        if ($amonestacio->save()) {
            $id = $amonestacio->id;
            //$hashId = friendlyHash($id);
            echo <<< EOF
<div class="alert alert-success alert-block">
  <h4>Procés completat satisfactòriament</h4>
    <p>L'amonestació s'ha creat satisfactòriament. El seu identificador
    per a futures referències és <strong>#$id</strong></p>

    <p>Si desitja realitzar-hi modificacions, consulteu amb l'equip directiu</p>
</div>
EOF;
        } else {
            $errors = print_r($amonestacio->getErrors(), true);
            echo <<< EOF
<div class="alert alert-error alert-block">
  <h4>Hi ha hagut un error</h4>
    <p>Hi ha hagut un error en la creació de la incidència. Comproveu si s'ha creat
    satisfactòriament, o torneu-la a crear.</p>

    <p>En cas de que el problema persisteixi, consulteu personal tècnic.</p>
</div>
<div class="alert alert-block">
  <h4>Informació tècnica</h4>
    <p>Guardeu la següent informació en un document de text:</p>

<code>$errors</code>

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
        $classes=Classes::model()->findAll();
        $data = array();
        foreach ($classes as $c) {
            echo '<option value="' . $c->id .'">' . $c->descr .'</option>';
        }
    }

    /**
     * Llistat de profes, per a l'autocompletat
     */
    public function actionLlistatProfes()
    {
        $profes=Profes::model()->findAll();
        $data = array();
        foreach ($profes as $p) {
            $entry = array(
                "id" =>  $p->id,
                "nom" => $p->nom
            );
            $data[] = $entry;
        }
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
        $tipus=Tipus::model()->findAll();
        $data = array();
        foreach ($tipus as $t) {
            $entry = array(
                "id"        => $t->id,
                "descr"     => $t->descr,
                "longDescr" => $t->longDescr,
                "abrev"     => $t->abrev
            );
            $data[] = $entry;
        }
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
    public function actionConsulta($tipus, $id=null)
    {
        if (!isset($id)) {
            $id = Yii::app()->user->getState('uid',0);
        }
        switch ($tipus) {
            case "meves":
                $data = Amonestacions::model()->with( array(
                    'alumne0' => array (
                        'select' => array('nom','cognom'),
                    ),
                ))->findAll( array(
                    'condition' => 'ennomde=:ennomde',
                    'params'    => array(":ennomde"=>$id),
                ));
                break;
            case "alumne":
                $query = Amonestacions::model()->with(array('tipus0','profe0','profe1'))
                ->findAll( array(
                    'condition' => 'alumne=:alumne',
                    'params'    => array(":alumne"=>$id),
                ));
                $data = array();
                foreach ($query as $row) {
                    $data[] = array_merge (
                        $row->getAttributes() ,
                        array(
                            'nomProfe' => $row->profe0->getAttribute('nom') ,
                            'ennomdeProfe' => $row->profe1->getAttribute('nom'),
                            'abrevTipus' => $row->tipus0->getAttribute('abrev'),
                        )
                    );
                }
                break;
            case "classe":
                $data = Amonestacions::model()->with( array(
                    'alumne0' => array(
                        'condition'=>"classe=:id",
                        'params'   => array(":id"=>$id),
                    ),
                ))->findAll();
                break;
            case "profe":
                $data = Amonestacions::model()->findAll( array(
                    'condition' => 'ennomde=:ennomde',
                    'params'    => array(":ennomde"=>$id),
                ));
                break;
            case "novestutor":
                $data = Amonestacions::model()->with( array(
                    'alumne0' => array(
                        'condition'=>"classe=:id",
                        'params'   => array(":id"=>$id),
                    ),
                ))->findAll(array(
                    'condition' => "jaVista=0",
                ));
                break;

            /**
             * Encara s'ha d'acabar
             *
            case "acumulades":
                $data = Amonestacions::model()->findAll( array(
                    'condition' => 'assignadaEscrita=0',
                ));
                break;
             */
        }

        header('Content-type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.
        print_r (CJSON::encode($data));
    }
}
