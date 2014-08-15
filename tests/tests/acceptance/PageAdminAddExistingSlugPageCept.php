<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Page admin add existing slug page error');

UserLoginPage::of($I)->loginCorrect();

$I->click('.menu.ui a[href="/page/admin"]');
$I->seeInTitle('Список страниц');
$I->click('a.ui.button[href="/page/admin/add"]');
$I->seeInTitle('Создание страницы');
$I->fillField('title', 'index');
$I->fillField('slug', 'index');
$I->click('.ui.submit');
$I->amOnPage('/page/admin/add');
$I->see('Страница с такой транслитерацией уже существует');