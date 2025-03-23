<?php
/**
 * ACF Repeater Widget Class
 */
class Elementor_ACF_Repeater extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'acf_repeater';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return __('ACF Repeater', 'elementor-acf-repeater');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-code';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return ['acf', 'repeater', 'custom fields', 'template'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // ACF Repeater Field Selector
        $repeater_fields = $this->get_acf_repeater_fields();
        
        $this->add_control(
            'acf_repeater_field',
            [
                'label' => __('ACF Repeater Field', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $repeater_fields,
                'default' => '',
                'description' => __('Select an ACF repeater field to display', 'elementor-acf-repeater'),
            ]
        );
        
        // HTML Template
        $this->add_control(
            'html_template',
            [
                'label' => __('HTML Template', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 10,
                'default' => '<div class="acf-repeater-item">
    <h3>[acfr_title]</h3>
    <p>[acfr_description]</p>
    <a class="button" href="[acfr_link]">Read More</a>
</div>',
                'description' => __('Use shortcodes for ACF repeater subfields. Example: [acfr_subfield_name]', 'elementor-acf-repeater'),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        // Available Subfields Display
        $this->add_control(
            'available_subfields',
            [
                'label' => __('Available Subfields', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div id="acf-repeater-subfields">Select a repeater field to see available subfields</div>',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    'acf_repeater_field!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Section - NEW SECTION
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Items per row control
        $this->add_responsive_control(
            'items_per_row',
            [
                'label' => __('Items Per Row', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-wrapper' => 'display: flex; flex-wrap: wrap;',
                    '{{WRAPPER}} .acf-repeater-item' => 'width: calc(100% / {{VALUE}} - ({{horizontal_gap.SIZE}}px * ({{VALUE}} - 1) / {{VALUE}}));',
                ],
            ]
        );

        // Horizontal gap
        $this->add_responsive_control(
            'horizontal_gap',
            [
                'label' => __('Horizontal Gap', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-wrapper' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Vertical gap
        $this->add_responsive_control(
            'vertical_gap',
            [
                'label' => __('Vertical Gap', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-wrapper' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Wrapper
        $this->start_controls_section(
            'style_wrapper_section',
            [
                'label' => __('Wrapper', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => __('Padding', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => __('Margin', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'label' => __('Border', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-wrapper',
            ]
        );

        $this->add_control(
            'wrapper_bg_color',
            [
                'label' => __('Background Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-wrapper' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Item
        $this->start_controls_section(
            'style_item_section',
            [
                'label' => __('Item', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __('Padding', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => __('Margin', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __('Border', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-item',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'default' => [
                    'top' => '5',
                    'right' => '5',
                    'bottom' => '5',
                    'left' => '5',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_control(
            'item_bg_color',
            [
                'label' => __('Background Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __('Box Shadow', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-item',
            ]
        );

        $this->end_controls_section();

        // Style Tab - Headings
        $this->start_controls_section(
            'style_headings_section',
            [
                'label' => __('Headings', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'headings_typography',
                'label' => __('Typography', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-item h1, {{WRAPPER}} .acf-repeater-item h2, {{WRAPPER}} .acf-repeater-item h3, {{WRAPPER}} .acf-repeater-item h4, {{WRAPPER}} .acf-repeater-item h5, {{WRAPPER}} .acf-repeater-item h6',
            ]
        );

        $this->add_control(
            'headings_color',
            [
                'label' => __('Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item h1, {{WRAPPER}} .acf-repeater-item h2, {{WRAPPER}} .acf-repeater-item h3, {{WRAPPER}} .acf-repeater-item h4, {{WRAPPER}} .acf-repeater-item h5, {{WRAPPER}} .acf-repeater-item h6' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'headings_margin',
            [
                'label' => __('Margin', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item h1, {{WRAPPER}} .acf-repeater-item h2, {{WRAPPER}} .acf-repeater-item h3, {{WRAPPER}} .acf-repeater-item h4, {{WRAPPER}} .acf-repeater-item h5, {{WRAPPER}} .acf-repeater-item h6' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Paragraphs
        $this->start_controls_section(
            'style_paragraphs_section',
            [
                'label' => __('Paragraphs', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'paragraphs_typography',
                'label' => __('Typography', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-item p',
            ]
        );

        $this->add_control(
            'paragraphs_color',
            [
                'label' => __('Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'paragraphs_margin',
            [
                'label' => __('Margin', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Tab - Buttons
        $this->start_controls_section(
            'style_buttons_section',
            [
                'label' => __('Buttons', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'style_buttons_tabs'
        );

        $this->start_controls_tab(
            'style_buttons_normal_tab',
            [
                'label' => __('Normal', 'elementor-acf-repeater'),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'buttons_typography',
                'label' => __('Typography', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button',
            ]
        );

        $this->add_control(
            'buttons_text_color',
            [
                'label' => __('Text Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buttons_bg_color',
            [
                'label' => __('Background Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'buttons_border',
                'label' => __('Border', 'elementor-acf-repeater'),
                'selector' => '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button',
            ]
        );

        $this->add_control(
            'buttons_border_radius',
            [
                'label' => __('Border Radius', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'buttons_padding',
            [
                'label' => __('Padding', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_buttons_hover_tab',
            [
                'label' => __('Hover', 'elementor-acf-repeater'),
            ]
        );

        $this->add_control(
            'buttons_hover_text_color',
            [
                'label' => __('Text Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button:hover, {{WRAPPER}} .acf-repeater-item button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buttons_hover_bg_color',
            [
                'label' => __('Background Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button:hover, {{WRAPPER}} .acf-repeater-item button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buttons_hover_border_color',
            [
                'label' => __('Border Color', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button:hover, {{WRAPPER}} .acf-repeater-item button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'buttons_hover_transition',
            [
                'label' => __('Transition Duration', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .acf-repeater-item a.button, {{WRAPPER}} .acf-repeater-item button' => 'transition: all {{SIZE}}s',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Custom CSS Section
        $this->start_controls_section(
            'custom_css_section',
            [
                'label' => __('Custom CSS', 'elementor-acf-repeater'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_css',
            [
                'label' => __('Custom CSS', 'elementor-acf-repeater'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'render_type' => 'ui',
                'show_label' => false,
                'separator' => 'none',
                'description' => __('Add your custom CSS here', 'elementor-acf-repeater'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get all ACF repeater fields
     */
    private function get_acf_repeater_fields() {
        $repeater_fields = [];
        
        // Check if ACF is active
        if (!function_exists('acf_get_field_groups')) {
            return $repeater_fields;
        }
        
        $field_groups = acf_get_field_groups();
        
        foreach ($field_groups as $field_group) {
            $fields = acf_get_fields($field_group);
            
            if (!$fields) {
                continue;
            }
            
            foreach ($fields as $field) {
                if ($field['type'] === 'repeater') {
                    $repeater_fields[$field['name']] = $field['label'];
                }
            }
        }
        
        return $repeater_fields;
    }

    /**
     * Get all subfields of a repeater field
     */
    private function get_repeater_subfields($repeater_name) {
        $subfields = [];
        
        // Check if ACF is active
        if (!function_exists('acf_get_field_groups')) {
            return $subfields;
        }
        
        $field_groups = acf_get_field_groups();
        
        foreach ($field_groups as $field_group) {
            $fields = acf_get_fields($field_group);
            
            if (!$fields) {
                continue;
            }
            
            foreach ($fields as $field) {
                if ($field['type'] === 'repeater' && $field['name'] === $repeater_name) {
                    if (isset($field['sub_fields']) && is_array($field['sub_fields'])) {
                        foreach ($field['sub_fields'] as $sub_field) {
                            $subfields[] = $sub_field['name'];
                        }
                    }
                    break 2;
                }
            }
        }
        
        return $subfields;
    }
    
    /**
     * Process shortcodes in the HTML template
     */
    private function process_template_shortcodes($template, $subfields, $prefix, $row) {
        // Replace each shortcode with the corresponding value
        foreach ($subfields as $field_name) {
            $shortcode = '[' . $prefix . $field_name . ']';
            $value = isset($row[$field_name]) ? $row[$field_name] : '';
            
            // Handle array values (like image fields)
            if (is_array($value)) {
                // If it's an image field with URL
                if (isset($value['url'])) {
                    $template = str_replace($shortcode, $value['url'], $template);
                } 
                // If it's a link field
                elseif (isset($value['link'])) {
                    $template = str_replace($shortcode, $value['link'], $template);
                }
                // Return empty if we can't determine the value
                else {
                    $template = str_replace($shortcode, '', $template);
                }
            } else {
                $template = str_replace($shortcode, $value, $template);
            }
        }
        
        return $template;
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get repeater field name
        $repeater_field = $settings['acf_repeater_field'];
        
        // If no repeater field is selected, show message and return
        if (empty($repeater_field)) {
            echo '<div class="acf-repeater-error">' . __('Please select an ACF repeater field.', 'elementor-acf-repeater') . '</div>';
            return;
        }
        
        // Get HTML template
        $html_template = $settings['html_template'];
        
        // If no template, show message and return
        if (empty($html_template)) {
            echo '<div class="acf-repeater-error">' . __('Please provide an HTML template.', 'elementor-acf-repeater') . '</div>';
            return;
        }
        
        // Get shortcode prefix
        $prefix = Elementor_ACF_Repeater_Widget::SHORTCODE_PREFIX;
        
        // Get repeater rows
        $rows = get_field($repeater_field);
        
        // If no rows found, show message and return
        if (!$rows || !is_array($rows)) {
            echo '<div class="acf-repeater-message">' . __('No repeater rows found.', 'elementor-acf-repeater') . '</div>';
            return;
        }
        
        // Get subfields
        $subfields = $this->get_repeater_subfields($repeater_field);
        
        // Custom CSS
        if (!empty($settings['custom_css'])) {
            echo '<style>' . $settings['custom_css'] . '</style>';
        }
        
        // Get layout settings
        $items_per_row = !empty($settings['items_per_row']) ? $settings['items_per_row'] : 1;
        
        // Start output
        echo '<div class="acf-repeater-wrapper acf-grid-layout">';
        
        // Loop through each row
        foreach ($rows as $row) {
            // Process template for this row
            $processed_template = $this->process_template_shortcodes($html_template, $subfields, $prefix, $row);
            
            // Output the processed template
            echo $processed_template;
        }
        
        echo '</div>'; // Close wrapper
    }

    /**
     * Render widget output in the editor.
     */
    protected function content_template() {
        ?>
        <div class="acf-repeater-wrapper elementor-repeater-preview">
            <# if ( settings.acf_repeater_field ) { #>
                <div class="acf-repeater-item">
                    <h3><?php echo __('ACF Repeater Preview', 'elementor-acf-repeater'); ?></h3>
                    <p><?php echo __('Your ACF repeater content will appear here.', 'elementor-acf-repeater'); ?></p>
                    <p><?php echo __('Preview is not available in the editor.', 'elementor-acf-repeater'); ?></p>
                </div>
            <# } else { #>
                <div class="acf-repeater-error">
                    <?php echo __('Please select an ACF repeater field.', 'elementor-acf-repeater'); ?>
                </div>
            <# } #>
        </div>
        <?php
    }
}