<?php declare(strict_types=1);

namespace Convo\Pckg\Forms;

use Convo\Core\Factory\AbstractPackageDefinition;

class FormsPackageDefinition extends AbstractPackageDefinition
{
	const NAMESPACE = 'convo-forms';

	public function __construct(
		\Psr\Log\LoggerInterface $logger
	)
	{
		parent::__construct( $logger, self::NAMESPACE, __DIR__);
	}

	protected function _initDefintions()
	{
	    $context_id_param =   [
	        'editor_type' => 'context_id',
	        'editor_properties' => array(),
	        'defaultValue' => 'forms',
	        'name' => 'Context ID',
	        'description' => 'Unique ID by which this context is referenced',
	        'valueType' => 'string'
	    ];


		return [
			new \Convo\Core\Factory\ComponentDefinition(
				$this->getNamespace(),
				'\Convo\Pckg\Forms\CreateEntryElement',
				'Create Form Entry',
				'Creates form entry.',
				array(
				    'context_id' => $context_id_param,
				    'entry' => array(
				        'editor_type' => 'params',
				        'editor_properties' => array(
				            'multiple' => true
				        ),
				        'defaultValue' => array(),
				        'name' => 'Payload',
				        'description' => 'Associative array containing the entry data',
				        'valueType' => 'array'
				    ),
					'result_var' => array(
						'editor_type' => 'text',
						'editor_properties' => array(),
						'defaultValue' => 'status',
						'name' => 'Result Variable Name',
						'description' => 'Variable with additional operation related information',
						'valueType' => 'string'
					),
					'ok' => [
						'editor_type' => 'service_components',
						'editor_properties' => [
							'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
							'multiple' => true
						],
						'defaultValue' => [],
						'defaultOpen' => false,
						'name' => 'OK',
						'description' => 'Flow to be executed if the form entry is created.',
						'valueType' => 'class'
					],
					'validation_error' => [
						'editor_type' => 'service_components',
						'editor_properties' => [
							'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
							'multiple' => true
						],
						'defaultValue' => [],
						'defaultOpen' => false,
						'name' => 'Suggestions',
						'description' => 'Flow to be executed if there are validation errrors.',
						'valueType' => 'class'
					],
					'_workflow' => 'read',
					'_preview_angular' => array(
						'type' => 'html',
						'template' => '<div class="code">' .
							'Create <b>{{ component.properties.context_id }}</b> form entry' .
							'</div>'
					),
					'_help' =>  array(
						'type' => 'file',
						'filename' => 'create-entry-element.html'
					)
				)
			),
			new \Convo\Core\Factory\ComponentDefinition(
			    $this->getNamespace(),
			    '\Convo\Pckg\Forms\DummyFormContext',
			    'Dummy Form Context',
			    'Provides dummy, test implementation of the form managing context',
			    array(
			        'id' => array(
			            'editor_type' => 'text',
			            'editor_properties' => array(),
			            'defaultValue' => 'form_ctx',
			            'name' => 'Context ID',
			            'description' => 'Unique ID by which this context is referenced',
			            'valueType' => 'string'
			        ),
			        '_preview_angular' => array(
			            'type' => 'html',
			            'template' => '<div class="code">' .
			            '<span class="statement">Dummy form </span> <b>[{{ contextElement.properties.id }}]</b>' .
			            '</div>'
			        ),
			        '_workflow' => 'datasource',
					'_help' =>  array(
						'type' => 'file',
						'filename' => 'dummy-form-context.html'
					)
				)
		    ),
            new \Convo\Core\Factory\ComponentDefinition(
                $this->getNamespace(),
                '\Convo\Pckg\Forms\UpdateEntryElement',
                'Update Form Entry',
                'Updates form entry.',
                array(
                    'context_id' => $context_id_param,
                    'entry_id'=>array(
                        'editor_type' => 'text',
                        'editor_properties' => array(),
                        'defaultValue' => '',
                        'name' => 'Entry ID',
                        'description' => 'ID of the Entry to update.',
                        'valueType' => 'string'
                    ),
                    'entry' => array(
                        'editor_type' => 'params',
                        'editor_properties' => array(
                            'multiple' => true
                        ),
                        'defaultValue' => array(),
                        'name' => 'Payload',
                        'description' => 'Associative array containing the entry data',
                        'valueType' => 'array'
                    ),
                    'result_var' => array(
                        'editor_type' => 'text',
                        'editor_properties' => array(),
                        'defaultValue' => 'status',
                        'name' => 'Result Variable Name',
                        'description' => 'Variable with additional operation related information',
                        'valueType' => 'string'
                    ),
                    'ok' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'OK',
                        'description' => 'Flow to be executed if the form entry is created.',
                        'valueType' => 'class'
                    ],
                    'validation_error' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'Suggestions',
                        'description' => 'Flow to be executed if there are validation errrors.',
                        'valueType' => 'class'
                    ],
                    '_workflow' => 'read',
                    '_preview_angular' => array(
                        'type' => 'html',
                        'template' => '<div class="code">' .
                            'Create <b>{{ component.properties.context_id }}</b> form entry' .
                            '</div>'
                    ),
                    '_help' =>  array(
                        'type' => 'file',
                        'filename' => 'update-entry-element.html'
                    )
                )
            ),
            new \Convo\Core\Factory\ComponentDefinition(
                $this->getNamespace(),
                '\Convo\Pckg\Forms\DeleteEntryElement',
                'Delete Form Entry',
                'Deletes form entry.',
                array(
                    'context_id' => $context_id_param,
                    'entry_id'=>array(
                        'editor_type' => 'text',
                        'editor_properties' => array(),
                        'defaultValue' => '',
                        'name' => 'Entry ID',
                        'description' => 'ID of the Entry to update.',
                        'valueType' => 'string'
                    ),
                    'ok' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'OK',
                        'description' => 'Flow to be executed if the form entry is deleted.',
                        'valueType' => 'class'
                    ],
                    '_workflow' => 'read',
                    '_preview_angular' => array(
                        'type' => 'html',
                        'template' => '<div class="code">' .
                            'Create <b>{{ component.properties.context_id }}</b> form entry' .
                            '</div>'
                    ),
                    '_help' =>  array(
                        'type' => 'file',
                        'filename' => 'delete-entry-element.html'
                    )
                )
            ),
            new \Convo\Core\Factory\ComponentDefinition(
                $this->getNamespace(),
                '\Convo\Pckg\Forms\LoadEntryElement',
                'Load Form Entry',
                'Loads form entry.',
                array(
                    'context_id' => $context_id_param,
                    'entry_id'=>array(
                        'editor_type' => 'text',
                        'editor_properties' => array(),
                        'defaultValue' => '',
                        'name' => 'Entry ID',
                        'description' => 'ID of the Entry to load.',
                        'valueType' => 'string'
                    ),
                    'ok' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'OK',
                        'description' => 'Flow to be executed if the form entry is loaded.',
                        'valueType' => 'class'
                    ],
                    '_workflow' => 'read',
                    '_preview_angular' => array(
                        'type' => 'html',
                        'template' => '<div class="code">' .
                            'Create <b>{{ component.properties.context_id }}</b> form entry' .
                            '</div>'
                    ),
                    '_help' =>  array(
                        'type' => 'file',
                        'filename' => 'load-entry-element.html'
                    )
                )
            ),
            new \Convo\Core\Factory\ComponentDefinition(
                $this->getNamespace(),
                '\Convo\Pckg\Forms\SearchEntriesElement',
                'Search Form Entries',
                'Searches form entries.',
                array(
                    'context_id' => $context_id_param,
                    'search' => array(
                        'editor_type' => 'params',
                        'editor_properties' => array(
                            'multiple' => true
                        ),
                        'defaultValue' => array(),
                        'name' => 'Search',
                        'description' => 'Search',
                        'valueType' => 'array'
                    ),
                    'multiple_flow' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'Multiple',
                        'description' => 'Flow to be executed if the requested search has found multiple entries.',
                        'valueType' => 'class'
                    ],
                    'single_flow' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'Multiple',
                        'description' => 'Flow to be executed if the requested search has found a single entry.',
                        'valueType' => 'class'
                    ],
                    'empty_flow' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true,
                            'hideWhenEmpty' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'Empty',
                        'description' => "Flow to be executed if the requested search didn't find anything.",
                        'valueType' => 'class'
                    ],
                    '_workflow' => 'read',
                    '_preview_angular' => array(
                        'type' => 'html',
                        'template' => '<div class="code">' .
                            'Create <b>{{ component.properties.context_id }}</b> form entry' .
                            '</div>'
                    ),
                    '_help' =>  array(
                        'type' => 'file',
                        'filename' => 'search-entries-element.html'
                    )
                )
            )
		];
	}
}
