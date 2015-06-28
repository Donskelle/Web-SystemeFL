<?php


add_action('wp_enqueue_scripts', 'add_view_stylesheet' );

function add_view_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'dm-view-style', plugins_url('Views/css/view.css', __FILE__) );
    wp_enqueue_style( 'dm-view-style' );
}
function frontendController(){
    new ShowCustomField();
}


class ShowCustomField {
    public function __construct(){
        add_action('the_content', array($this, 'add_function_to_page'));
    }

    
    public function add_function_to_page($content)
    {
        if ( is_page() )
        {
            $id = get_the_ID();
            $meta = get_post_meta($id, 'custom_element_grid_class_meta_box', true);
            if($meta != null)
            {
                switch ($meta) {
                    case 'Keine':
                        $content .= "Keine";
                        break;

                    case 'Startseite':
                        $content .= "Willkommen auf DokuMummy Worpress";
                        break;

                    case 'Dokumente':
                        //$content .= "Dokumente";
                        require_once("Views/DokumentView.php");
                        break;

                    case 'Gruppen':
                        require_once("Views/GroupView.php");
                        break;

                    default:
                        $content .= "defaultController";
                        break;
                }
            }
        }

        return $content;
    }
}