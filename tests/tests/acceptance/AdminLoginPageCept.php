<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Admin login page');
$I->amOnPage('/admin');
$I->seeInCurrentUrl('/admin/index/login');
$I->seeInTitle('Login');
$I->seeElement('#login');
$I->seeElement('#password');