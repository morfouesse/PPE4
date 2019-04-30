<?php

namespace App\Tests\Util;

use App\Entity\User;
use App\Entity\Service;
use App\Util\ServiceDuUserUtil;
use PHPUnit\Framework\TestCase;

class ServiceDuUserUtilTest extends TestCase
{
 
    public function testAddServiceUser(){
      // access à la class 
        $ServiceDuUserUtil= new ServiceDuUserUtil();
        $user=new User(45,"SALA","testSALA","testSALA","SALA");
        $service= new Service();
        // acces a la methode a tester
        $result = $ServiceDuUserUtil->addServiceUser(30, 12);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }




}