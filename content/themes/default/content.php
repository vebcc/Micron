<article>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>

        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" ><?php the_post_thumbnail();?></a>
        </div>
        <div class="article-details" >
            <div class="post-meta-frontpage">
                <?php the_category(', ');?>
            </div>
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></h2>
            <div class="post-meta-frontpage">
                <?php the_time('F jS, Y');?> |
                <?php comments_number( __('0 Comments','megadrive'),__('1 Comment','megadrive'),__('% Comments','megadrive') ); ?>
            </div>
            <?php the_excerpt(); ?>
        </div>
        <p class="red-bottom-line"></p>

        <?php } else {?>

        <div>
            <h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a></h2>
            <div class="post-meta-frontpage">
                <?php the_category(', ');?> |
                <?php the_time('F jS, Y');?> |
                <?php comments_number( __('0 Comments','megadrive'),__('1 Comment','megadrive'),__('% Comments','megadrive') ); ?>
            </div>
            <?php the_excerpt(); ?>
        </div>
        <p class="red-bottom-line"></p>

        <?php } ?>
    </div>
</article>
