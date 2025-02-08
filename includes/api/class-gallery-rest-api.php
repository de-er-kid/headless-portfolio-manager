<?php

if (!defined('ABSPATH')) {
    exit;
}

class Portfolio_Gallery_REST_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_endpoints'));
    }

    public function register_endpoints() {
        register_rest_route('portfolio/v1', '/gallery', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_gallery'),
            'permission_callback' => '__return_true'
        ));
    }

    public function get_gallery() {
        $args = array(
            'post_type' => 'service',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        $posts = get_posts($args);
        $gallery_items = array();

        foreach ($posts as $post) {
            $images = get_post_meta($post->ID, '_service_gallery', true); // Fetch the meta value
            $image_ids = explode(',', $images);

            foreach ($image_ids as $image_id) {
                $image_id = trim($image_id);
                if (!empty($image_id)) {
                    $image_src = wp_get_attachment_image_src($image_id, 'full'); // Get full-size image URL
                    $image_title = get_the_title($image_id); // Get media title
                    $image_description = get_post_meta($image_id, '_wp_attachment_image_alt', true); // Get image alt text

                    if ($image_src) {
                        $gallery_items[] = array(
                            'src' => $image_src[0],
                            'category' => get_the_title($post->ID),
                            'title' => $image_title,
                            'description' => $image_description,
                        );
                    }
                }
            }
        }

        return new WP_REST_Response($gallery_items, 200);
    }
}

new Portfolio_Gallery_REST_API();


// next js variable:
// const galleryItems: GalleryItem[] = [
//   {
//     src: "/gallery/listing/Concert-Festival/event (6).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (1).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (2).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (3).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (4).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (5).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (8).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (9).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (10).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (11).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   // generate 12 - 25 images object for Concert/Festival
//   {
//     src: "/gallery/listing/Concert-Festival/event (12).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (13).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (14).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (15).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (16).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (17).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (18).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (19).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (20).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
  
//   {
//     src: "/gallery/listing/Concert-Festival/event (21).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Concert-Festival/event (22).webp",
//     category: "Concert/Festival",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
  
//     {
//       src: "/gallery/listing/Concert-Festival/event (23).webp",
//       category: "Concert/Festival",
//       title: "Lorum Ipsum Dolor Sit Amet",
//       description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//     },
  
//     {
//       src: "/gallery/listing/Concert-Festival/event (24).webp",
//       category: "Concert/Festival",
//       title: "Lorum Ipsum Dolor Sit Amet",
//       description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//     },
  
//     {
//       src: "/gallery/listing/Concert-Festival/event (25).webp",
//       category: "Concert/Festival",
//       title: "Lorum Ipsum Dolor Sit Amet",
//       description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//     },


//   // generate 13 wedding image objects
//   {
//     src: "/gallery/listing/Weddings/wedding (1).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (2).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (3).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (4).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (5).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (6).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (7).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (8).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (9).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (10).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },

//   {
//     src: "/gallery/listing/Weddings/wedding (11).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Weddings/wedding (12).webp",
//     category: "Weddings",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
  
//     {
//       src: "/gallery/listing/Weddings/wedding (13).webp",
//       category: "Weddings",
//       title: "Lorum Ipsum Dolor Sit Amet",
//       description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//     },


//   // family generate 8 images object
//   {
//     src: "/gallery/listing/Family/family (1).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (2).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (3).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (4).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (5).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (6).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (7).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Family/family (8).webp",
//     category: "Family",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   // 6 newborn images object
//   {
//     src: "/gallery/listing/Newborn/newborn (1).webp",
//     category: "Newborn",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Newborn/newborn (2).webp",
//     category: "Newborn",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Newborn/newborn (3).webp",
//     category: "Newborn",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Newborn/newborn (4).webp",
//     category: "Newborn",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Newborn/newborn (5).webp",
//     category: "Newborn",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Newborn/newborn (6).webp",
//     category: "Newborn",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   // 12 maternity images object
//   {
//     src: "/gallery/listing/Maternity/maternity (1).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (2).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (3).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (4).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (5).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (6).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (7).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (8).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (9).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (10).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (11).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Maternity/maternity (12).webp",
//     category: "Maternity",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   // 2 engagement images object
//   {
//     src: "/gallery/listing/Engagement/engagement (1).webp",
//     category: "Engagement",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   {
//     src: "/gallery/listing/Engagement/engagement (2).webp",
//     category: "Engagement",
//     title: "Lorum Ipsum Dolor Sit Amet",
//     description: "Lorum ipsum dolor sit amet, consectetur adipiscing elit."
//   },
//   // Add more items for each category
// ]