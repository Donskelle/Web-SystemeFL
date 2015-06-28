<?php


class AdminBarDownloadOption {

    /**
     * @var
     */
    private $pdf_link;
    /**
     * @var
     */
    private $zip_link;
    /**
     * @var mixed
     */
    private $doc_name;

    /**
     * Fügt der admin_bar die Downloadoptionen hinzu.
     *
     * @param $pdf_link string Link zum PDF-Download
     * @param $zip_link string Link zum ZIP-Download
     * @param $doc_name string Name des Projektes
     */
    public function __construct($pdf_link, $zip_link,  $doc_name){
        $this->pdf_link = $pdf_link;
        $this->zip_link = $zip_link;
        $this->doc_name = str_replace(' ', '',$doc_name);

        add_action('admin_bar_menu', array($this,'showDownloadOptions'), 998); ///998 ist die Priorität
    }


    /**
     * @param $wp_admin_bar
     */
    public function showDownloadOptions($wp_admin_bar){

        $download_parent = array(
            'id' => 'download_parent',
            'title' => 'Download'
        );

        $wp_admin_bar->add_node($download_parent);


        $pdf_option = array(
            'id' => 'pdf_option',
            'title' => '<a href="'.$this->pdf_link.'" class="downloadLink" download="'.$this->doc_name.'.pdf" target="_blank">PDF</a>',
            'parent' => 'download_parent'
        );
        $wp_admin_bar->add_node($pdf_option);

        $zip_option = array(
            'id' => 'zip_option',
            'title' => '<a href="'.$this->zip_link.'" class="downloadLink" download="'.$this->doc_name.'.zip" target="_blank">ZIP</a>',
            'parent' => 'download_parent'
        );
        $wp_admin_bar->add_node($zip_option);
    }

}