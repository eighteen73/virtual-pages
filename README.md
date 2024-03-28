# WordPress Custom Virtual Pages

Allows for creation of ad-hoc URLs from plugins, while following all standard WP page and content hooks.

Shortcodes can be included in content, allowing other code to be dynamically introduced where needed.

## An example

```php

<?php

\Eighteen73\VirtualPages\VirtualPages::init();

add_action( 'virtual_pages', function ( \Eighteen73\VirtualPages\Controller $controller ) {

   // first page
   $controller->addPage( new \Eighteen73\VirtualPages\VirtualPage( "/custom/page" ) )
     ->setTitle( 'My First Custom Page' )
     ->setContent( '<p>Hey, this is my first custom virtual page!</p>' )
     ->setTemplate( 'custom-page.php' );

   // second page
   $controller->addPage( new \Eighteen73\VirtualPages\VirtualPage( "/my/custom/gallery" ) )
     ->setTitle( 'My Custom Gallery Virtual Page' )
     ->setContent( '[gallery ids="27,35,48"]' )  // is possible to put shortcodes in content
     ->setTemplate( 'custom-gallery.php' );

} );

```

## Credits

Heavily based on [https://gist.github.com/gmazzap/1efe17a8cb573e19c086](https://gist.github.com/gmazzap/1efe17a8cb573e19c086) by Giuseppe Mazzapica.
