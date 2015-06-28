<?php

class AdminBarLayoutSelection {
    /**
     * Das alte Layout
     * @var
     */
    private $old_layout;
    /**
     * Die Dokumentenid
     * @var
     */
    private $doc_id;


    /**
     * Erstellt die Auswahl des Layouts.
     *
     * $oldLayout muss übergeben werden, damit der POST-Request vollständig ist.
     *
     * @param $doc_id string ID des Dokumentes.
     * @param $oldLayout string Name des alten Layouts.
     */
    public function __construct($doc_id, $oldLayout){
        $this->doc_id = $doc_id;
        $this->old_layout = $oldLayout;
        add_action('admin_bar_menu', array($this,'showLayoutSelection'), 997); ///998 ist die Priorität
    }

    /**
     * Erstellt die Auswahl in der admin_bar.
     *
     * @param $wp_admin_bar
     */
    public function showLayoutSelection($wp_admin_bar){

        $layout_parent = array(
                'id' => 'select_layout',
                'title' => 'Layout Auswählen',
                'meta' => array()
        );
        $wp_admin_bar->add_node($layout_parent);

        $layout1 =  array(
            'id' => 'layout1',
            'title' => $this->generateTitle('Layout 1', 'default'),
            'parent' => 'select_layout',
            'meta' => array()
        );

        $wp_admin_bar->add_node($layout1);

        $layout2 =  array(
            'id' => 'layout2',
            'title' => $this->generateTitle('Layout 2', 'sphinxdoc'),
            'parent' => 'select_layout',
            'meta' => array()
        );

        $wp_admin_bar->add_node($layout2);

        $layout3 =  array(
            'id' => 'layout3',
            'title' => $this->generateTitle('Layout 3', 'agogo'),
            'parent' => 'select_layout',
            'meta' => array()
        );

        $wp_admin_bar->add_node($layout3);

        $layout4 =  array(
            'id' => 'layout4',
            'title' => $this->generateTitle('Layout 4', 'nature'),
            'parent' => 'select_layout',
            'meta' => array()
        );

        $wp_admin_bar->add_node($layout4);

        $layout5 =  array(
            'id' => 'layout5',
            'title' => $this->generateTitle('Layout 5', 'scrolls'),
            'parent' => 'select_layout',
            'meta' => array()
        );

        $wp_admin_bar->add_node($layout5);
    }


    /**
     * Erstellt das HTMl für eine Layout-Option.
     *
     * @param $title string Title des Layouts.
     * @param $layoutValue string Name des Layouts
     * @return string HTML
     */
    private function generateTitle($title, $layoutValue){
        $content = '<form action="" method="post">
<input type="hidden" name="operation" value="selectLayout"/>
<input type="hidden" name="old_layout" value="'.$this->old_layout.'"/>
<input type="hidden" name="document_id" value="'.$this->doc_id.'"/>
<input type="hidden" name="selectedLayout" value="'.$layoutValue.'">
                <button type="submit" >'.$title.'</button></form>';
        return $content;
    }

}