<?php

namespace Drupal\recaptcha_verification\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'field_recaptcha_action' field type.
 *
 * @FieldType(
 *   id = "action",
 *   label = @Translation("Recaptcha Action"),
 *   module = "recaptcha_verification",
 *   description = @Translation("Creates a field that sets a google recaptcha action."),
 *   default_widget = "default_recaptcha_action_widget",
 * )
 */
class Action extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'name' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
        'datalayer' => [
          'type' => 'int',
          'size' => 'tiny',
          'not null' => TRUE,
          'default' => 0,
        ]
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('name')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['name'] = DataDefinition::create('string')
      ->setLabel(t('Action Name'));
    $properties['datalayer'] = DataDefinition::create('integer')
      ->setLabel(t('Datalayer'));

    return $properties;
  }

}