<?php 
$I = new AcceptanceTester($scenario);

$I->wantTo('Get response from api');
$I->amOnPage('/api');
$I->seeInSource('Welcome to api for yona-cms!');

$I->wantTo('Get page list from api');
$I->amOnPage('/api/page/list');
$I->seeInSource('"title":"Homepage"');

$I->wantTo('Get single page info from api');
$I->amOnPage('/api/page/get?pageId=1');
$I->seeInSource('metaDescription":"meta-description of homepage",');