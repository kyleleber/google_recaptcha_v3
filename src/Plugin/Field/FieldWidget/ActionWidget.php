<?php

namespace Drupal\recaptcha_verification\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\StringTextareaWidget;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\text\Plugin\Field\FieldWidget\TextfieldWidget;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Plugin implementation of the 'text_textarea' widget.
 *
 * @FieldWidget(
 *   id = "default_recaptcha_action_widget",
 *   label = @Translation("Action Widget"),
 *   field_types = {
 *     "action"
 *   }
 * )
 */
class ActionWidget extends TextfieldWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $main_widget = parent::formElement($items, $delta, $element, $form, $form_state);

    $element['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Recaptcha Action'),
      '#base_type' => $main_widget['name']['#type'],
      '#description' => $this->t('Name of Recaptcha Action'),
      '#default_value' => $items[$delta]->name,
    ];
    $element['datalayer'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Push to Datalayer'),
      '#base_type' => $main_widget['datalayer']['#type'],
      '#description' => $this->t('Checking this box will push the action and score to the datalayer.'),
      '#default_value' => $items[$delta]->datalayer,
    ];

    return $element;
  }

}
