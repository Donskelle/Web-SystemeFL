<?php


class AdminBarGroupSelection {
    /**
     * Array mit den Gruppen, denen das Dokument zugewiesen werden darf.
     * @var
     */
    private $groups;
    /**
     * Die ID des Dokumentes.
     * @var
     */
    private $document_id;

    /**
     * Erstellt die Auswahl der Gruppen für das Dokument.
     *
     * @param $groups
     * @param $doc_id
     */
    public function __construct($groups, $doc_id){
        $this->groups = $groups;
        $this->document_id = $doc_id;

        add_action('admin_bar_menu', array($this,'showGroupSelection'), 999); ///998 ist die Priorität
    }


    /**
     * Erstellt das admin_bar Element.
     *
     * Wenn das Dokument einer Gruppe zugewiesen ist, wird dies angezeigt.
     *
     * @param $wp_admin_bar
     */
    public function showGroupSelection($wp_admin_bar)
    {

        $bActiveGroup = false;
        $aktiveGroup = null;
        $newGroupArray = [];

        //aktive gruppe/zugewiesene gruppe
        if ($this->groups['active'] != "") {
            $bActiveGroup = true;
            //finde die aktive gruppe
            for ($i = 0; $i < count($this->groups['groups']); $i++) {
                if ($this->groups["groups"][$i]->id == $this->groups["active"]->group_id) {
                    $aktiveGroup = $this->groups["groups"][$i];
                }
            }

        }

        //Ist eine aktive Gruppe zugewiesen?
        if ($bActiveGroup == true) {
            $selection_parent = array(
                'id' => 'select_group',
                'title' => 'Aktuelle Gruppe: ' . $aktiveGroup->name
            );
        } else {
            $selection_parent = array(
                'id' => 'select_group',
                'title' => 'Keine zugewiesene Gruppe'
            );
        }
        $wp_admin_bar->add_node($selection_parent);


        for ($i = 0; $i < count($this->groups['groups']); $i++) {
            $groupId = $this->groups['groups'][$i]->id;
            $groupName = $this->groups['groups'][$i]->name;

            $groupOption = array(
                'id' => $groupId,
                'title' => $this->buildOption($groupId, $groupName),
                'parent' => 'select_group'
            );
            $wp_admin_bar->add_node($groupOption);
        }

    }


    /**
     * Erstellt das HTML für die Auswahloptionen.
     *
     * @param $group_id string Gruppen ID
     * @param $group_name string Gruppenname
     * @return string HTML für die Option.
     */
    private function buildOption($group_id, $group_name){
        $content = '<form method="post" action="">
<input type="hidden" value="selectGroup" name="operation">
<input type="hidden" value="'.$this->document_id.'" name="document_id">
<input type="hidden" value="'.$group_id.'" name="selectedGroup">
<button type="submit">'.$group_name.'</button>
</form>';
        return $content;
    }
}