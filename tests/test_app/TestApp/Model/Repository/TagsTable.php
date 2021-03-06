<?php
/**
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright 2005-2013, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice
 *
 * @since         CakePHP(tm) v 3.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace TestApp\Model\Repository;

use Cake\ORM\Table;

/**
 * Tag table class
 *
 */
class TagsTable extends Table {

	public function initialize(array $config) {
		$this->belongsTo('authors');
		$this->belongsToMany('articles');
		$this->hasMany('articlesTags', ['propertyName' => 'extraInfo']);
	}

}
