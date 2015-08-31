<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Homapage');
$I->amOnPage('/');
$I->see('Стартовая страница');
$I->see('веб-студия WeZoom');
