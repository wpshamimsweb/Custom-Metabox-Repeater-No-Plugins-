<?php

// Register Meta Box
function register_product_meta_box() {
    add_meta_box(
        'product_custom_fields',
        'Product Custom Fields',
        'display_product_custom_fields',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'register_product_meta_box');

// Display Meta Box
function display_product_custom_fields($post) {
    wp_nonce_field('save_product_custom_fields', 'product_custom_fields_nonce');

    $collection_name = get_post_meta($post->ID, '_collection_name', true);
    $quantity_available = get_post_meta($post->ID, '_quantity_available', true);
    $color_options = get_post_meta($post->ID, '_color_options', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="collection_name">Collection Name</label></th>
            <td><input type="text" name="collection_name" id="collection_name" value="<?php echo esc_attr($collection_name); ?>" class="regular-text" /></td>
        </tr>

        <tr>
            <th><label for="quantity_available">Quantity Available</label></th>
            <td>
                <select name="quantity_available" id="quantity_available">
                    <option value="yes" <?php selected($quantity_available, 'yes'); ?>>Yes</option>
                    <option value="no" <?php selected($quantity_available, 'no'); ?>>No</option>
                </select>
            </td>
        </tr>

        <tr>
            <th><label for="color_options">Color Options</label></th>
            <td>
                <div id="color_options_container">
                    <?php if (!empty($color_options)) : ?>
                        <?php foreach ($color_options as $index => $color) : ?>
                            <div class="repeater-item" data-index="<?php echo $index; ?>">
                                <div class="repeater-header">
                                    <span class="toggle-icon">+</span>
                                    <span>Color Option <?php echo $index + 1; ?></span>
                                    <button type="button" class="button-link remove-color">Remove</button>
                                </div>
                                <div class="repeater-content">
                                    <label>Color Name:</label>
                                    <input type="text" name="color_options[<?php echo $index; ?>][color_name]" value="<?php echo esc_attr($color['color_name']); ?>" class="regular-text" />
                                    <label>Color Code:</label>
                                    <input type="text" name="color_options[<?php echo $index; ?>][color_code]" value="<?php echo esc_attr($color['color_code']); ?>" class="regular-text" />
                                    <label>Color Image 1:</label>
                                    <input type="file" name="color_options[<?php echo $index; ?>][color_image_1]" />
                                    <label>Color Image 2:</label>
                                    <input type="file" name="color_options[<?php echo $index; ?>][color_image_2]" />
                                    <label>Color Image 3:</label>
                                    <input type="file" name="color_options[<?php echo $index; ?>][color_image_3]" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" id="add_color" class="button button-primary">+ Add Color</button>
            </td>
        </tr>
    </table>

    <style>
        #color_options_container {
            margin-top: 15px;
        }

        .repeater-item {
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
            background: #f9f9f9;
        }

        .repeater-header {
            padding: 10px;
            background: #e9e9e9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .repeater-header .toggle-icon {
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .repeater-content {
            padding: 10px;
            display: none;
        }

        .repeater-header .remove-color {
            color: #f00;
            text-decoration: none;
            cursor: pointer;
        }

        .repeater-header .remove-color:hover {
            text-decoration: underline;
        }

        .repeater-content label {
            display: block;
            margin-bottom: 5px;
        }

        .repeater-content input {
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }

        #add_color {
            margin-top: 15px;
            display: block;
        }
    </style>

    <script>
        document.getElementById('add_color').addEventListener('click', function() {
            var container = document.getElementById('color_options_container');
            var index = container.getElementsByClassName('repeater-item').length;

            var repeaterHTML = `
                <div class="repeater-item" data-index="${index}">
                    <div class="repeater-header">
                        <span class="toggle-icon">+</span>
                        <span>Color Option ${index + 1}</span>
                        <button type="button" class="button-link remove-color">Remove</button>
                    </div>
                    <div class="repeater-content">
                        <label>Color Name:</label>
                        <input type="text" name="color_options[${index}][color_name]" class="regular-text" />
                        <label>Color Code:</label>
                        <input type="text" name="color_options[${index}][color_code]" class="regular-text" />
                        <label>Color Image 1:</label>
                        <input type="file" name="color_options[${index}][color_image_1]" />
                        <label>Color Image 2:</label>
                        <input type="file" name="color_options[${index}][color_image_2]" />
                        <label>Color Image 3:</label>
                        <input type="file" name="color_options[${index}][color_image_3]" />
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', repeaterHTML);
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-color')) {
                event.target.closest('.repeater-item').remove();
            } else if (event.target.classList.contains('toggle-icon')) {
                var item = event.target.closest('.repeater-item');
                var content = item.querySelector('.repeater-content');
                content.style.display = content.style.display === 'block' ? 'none' : 'block';
                event.target.textContent = content.style.display === 'block' ? '-' : '+';
            }
        });
    </script>
    <?php
}

// Save Meta Box Data
function save_product_custom_fields($post_id) {
    if (!isset($_POST['product_custom_fields_nonce']) || !wp_verify_nonce($_POST['product_custom_fields_nonce'], 'save_product_custom_fields')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['collection_name'])) {
        update_post_meta($post_id, '_collection_name', sanitize_text_field($_POST['collection_name']));
    }

    if (isset($_POST['quantity_available'])) {
        update_post_meta($post_id, '_quantity_available', sanitize_text_field($_POST['quantity_available']));
    }

    if (isset($_POST['color_options']) && is_array($_POST['color_options'])) {
        $sanitized_color_options = array_map(function($color) {
            return [
                'color_name'  => sanitize_text_field($color['color_name'] ?? ''),
                'color_code'  => sanitize_text_field($color['color_code'] ?? ''),
                'color_image_1' => esc_url_raw($color['color_image_1'] ?? ''),
                'color_image_2' => esc_url_raw($color['color_image_2'] ?? ''),
                'color_image_3' => esc_url_raw($color['color_image_3'] ?? ''),
            ];
        }, $_POST['color_options']);

        update_post_meta($post_id, '_color_options', $sanitized_color_options);
    }
}
add_action('save_post', 'save_product_custom_fields');
