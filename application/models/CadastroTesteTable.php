<?php

class CadastroTesteTable extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'CDCADASTROTESTE';

    /** Table PK */
	protected $_primary  = array('CDCDTCODIGO');

	/** Table Row Model */
	protected $_rowClass = 'Row';

}