<?php

/**
 * Volt
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Mvc\View\Engine;

class Volt extends \Phalcon\Mvc\View\Engine\Volt
{

	public function __construct($view, $dependencyInjector = null)
	{
		parent::__construct($view, $dependencyInjector);

	}

	public function initCompiler()
	{
		$compiler = $this->getCompiler();

		$compiler->addFunction('const', function($resolvedArgs) {
			return $resolvedArgs;
			//return "get_defined_constants()[$resolvedArgs]";
		});
		/*$compiler->addFunction('helper', function() {
            return '$this->helper';
        });*/
		$compiler->addFunction('langUrl', function($resolvedArgs) {
			return '$this->helper->langUrl(' . $resolvedArgs . ')';
		});
		$compiler->addFunction('image', function($resolvedArgs) {
			return '(new \Image\Storage(' . $resolvedArgs . '))';
		});
		$compiler->addFunction('imageHtml', function($resolvedArgs) {
			return '(new \Image\Storage(' . $resolvedArgs . '))->imageHtml()';
		});
		$compiler->addFunction('imageSrc', function($resolvedArgs) {
			return '(new \Image\Storage(' . $resolvedArgs . '))->cachedRelPath()';
		});
		$compiler->addFunction('widget', function($resolvedArgs) {
			return '(new \Application\Widget\Proxy(' . $resolvedArgs . '))';
		});

	}

}
