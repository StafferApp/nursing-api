<?php

class WWW_controller_auth extends WWW_Factory {
    
    private $returnData = array('success'=>false);
    
    public function login($input){
        $translation = $this->getTranslations();
        if(!isset($input['email']) || !isset($input['password'])){
            $this->returnData['error'] = $translation['incomplete-data'];
        }else if(empty($input['email']) || empty($input['password'])){
            $this->returnData['error'] = $translation['missing-data'];
        }else{
            $auth = $this->getModel('auth');
            $auth->setEmail(strtolower(trim($input['email'])));
            $auth->setPassword(sha1(trim($input['password'])));
            $result = $auth->login();
            if(boolval($result['success'])){
                $this->returnData['success'] = true;
                $this->returnData['data'] = $result['data'];
            }else{
                $this->returnData['error'] = $translation['invalid-credentials'];
            }
        }
        return $this->returnData;
    }
    
    public function register($input){
        $translation = $this->getTranslations();
        if(!isset($input['name']) || !isset($input['email']) || !isset($input['password']) || !isset($input['type'])){
            $this->returnData['error'] = $translation['incomplete-data'];
        }else if(empty($input['name']) || empty($input['email']) || empty($input['password']) || empty($input['type'])){
            $this->returnData['error'] = $translation['missing-data'];
        }else if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL)){
            $this->returnData['error'] = $translation['invalid-email'];
        }else{
            $auth = $this->getModel('auth');
            $auth->setName(trim($input['name']));
            $auth->setEmail(strtolower(trim($input['email'])));
            $auth->setPassword(sha1(trim($input['password'])));
            $auth->setType(intval($input['type']));
            $auth->setStatus($this->_getStatus(intval($input['type'])));
            $result = $auth->register();
            if(boolval($result['success'])){
                $this->returnData['success'] = true;
                $this->returnData['data'] = $result['data'];
            }else{
                $this->returnData['error'] = $translation['went-wrong'];
            }
        }
        return $this->returnData;
    }
    
    private function _getStatus($type){
        $status = 1;
        switch($type){
            case 1:
                // its a user, activate its account
                $status = 1;
                break;
            case 2:
                // its a nurse, we need her documents; then will put in queue with status 4
                $status = 3;
                break;
        }
        return $status;
    }
    
}