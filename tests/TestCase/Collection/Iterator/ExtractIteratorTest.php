<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 3.0.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Cake\Test\TestCase\Collection\Iterator;

use ArrayObject;
use Cake\Collection\Iterator\ExtractIterator;
use Cake\TestSuite\TestCase;

/**
 * ExtractIterator Test
 *
 */
class ExtractIteratorTest extends TestCase {

/**
 * Tests it is possible to extract a column in the first level of an array
 *
 * @return void
 */
	public function testExtractFromArrayShallow() {
		$items = [
			['a' => 1, 'b' => 2],
			['a' => 3, 'b' => 4]
		];
		$extractor = new ExtractIterator($items, 'a');
		$this->assertEquals([1, 3], iterator_to_array($extractor));

		$extractor = new ExtractIterator($items, 'b');
		$this->assertEquals([2, 4], iterator_to_array($extractor));

		$extractor = new ExtractIterator($items, 'c');
		$this->assertEquals([null, null], iterator_to_array($extractor));
	}

/**
 * Tests it is possible to extract a column in the first level of an object
 *
 * @return void
 */
	public function testExtractFromObjectShallow() {
		$items = [
			new ArrayObject(['a' => 1, 'b' => 2]),
			new ArrayObject(['a' => 3, 'b' => 4])
		];
		$extractor = new ExtractIterator($items, 'a');
		$this->assertEquals([1, 3], iterator_to_array($extractor));

		$extractor = new ExtractIterator($items, 'b');
		$this->assertEquals([2, 4], iterator_to_array($extractor));

		$extractor = new ExtractIterator($items, 'c');
		$this->assertEquals([null, null], iterator_to_array($extractor));
	}

/**
 * Tests it is possible to extract a column deeply nested in the structure
 *
 * @return void
 */
	public function testExtractFromArrayDeep() {
		$items = [
			['a' => ['b' => ['c' => 10]], 'b' => 2],
			['a' => ['b' => ['d' => 15]], 'b' => 4],
			['a' => ['x' => ['z' => 20]], 'b' => 4],
			['a' => ['b' => ['c' => 25]], 'b' => 2],
		];
		$extractor = new ExtractIterator($items, 'a.b.c');
		$this->assertEquals([10, null, null, 25], iterator_to_array($extractor));
	}

}
