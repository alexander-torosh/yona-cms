<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Admin wrong login auth false');
$I->amOnPage('/admin');
$I->seeInCurrentUrl('/admin/index/login');
/*$I->seeInTitle('Login');
$I->seeElement('#login');
$I->seeElement('#password');
$I->fillField('login', 'yona#%@!^');
$I->fillField('password', 'yonacmsphalcon');
$I->click('Login');*/

UserLoginPage::of($I)->login('yona', 'yonacmsphalcon@#$%^&*');

$I->see('Неверный логин или пароль');
$I->seeInCurrentUrl('/admin/index/login');
$I->seeInTitle('Login');