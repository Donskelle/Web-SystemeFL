<?php
/**
 * Created by PhpStorm.
 * User: Jan
 * Date: 02.06.2015
 * Time: 10:10
 */

/**
 * Class CustomWidget
 *
 * Oberklasse für Backend Widgets.
 *
 * //http://code.tutsplus.com/tutorials/how-to-build-custom-dashboard-widgets--wp-29778
 */
abstract class CustomWidget {

    /**
     * Id des Widgets.
     * @var string
     */
    protected $widgetID = '';
    /**
     * Name/Titel des Widgets.
     * @var string
     */
    protected $widgetTitle = '';
    /**
     * Diese Variable darf nicht geändert werden.
     * @var string
     */
    private $callback_function_name = 'widget_content';


    /**
     * Konstruktor von CustomWidget
     *
     * Setzt id und Titel des Widgets und registriert es.
     */
    public function __construct($id, $title)
    {
        $this->set_widget_meta($id, $title);
        add_action('wp_dashboard_setup', array($this, 'add_dash_board_widget'));
    }
    /**
     * Setzt id und Namen des Widgets.
     * @param $id
     * @param $title
     * @return mixed
     */
    private function set_widget_meta($id, $title){
        $this->widgetID = $id;
        $this->widgetTitle = $title;
    }
    /**
     * Wrapperfunktion für wp_add_dashboard_widget.
     * Muss public sein, da WP es erfordert.
     * @return mixed
     */
    public function add_dash_board_widget(){
        wp_add_dashboard_widget(
            $this->widgetID,
            $this->widgetTitle,
            array($this, $this->callback_function_name)
        );
    }

    /**
     * Die Callbackfunktion des Widgets. Nur diese Funktion muss implementiert werden.
     * @return mixed
     */
    abstract public function widget_content();
}