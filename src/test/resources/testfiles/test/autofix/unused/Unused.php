<?php

namespace test\autofix\unused;

use test\a\AClass;
use test\a\BClass;
use test\a\CClass;
use test\a\DClass;

class Unused {

	/** @var BClass */
	private $blah;

	/**
	 * @param AClass $aClass
	 * @return DClass
	 */
	public function blah(AClass $aClass) {}

}
