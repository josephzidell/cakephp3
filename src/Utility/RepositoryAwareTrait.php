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
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Utility;

use Cake\Error;
use Cake\Utility\Inflector;

/**
 * Provides functionality for loading table classes
 * and other repositories onto properties of the host object.
 *
 * Example users of this trait are Cake\Controller\Controller and 
 * Cake\Console\Shell.
 */
trait RepositoryAwareTrait {

/**
 * This object's primary model class name, the Inflector::pluralized()'ed version of
 * the object's $name property.
 *
 * Example: For a object named 'Comments', the modelClass would be 'Comments'
 *
 * @var string
 */
	public $modelClass;

/**
 * A list of repository factory functions.
 *
 * @var array
 */
	protected $_repositoryFactories = [];

/**
 * Set the modelClass and modelKey properties based on conventions.
 *
 * If the properties are already set they will not be overwritten
 *
 * @param string $name
 * @return void
 */
	protected function _setModelClass($name) {
		if (empty($this->modelClass)) {
			$this->modelClass = Inflector::pluralize($name);
		}
	}

/**
 * Loads and constructs repository objects required by this object
 *
 * Typically used to load ORM Table objects as required. Can
 * also be used to load other types of repository objects your application uses.
 *
 * If a repository provider does not return an object a MissingModelException will
 * be thrown.
 *
 * @param string $modelClass Name of model class to load. Defaults to $this->modelClass
 * @param string $type The type of repository to load. Defaults to 'Table' which
 *   delegates to Cake\ORM\TableRegistry.
 * @return boolean True when single repository found and instance created.
 * @throws Cake\Error\MissingModelException if the model class cannot be found.
 * @throws Cake\Error\Exception When using a type that has not been registered.
 */
	public function repository($modelClass = null, $type = 'Table') {
		if ($modelClass === null) {
			$modelClass = $this->modelClass;
		}

		if (isset($this->{$modelClass})) {
			return true;
		}

		list($plugin, $modelClass) = pluginSplit($modelClass, true);

		if (!isset($this->_repositoryFactories[$type])) {
			throw new Error\Exception(sprintf(
				'Unknown repository type "%s". Make sure you register a type before trying to use it.',
				$type
			));
		}
		$factory = $this->_repositoryFactories[$type];
		$this->{$modelClass} = $factory($plugin . $modelClass);
		if (!$this->{$modelClass}) {
			throw new Error\MissingModelException($modelClass);
		}
		return true;
	}

/**
 * Register a callable to generate repositories of a given type.
 *
 * @param string $type The name of the repository type the factory function is for.
 * @param callable $factory The factory function used to create instances.
 * @return void
 */
	public function repositoryFactory($type, callable $factory) {
		$this->_repositoryFactories[$type] = $factory;
	}

}
