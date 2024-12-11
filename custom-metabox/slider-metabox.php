<?php

function register_slider_meta_box() {
    add_meta_box(
        'homepage_slider',
        'Homepage Slider',
        'display_slider_meta_box',
        'page',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'register_slider_meta_box');

function display_slider_meta_box($post) {
    wp_nonce_field('save_slider_meta_box', 'slider_meta_nonce');

    $slides = get_post_meta($post->ID, '_homepage_slides', true);
    ?>
    <div id="slider_repeater">
        <?php if (!empty($slides)) : ?>
            <?php foreach ($slides as $index => $slide) : ?>
                <div class="repeater-item">
                    <label>Image:</label>
                    <input type="hidden" name="slides[<?php echo $index; ?>][image]" value="<?php echo esc_url($slide['image']); ?>" class="image-url" />
                    <img src="<?php echo esc_url($slide['image']); ?>" class="preview-image" style="max-width: 100px; margin-bottom: 10px; <?php echo empty($slide['image']) ? 'display:none;' : ''; ?>" />
                    <button type="button" class="upload-image button">Upload Image</button>
                    <label>Title:</label>
                    <input type="text" name="slides[<?php echo $index; ?>][title]" value="<?php echo esc_attr($slide['title']); ?>" class="regular-text" />
                    <label>Button Link:</label>
                    <input type="url" name="slides[<?php echo $index; ?>][button_link]" value="<?php echo esc_url($slide['button_link']); ?>" class="regular-text" />
                    <button type="button" class="remove-slide button-link">Remove</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" id="add_slide" class="button button-primary">+ Add Slide</button>

    <style>
        .repeater-item {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .repeater-item input {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }
        .preview-image {
            display: block;
            margin-bottom: 10px;
        }
        .remove-slide {
            color: red;
        }
    </style>

    <script>
        jQuery(document).ready(function ($) {
            $("#add_slide").on("click", function () {
                const container = $("#slider_repeater");
                const index = container.find(".repeater-item").length;

                const repeaterHTML = `
                    <div class="repeater-item">
                        <label>Image:</label>
                        <input type="hidden" name="slides[${index}][image]" class="image-url" />
                        <img src="" class="preview-image" style="max-width: 100px; margin-bottom: 10px; display: none;" />
                        <button type="button" class="upload-image button">Upload Image</button><br>
                        <label>Title:</label>
                        <input type="text" name="slides[${index}][title]" class="regular-text" />
                        <label>Button Link:</label>
                        <input type="url" name="slides[${index}][button_link]" class="regular-text" />
                        <button type="button" class="remove-slide button-link">Remove</button>
                    </div>
                `;
                container.append(repeaterHTML);
            });

            $(document).on("click", ".upload-image", function (e) {
                e.preventDefault();

                const button = $(this);
                const input = button.siblings(".image-url");
                const preview = button.siblings(".preview-image");

                const customUploader = wp.media({
                    title: "Select or Upload Image",
                    button: {
                        text: "Use this image",
                    },
                    multiple: false,
                });

                customUploader.on("select", function () {
                    const attachment = customUploader.state().get("selection").first().toJSON();
                    input.val(attachment.url);
                    preview.attr("src", attachment.url).show();
                });

                customUploader.open();
            });

            $(document).on("click", ".remove-slide", function () {
                $(this).closest(".repeater-item").remove();
            });
        });
    </script>
    <?php
}

function enqueue_admin_scripts($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

function save_slider_meta_box($post_id) {
    if (!isset($_POST['slider_meta_nonce']) || !wp_verify_nonce($_POST['slider_meta_nonce'], 'save_slider_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['slides']) && is_array($_POST['slides'])) {
        $sanitized_slides = array_map(function($slide) {
            return [
                'image' => esc_url_raw($slide['image'] ?? ''),
                'title' => sanitize_text_field($slide['title'] ?? ''),
                'button_link' => esc_url_raw($slide['button_link'] ?? ''),
            ];
        }, $_POST['slides']);
        update_post_meta($post_id, '_homepage_slides', $sanitized_slides);
    } else {
        delete_post_meta($post_id, '_homepage_slides');
    }
}
add_action('save_post', 'save_slider_meta_box');
