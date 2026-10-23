<?php

/**
 * @copyright Copyright (c) 2009-2022 ThemeCatcher (https://www.themecatcher.net)
 */
class Quform_Element_Honeypot extends Quform_Element_Field
{
    /**
     * Render this field and return the HTML
     *
     * @param   array   $context
     * @return  string
     */
    public function render(array $context = array())
    {
        $context = $this->prepareContext($context);

        $labels = apply_filters(
            'quform_honeypot_labels',
            array(
                'Email',
                'Email Address',
                'Website URL',
                'Your Website',
                'Contact Email',
                'Business Email',
                'Phone Number',
                'Company Name'
            )
        );

        $inlineStyle = 'clip: rect(1px, 1px, 1px, 1px); clip-path: inset(50%); position: absolute !important; height: 1px; width: 1px; overflow: hidden;';

        $output = sprintf('<div class="quform-element quform-element-textarea quform-element-%s quform-sr-only quform-cf" style="%s">',
            esc_attr($this->getIdentifier()),
            esc_attr($inlineStyle)
        );

        $output .= '<div class="quform-spacer">';

        $output .= sprintf('<div class="quform-label quform-label-%s">', esc_attr($this->getIdentifier()));
        $output .= sprintf('<label class="quform-label-text" for="%s">%s%s</label>',
            esc_attr($this->getUniqueId()),
            esc_html($labels[array_rand($labels)]),
            Quform::isNonEmptyString($requiredText = $this->form->config('requiredText')) ? '<span class="quform-required">' . esc_html($requiredText) . '</span>' : ''
        );
        $output .= '</div>';

        $output .= sprintf('<div class="quform-inner quform-inner-%s">', esc_attr($this->getIdentifier()));

        $output .= $this->getFieldHtml($context);

        $output .= '</div></div></div>';

        return $output;
    }

    /**
     * Get the HTML attributes for the field
     *
     * @param   array  $context
     * @return  array
     */
    protected function getFieldAttributes(array $context = array())
    {
        $attributes = array(
            'id' => $this->getUniqueId(),
            'name' => $this->getFullyQualifiedName(),
            'class' => Quform::sanitizeClass($this->getFieldClasses($context)),
            'tabindex' => '-1',
            'autocomplete' => 'new-password'
        );

        $attributes = apply_filters('quform_field_attributes', $attributes, $this, $this->form, $context);
        $attributes = apply_filters('quform_field_attributes_' . $this->getIdentifier(), $attributes, $this, $this->form, $context);

        return $attributes;
    }

    /**
     * Get the classes for the field
     *
     * @param   array  $context
     * @return  array
     */
    protected function getFieldClasses(array $context = array())
    {
        $classes = array(
            'quform-field',
            'quform-field-textarea',
            sprintf('quform-field-%s', $this->getIdentifier())
        );

        $classes = apply_filters('quform_field_classes', $classes, $this, $this->form, $context);
        $classes = apply_filters('quform_field_classes_' . $this->getIdentifier(), $classes, $this, $this->form, $context);

        return $classes;
    }

    /**
     * Get the HTML for the field
     *
     * @param   array $context
     * @return  string
     */
    protected function getFieldHtml(array $context = array())
    {
        return Quform::getHtmlTag('textarea', $this->getFieldAttributes($context));
    }

    /**
     * Get the default element configuration
     *
     * @param   string|null  $key  Get the config by key, if omitted the full config is returned
     * @return  array
     */
    public static function getDefaultConfig($key = null)
    {
        $config = apply_filters('quform_default_config_honeypot', array(
            'showInEmail' => false,
            'saveToDatabase' => false
        ));

        $config['type'] = 'honeypot';

        if (Quform::isNonEmptyString($key)) {
            return Quform::get($config, $key);
        }

        return $config;
    }
}
