<?php

    class userController {
        
        public function indexAction(){
            
        }
        
        public function loginAction(){
            global $_u;
            
            if($_u->isAuth()){
                redirect('admin');
            }
            
            $params = getParams('login');           
            if(trim($params['secret'])==USER_SECRET){
                $_u->login();
                redirect('admin/show');
            }
            
        }
        
        public function logoutAction(){
            global $_u;
            $_u->logout();
            redirect('home');
        }
        
        
    }
?>