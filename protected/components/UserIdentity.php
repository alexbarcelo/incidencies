<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    private $_name;

    /**
     * Authenticates a user.
     */
    public function authenticate()
    {
        $conn = new CDbConnection("mysql:host=localhost;dbname=config","root","");
        $cmd  = $conn->createCommand("SELECT * from `usuarios` WHERE `usuario`=:idusername AND `clave`=:clave ORDER BY `usuarios`.`id` DESC");
        $cmd->bindParam(":idusername",$this->username,PDO::PARAM_STR);
        $cmd->bindParam(":clave",$this->password,PDO::PARAM_STR);
        $row  = $cmd->queryRow();

        if (!$row)
          $this->errorCode=self::ERROR_USERNAME_INVALID;
        else {
          // Okay! Check Nom i equipDirectiu
          $this->errorCode=self::ERROR_NONE;
          $this->_name = $row["nombre"];
          $this->setState("uid", $row["idProfesores"]);

          /*
           * Detecció ``a mà'' de l'equip directiu (es podria millorar)
           *
           * Per ara, detectem només la Carretero
           */
          if ($row["usuario"] === '13') {
            $this->setState("equipDirectiu", true);
          }
        }

        return !$this->errorCode;
    }

    public function getName()
    {
        return $this->_name;
    }
}
