    <!-- HTML File for Front End to show listing  -->

    <div class="container-fluid">
        <div class="container blog-container referencer-block">
            <?php
            // WP Query
            $args = array(
                'post_type'        => 'referanser', //Your CPT/Posts Name
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page'   => 6,
            );
            ?>
            <?php

            $query = new WP_Query($args);
            $totalpost = $query->found_posts;
            $projectcount = 4;
            if ($query->have_posts()) {


            ?>
                <div class="row paddingLR15 ref_listing blog_list" data-offset="6">
                    <?php   // data-offset="6"  ,  "blog_list": class to append remaining list 
                    while ($query->have_posts()) {
                        $query->the_post();
                        $myExcerpt = get_the_excerpt();
                        $tags = array("<p>", "</p>");
                        $myExcerpt = str_replace($tags, "", $myExcerpt);
                        $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full');
                        $button_text = get_field('button_text'); // Your ACF Fields
                        $author_name = get_field('author_name');
                    ?>
                        <?php if ($projectcount >= 4 && $projectcount <= 6) { ?>
                            <div class="blog-refferencer blogmarginTB">
                                <article>
                                    <?php if ($url) { ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="img-wrapper" style="background-image: url('<?php echo $url; ?>')"></div>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="img-wrapper" style="background-image: url('<?php bloginfo('template_url'); ?>/images/placeholder.png')"></div>
                                        </a>
                                    <?php } ?>
                                    <div class="text-wrapper">
                                        <a href="<?php the_permalink(); ?>">
                                            <h4><?php echo the_title(); ?></h4>
                                        </a>
                                        <?php if (has_excerpt()) { ?>
                                            <p class="regular-text">
                                                <?php
                                                $string = get_the_excerpt();
                                                $words = explode(" ", $string);
                                                $finalStr = implode(" ", array_splice($words, 0, 14));
                                                if (strlen($string) > 14) {
                                                    echo $finalStr . "...";
                                                } else {
                                                    echo $finalStr;
                                                }  ?>
                                            </p>
                                        <?php } ?>
                                        <?php if ($button_text) { ?>
                                            <div class="btn-wrapper">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php echo $button_text; ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                <?php }
                        $projectcount = $projectcount + 1;
                    } // end while
                } // end if 
                ?>
                </div>
                <?php
                // new Row
                ?>

                <div class="ref-box-loader-wrap"><span class="ref-loader"></span></div>
                <!-- Div to show loader while loading the Posts (optional) -->
        </div>

    </div>