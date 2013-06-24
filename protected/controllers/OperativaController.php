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
                'actions'=>array('index','llistatProfes','filtraAlumnes'),
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
            return;
        }
    }

    /**
     * Llistat de profes, per a l'autocompletat
     */
    public function actionLlistatProfes()
    {
        $profes=Profes::model()->findAll();
        $data = [];
        foreach ($profes as $p) {
            $entry = [
                "id" =>  $p->id,
                "nom" => $p->nom];
            $data[] = $entry;
        }
        header('Content-type: application/json');
        echo CJSON::encode($data);
    }
}
