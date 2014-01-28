<?php

/**
 * This is the DbTable Row class for all tables.
 */
class Row extends Zend_Db_Table_Row
{
	public function save() {
		parent::save();
	}

	public function __get($campo) {
		return parent::__get($campo);
	}
}