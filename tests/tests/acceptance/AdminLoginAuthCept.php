<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Admin login auth');

UserLoginPage::of($I)->loginCorrect();