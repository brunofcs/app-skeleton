<?php

class AutorizacaoTable extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'LCAUTORIZACAO';

    /** Table PK */
	protected $_primary  = array('LCAUTGRUPO', 'LCAUTMENU');

	/** Table Row Model */
	protected $_rowClass = 'Row';

}