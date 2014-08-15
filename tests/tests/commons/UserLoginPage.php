<?php

class UserLoginPage
{
    // include url of current page
    public static $URL = '/admin';
    public static $RedirectURL = '/admin/index/login';
    public static $login = 'yona';
    public static $password = 'yonacmsphalcon';

    /**
     * @var AcceptanceTester
     */
    protected $AcceptanceTester;

    public function __construct(AcceptanceTester $I)
    {
        $this->AcceptanceTester = $I;
    }

    public static function of(AcceptanceTester $I)
    {
        return new static($I);
    }

    public function login($name, $password)
    {
        $I = $this->AcceptanceTester;

        $I->amOnPage(self::$URL);
        $I->seeInCurrentUrl(self::$RedirectURL);

        $I->fillField(LoginPage::$usernameField, $name);
        $I->fillField(LoginPage::$passwordField, $password);

        $I->click(LoginPage::$loginButton);

        return $this;
    }

    public function loginCorrect()
    {
        $I = $this->AcceptanceTester;

        $this->login(self::$login, self::$password);

        $I->seeInCurrentUrl('/admin');
        $I->see('Административная панель YonaCms');

        return $this;
    }

}