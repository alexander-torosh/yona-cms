<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Click from Home to Contacts link');
$I->amOnPage('/');
$I->click('#menu a[data-menu="contacts"]');
$I->see('Тел. (044) 221-65-78');
$I->see('Email: web@wezoom.net');
