<?php

class MenuTable extends Zend_Db_Table_Abstract {

    /** Table name */
    protected $_name    = 'CDMENU';

    /** Table PK */
	protected $_primary  = 'CDMNUMENU';

	/** Table Row Model */
	protected $_rowClass = 'Row';

}