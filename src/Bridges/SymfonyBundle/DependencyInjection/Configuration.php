<?php

/**
 * This file is part of the Nextras community extensions of Nette Framework
 *
 * @license    New BSD License
 * @link       https://github.com/nextras/migrations
 */

namespace Nextras\Migrations\Bridges\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
	const SYMFONY_KERNEL_CLASS = 'Symfony\Component\HttpKernel\Kernel';

	public function getConfigTreeBuilder()
	{
		if (
			class_exists(self::SYMFONY_KERNEL_CLASS) &&
			constant('Symfony\Component\HttpKernel\Kernel::VERSION_ID') < 30300
		) {
			$dirDefaultValue = '%kernel.root_dir%/../migrations';
		} else {
			$dirDefaultValue = '%kernel.project_dir%/migrations';
		}

		$treeBuilder = new TreeBuilder();
		$treeBuilder->root('nextras_migrations')->children()
			->scalarNode('dir')
				->defaultValue($dirDefaultValue)
				->cannotBeEmpty()
				->end()
			->enumNode('dbal')
				->values(['dibi', 'dibi2', 'dibi3', 'dibi4', 'doctrine', 'nette', 'nextras'])
				->defaultValue('doctrine')
				->cannotBeEmpty()
				->end()
			->enumNode('driver')
				->values(['mysql', 'pgsql'])
				->isRequired()
				->cannotBeEmpty()
				->end()
			->scalarNode('diff_generator')
				->defaultValue('doctrine')
				->end()
			->booleanNode('with_dummy_data')
				->defaultFalse()
				->end()
			->arrayNode('php_params')
				->prototype('variable')
					->end()
				->end()
			->scalarNode('ignored_queries_file')
				->defaultNull()
				->end()
			->end();

		return $treeBuilder;
	}
}
