<?php

namespace App\Manager;




class UserManager{

    public function checknbServiceofTypeUser($user,$lesServices)
    {
           //nb de services
           $nbServices=count($lesServices);
           if($user->getTypeUser()=="SALA" && $nbServices>2){
              
              // dump($lesServices);die;
           }
    }






}
