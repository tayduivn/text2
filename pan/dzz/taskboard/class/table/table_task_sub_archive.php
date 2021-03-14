<?php
/* @authorcode  codestrings
 * @copyright   Leyun internet Technology(Shanghai)Co.,Ltd
 * @license     http://www.dzzoffice.com/licenses/license.txt
 * @package     DzzOffice
 * @link        http://www.dzzoffice.com
 * @author      zyx(zyx@dzz.cc)
 */

if(!defined('IN_DZZ')) {
	exit('Access Denied');
}

class table_task_sub_archive extends dzz_table
{
	public function __construct() {

		
		$this->_table = 'task_sub_archive';
		$this->_pk    = 'subid';
		
		parent::__construct();
	}
}

?>
