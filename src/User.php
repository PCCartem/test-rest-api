<?php
namespace App;


class User {

    public $db = null;
    public $response = null;

    public function __construct()
    {
        if(is_null($this->db)) {
            $config = include_once "config.php";
            $this->db = new \App\Db($config);
        }
    }

    public function getUser($id)
    {
        if($user = $this->db->getUser($id)) {
            $this->response['error'] = false;
            $this->response['data'] = $user;
            return $this->response;

        }
        $this->response['error'] = true;
        $this->response['msg'] = 'Произошла ошибка, или пользователь не существует!';
        return $this->response;
    }

    public function addUser($data)
    {
        $this->validateUser($data, 'insert');

        if($this->response['error']) {
            return $this->response;
        }

        if($insertId = $this->db->addUser($data)) {
            $this->response['error'] = false;
            $this->response['data'] = $insertId;
            return $this->response;
        }

        $this->response['error'] = true;
        $this->response['msg'] = 'Произошла ошибка!';
        return $this->response;
    }


    public function updateUser($id, $data)
    {
        $this->validateUser($data, 'update');
        if($this->response['error']) {
            return $this->response;
        }
        if($updateId = $this->db->updateUser($id, $data)) {
            $this->response['error'] = false;
            $this->response['data'] = $updateId;
            return $this->response;
        }

        $this->response['error'] = true;
        $this->response['msg'] = 'Произошла ошибка!';
        return $this->response;
    }

    public function removeUser($id)
    {
        if($affectedRows = $this->db->removeUser($id)) {
            $this->response['error'] = false;
            $this->response['data'] = $affectedRows;
            return $this->response;
        }
        $this->response['error'] = true;
        $this->response['msg'] = 'Произошла ошибка!';
        return $this->response;
    }

    public function validateUser($data, $type = null)
    {
        $this->response['error'] = false;

        if(empty($data)) {
            $this->response['error'] = true;
            $this->response['msg'] = 'Отсутствуют данные.';
        } else {
            if($type === 'insert') {
                if(empty($data['f_name'])) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Отсутствует Имя пользователя.';
                }

                if(empty($data['l_name'])) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Отсутствует Фамилия пользователя.';
                }

                if(empty($data['m_name'])) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Отсутствует Отчество пользователя.';
                }

                if(empty($data['email'])) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Отсутствует E-mail.';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'E-mail указан не верно';
                }

                if(empty($data['phone'])) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Отсутствует Телефон.';
                }
                //TODO:Ну и покажу что регулярки я тоже немного знаю.
                //TODO:К сожалению не увидел в каком формате должен быть номер.

                elseif(!preg_match("/^[0-9]{10,10}+$/", $data['phone'] )){
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Телефон указан не верно';
                }

            }

            if($type === 'update' ) {
                if(!empty($data['phone']) && !preg_match("/^[0-9]{10,10}+$/", $data['phone'] )) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'Телефон указан не верно';
                }
                if(!empty($data['email']) && !preg_match("/^[0-9]{10,10}+$/", $data['email'] )) {
                    $this->response['error'] = true;
                    $this->response['msg'] = 'E-mail указан не верно';
                }
            }
        }

        if($this->response['error']) {
            return $this->response;
        } else {
            return true;
        }
    }
}