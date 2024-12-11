# Custom Metaboxes for Product Post Type and Homepage Slider

This repository contains custom metaboxes for the `product` post type in WordPress, as well as a homepage slider metabox. These metaboxes enable you to manage dynamic sliders with image uploads, titles, button links, and color options for products directly from the WordPress admin panel.

## Features

1. **Homepage Slider Metabox**
   - Add repeatable fields for creating dynamic homepage sliders.
   - Upload images, add titles, and button links for each slide.
   - Add multiple slides dynamically.
   - Display the slider on the homepage with the provided data.

2. **Product Color Options Metabox**
   - Add a repeater field to set color options for the product.
   - Color picker field for selecting colors.
   - Add multiple color options to each product.

## Installation

### Steps to Install

1. Open your WordPress theme folder.
2. Locate and open the `functions.php` file in your theme directory.
3. Copy the provided custom metabox code from this repository.
4. Paste the code into your `functions.php` file.
5. Save the changes.

### Usage

#### Homepage Slider Metabox

1. In the WordPress admin panel, go to the **Pages** section.
2. Edit an existing page or create a new one.
3. Scroll down to find the **Homepage Slider** metabox.
4. Use the interface to:
   - Upload images for each slide.
   - Add titles for each slide.
   - Add button links for each slide.
5. Save the page to update the slider data.

#### Product Color Options Metabox

1. In the **Products** section of the WordPress admin panel, edit or create a new product.
2. Scroll down to find the **Product Color Options** metabox.
3. Use the interface to:
   - Select colors using the color picker.
   - Add multiple color options.
4. Save the product to update the color options.

## Example Code to Retrieve and Display the Slider

To display the slider on the front end (e.g., in `single-page.php`), use the following code:

```php
<?php
$slides = get_post_meta(get_the_ID(), '_homepage_slides', true);

if (!empty($slides)) : ?>
    <div class="slides">
        <?php foreach ($slides as $slide) : ?>
            <div class="slide" style="background-image: url(<?php echo esc_url($slide['image']); ?>);">
                <div class="slideDivWrap">
                    <div class="slideHeadText"><?php echo esc_html($slide['title']); ?></div>
                    <div class="capitalizeAll slideBtn">
                        <a href="<?php echo esc_url($slide['button_link']); ?>" class="noUnderlineNoColor" title="View Collection">View Collection</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

