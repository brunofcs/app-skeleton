<?php

class LogUsuarioTable extends Zend_Db_Table_Abstract {

    /** Table name */
    protected $_name    = 'LCLOGUSUARIO';

    /** Table PK */
	protected $_primary  = array('LCLOGUSDATAHORA', 'LCLOGUSUSUARIO');

	/** Table Row Model */
	protected $_rowClass = 'Row';

}