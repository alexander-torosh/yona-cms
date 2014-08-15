<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Contacts');
$I->amOnPage('/contacts');
$I->see('Тел. (044) 221-65-78');
$I->see('Email: web@wezoom.net');
