<?php
namespace App;

class Db {

    protected static $pdo = null;

    public function __construct($config)
    {
        if(is_null(self::$pdo)) {
            self::$pdo = new \Slim\PDO\Database($config['dsn'], $config['usr'], $config['pwd']);
        }
    }

    public function getUser($id)
    {
        $selectStatement = self::$pdo->select()
            ->from('user')
            ->where('id', '=', $id);

        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        return $data;
    }

    public function addUser($data)
    {
        $insertStatement = self::$pdo->insert(array('f_name', 'l_name', 'm_name', 'phone', 'email'))
            ->into('user')
            ->values(array($data['f_name'], $data['l_name'], $data['m_name'], $data['phone'], $data['email']));
        $insertId = $insertStatement->execute(false);
        if($insertId) {
            return ['id' => self::$pdo->lastInsertId()];
        }

    }


    public function updateUser($id, $data)
    {
        $update = [];


        if(!empty($data['f_name'])) {
            $update['f_name'] = $data['f_name'];
        }
        if(!empty($data['l_name'])) {
            $update['l_name'] = $data['l_name'];
        }
        if(!empty($data['m_name'])) {
            $update['m_name'] = $data['m_name'];
        }
        if(!empty($data['phone'])) {
            $update['phone'] = $data['phone'];
        }
        if(!empty($data['email'])) {
            $update['email'] = $data['email'];
        }


        $updateStatement = self::$pdo->update($update)
            ->table('user')
            ->where('id', '=', $id);

        $affectedRows = $updateStatement->execute();
        return ['updatedRows' => $affectedRows];
    }

    public function removeUser($id)
    {
        $deleteStatement = self::$pdo->delete()
            ->from('user')
            ->where('id', '=', $id);

        $affectedRows = $deleteStatement->execute();
        return ['deletedRows' => $affectedRows];
    }

}