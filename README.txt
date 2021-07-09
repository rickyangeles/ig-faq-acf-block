https://www.ibenic.com/configuring-webpack-wordpress/

For block js.  multiple blocks and when acf updates on editor
https://www.advancedcustomfields.com/resources/acf_register_block_type/


Individual js for each block

```js
(function($){

    /**
     * initializeBlock
     *
     * Adds custom JavaScript to the block HTML.
     *
     * @date    15/4/19
     * @since   1.0.0
     *
     * @param   object $block The block jQuery element.
     * @param   object attributes The block attributes (only available when editing).
     * @return  void
     */
    var initializeBlock = function( $block ) {
        $block.find('img').doSomething();
    }

    // Initialize each block on page load (front end).
    $(document).ready(function(){
        $('.testimonial').each(function(){
            initializeBlock( $(this) );
        });
    });

    // Initialize dynamic block preview (editor).
    if( window.acf ) {
        window.acf.addAction( 'render_block_preview/type=testimonial', initializeBlock );
    }

})(jQuery);
```

Thinking of creating on frontend and backend an object ThemeJs with all common functions, and external libraries
attached, too.  Use Gulp/Webpack to combine the frontend and backend js.  Gulp can then go through Template-parts js
files and babel and minimize.

Or keeping all block js in a single file and using the window.acf to test and run for editor.

I'm assuming the acf_register_block_type 'enqueue_script' parameter will load the js only if the block is used on the page.

Which is better? Always loading the block js whether or not the block is on the page, or loading multiple js files for
multiple blocks?
