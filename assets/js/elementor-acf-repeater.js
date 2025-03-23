/**
 * Elementor ACF Repeater Widget JavaScript
 */
(function($) {
    'use strict';
    
    // Run when Elementor editor is loaded
    $(window).on('elementor/frontend/init', function() {
        // Hook into the panel editor
        elementor.hooks.addAction('panel/open_editor/widget/acf_repeater', function(panel, model, view) {
            var $element = view.$el;
            
            // When the ACF Repeater field select changes
            panel.$el.find('[data-setting="acf_repeater_field"]').on('change', function() {
                var repeaterField = $(this).val();
                
                if (!repeaterField) {
                    // Clear the subfields display if no field selected
                    panel.$el.find('#acf-repeater-subfields').html('Select a repeater field to see available subfields');
                    return;
                }
                
                // Get the subfields via AJAX
                $.ajax({
                    url: elementor_acf_repeater_vars.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_acf_repeater_subfields',
                        security: elementor_acf_repeater_vars.nonce,
                        field: repeaterField
                    },
                    success: function(response) {
                        if (response.success) {
                            // Get the shortcode prefix from the localized variable
                            var prefix = elementor_acf_repeater_vars.prefix;
                            
                            // Update the subfields display
                            var subfields = response.data;
                            var html = '<p>Available subfields:</p><div class="acfr-subfields-list">';
                            
                            // Create a default template with a wrapper div
                            var codeTemplate = '<div class="acf-repeater-item">\n';
                            
                            // Create list of available subfields with copy buttons
                            subfields.forEach(function(field) {
                                var shortcode = '[' + prefix + field + ']';
                                html += '<div class="acfr-subfield-item">' +
                                        '<code class="acfr-shortcode">' + shortcode + '</code>' +
                                        '<button class="acfr-copy-btn" data-shortcode="' + shortcode + '">Copy</button>' +
                                        '<span class="acfr-field-name">' + field + '</span>' +
                                        '</div>';
                                codeTemplate += '    ' + shortcode + '\n';
                            });
                            
                            // Close the template div
                            codeTemplate += '</div>';
                            
                            html += '</div>';
                            
                            // Update the HTML
                            panel.$el.find('#acf-repeater-subfields').html(html);
                            
                            // Add click handlers for the copy buttons
                            panel.$el.find('.acfr-copy-btn').on('click', function(e) {
                                e.preventDefault();
                                
                                var shortcode = $(this).data('shortcode');
                                var tempInput = $('<input>');
                                $('body').append(tempInput);
                                tempInput.val(shortcode).select();
                                document.execCommand('copy');
                                tempInput.remove();
                                
                                // Visual feedback
                                var $btn = $(this);
                                $btn.text('Copied!');
                                setTimeout(function() {
                                    $btn.text('Copy');
                                }, 1500);
                            });
                            
                            // Update the code editor with available shortcodes
                            var codeEditor = panel.$el.find('[data-setting="html_template"]');
                            if (codeEditor.val() === '' || !codeEditor.val().includes('[' + prefix)) {
                                codeEditor.val(codeTemplate);
                                // Trigger change event to update Elementor model
                                codeEditor.trigger('input');
                            }
                        } else {
                            panel.$el.find('#acf-repeater-subfields').html('No subfields found for this repeater field.');
                        }
                    },
                    error: function() {
                        panel.$el.find('#acf-repeater-subfields').html('Error loading subfields. Please try again.');
                    }
                });
            });
            
            // Trigger the change event to load subfields if a field is already selected
            var selectedField = panel.$el.find('[data-setting="acf_repeater_field"]').val();
            if (selectedField) {
                panel.$el.find('[data-setting="acf_repeater_field"]').trigger('change');
            }
        });
    });
    
})(jQuery);