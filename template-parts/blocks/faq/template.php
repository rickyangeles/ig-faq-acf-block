<?php

    //ACF Fields
    $qa         = get_field('qa');

    //Core Settings/Options
    $id = 'faq-' . $block['id'];
    if( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }
    $className = '';
    if( !empty($block['className']) ) {
        $className .= sprintf( ' %s', $block['className'] );
    }
    if( !empty($block['align']) ) {
        $className .= sprintf( ' align%s', $block['align'] );
    }

?>

<section class="block faq <?php echo $className; ?>" id="<?php echo $id; ?>">
    <?php if ( is_admin() && get_field('block_label') ): ?>
        <div class="block-label"><div><?php the_field('block_label'); ?></div></div>
    <?php endif; ?>
    <div class="container">
        <div class="columns">
            <div class="column">
                <?php if( have_rows('qa') ): ?>
                    <section class="accordions" id="accordion-<?php echo $block['id']; ?>">
                    <?php while( have_rows('qa') ): the_row(); $q = get_sub_field('field_60e4580abb395'); $a = get_sub_field('field_60e45810bb396'); ?>
                        <h3 class="q-header"><?php echo get_sub_field('field_60e4580abb395'); ?></h3>
                        <div class="a-content">
                            <?php echo $a; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
    jQuery(document).ready(function( $ ) {
        var icons = {
            header: "ui-icon-circle-arrow-e",
            activeHeader: "ui-icon-circle-arrow-s"
        };
        $( "#accordion-<?php echo $block['id']; ?>" ).accordion({
            icons: icons,
            heightStyle: 'content',
            collapsible: true,
            active: false
        });
        $('#accordion .accordion-toggle').click(function () {
            //Remove all rotate180
            $('.fa-plus').removeClass('fa-plus');
            //Now just add it for 'this'
            $(this).children('.fa-plus').addClass('fa-plus');
        });

        $('.schema_answer a').each(function(){
            this.href += '?a=text';
        })
    });
</script>
<!-- FAQ Schema -->


<?php if ( !is_admin() ) : ?>
    <?php if ( $qa ) : ?>
        <?php $c_count = 0; $q_count = count($qa); ?>
    <?php endif; ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [<?php while( have_rows('qa') ): the_row(); $c_count++; $q = get_sub_field('q'); $q = str_replace('"', "'", $q); $a = get_sub_field('a'); $a = str_replace('"', "'", $a);  ?>{
        "@type": "Question",
        "name": "<?php echo $q; ?>",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "<?php echo $a; ?>"
        }
    }<?php if ( $c_count < $q_count ) { echo ','; } ?><?php endwhile; ?>]
    }
</script>
<?php endif; ?>
