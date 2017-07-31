<?php

class WWW_model_auth extends WWW_Factory {

    private $id = 0;
    private $name = '';
    private $email = '';
    private $password = '';
    private $status = 0;
    private $type = 0;
    private $result = array('success'=>false);

    public function setID($id){ $this->id=$id; }
    public function setName($name){ $this->name=$name;}
    public function setEmail($email){ $this->email=$email;}
    public function setPassword($password){ $this->password=$password;}
    public function setStatus($status){ $this->status=$status;}
    public function setType($type){ $this->type=$type;}

    public function getID(){ return $this->id; }
    public function getName(){ return $this->name;}
    public function getEmail(){ return $this->email;}
    public function getPassword(){ return $this->password;}
    public function getStatus(){ return $this->status;}
    public function getType(){ return $this->type;}

    public function login(){
        $user = $this->dbSingle('SELECT * FROM user WHERE email=? AND password=?;',
                array($this->getEmail(), $this->getPassword()));
        if($user){
            $this->result['success'] = true;
            $this->result['data'] = array('id'=>$user['id'],'name'=>$user['name'],'type'=>$user['type'],'status'=>$user['status']);
            return $this->result;
        }else{
            return $this->result;
        }
    }

    public function register(){
        $user = $this->dbCommand('INSERT INTO user(name,email,password,status,type) VALUES(?,?,?,?,?);',
                array($this->getName(), $this->getEmail(), $this->getPassword(), $this->getStatus(), $this->getType()));
        if($user){
            $this->result['success'] = true;
            $this->result['data'] = array('id'=>$this->dbLastId(),'name'=>$this->getName(),'type'=>$this->getType(),'status'=>$this->getStatus());
            return $this->result;
        }else{
            return $this->result;
        }
    }

}