<?php


/**
 * Class DocumentAbschnitt
 *
 * Stellt einen Abschnitt eines Sphinxprojekts dar.
 */
class DocumentAbschnitt {


    /**
     * Dateiname des Abschnitts
     * @var
     */
    private $fileName;
    /**
     * Inhalt des Abschnitts
     * @var
     */
    private $abschnittContent;
    /**
     * Interne Id des Abschnitts.
     *
     * Diese Id wird innerhalb von SphinxDocument erzeugt.
     *
     * @var
     */
    private $abschnittId;

    /**
     *
     * @param $filename string
     * @param $abschnitt_content string
     * @param $abschnittId string
     */
    public function __construct($filename, $abschnitt_content, $abschnittId){
        $this->fileName = $filename;
        $this->abschnittContent = $abschnitt_content;
        $this->abschnittId = $abschnittId;
     }

    /**
     * Gibt den Dateinamen des Abschnitts zurück
     *
     * @return string Name der Datei, die den Abschnitt enthält.
     */
    public function getFileName(){
        return $this->fileName;
    }

    /**
     *
     * @return mixed
     */
    public function getAbschnittContent(){
        return $this->abschnittContent;
    }


    /**
     * Setzt den Inhalt des Abschnitts
     * @param $content
     */
    public function setAbschnittContent($content){
        $this->abschnittContent = $content;
    }

    /**
     * Gibt die Id des ABschnitts zurück.
     * @return mixed
     */
    public function getAbschnittId(){
        return $this->abschnittId;
    }



}