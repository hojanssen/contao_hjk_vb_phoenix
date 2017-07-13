<?php

namespace HJK\VbPhoenix;


class CESchedule extends \ContentElement {

        /**
         * Template
         * @var string
         */
        protected $strTemplate = 'ce_hjk_vbphoenix_schedule';


        public function generate () {
            
            if ( TL_MODE == "BE") {
                $template = new \BackendTemplate('be_wildcard');

                $template->wildcard = 
                        "### (HJK) VB-Phoenix: Spielplan ###";
                
                $template->title = $this->headline;
                $template->id = $this->id;
                //$template->link = $this->name;
                //$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

                return $template->parse();
            } else {
                return parent::generate();
            }

        }



        protected function compile () {
            $squadron = SquadronModel::findById ( $this->hjk_vbphoenix_squadron);
            
            $download = $squadron->getCurrentDownload ( "schedule", $this->hjk_vbphoenix_season);
            
            $this->Template->squadron = $squadron;
            
            if ( $download ) {
                if ( $this->hjk_vbphoenix_games != "all" && $this->hjk_vbphoenix_team ) {
                    $entries= array ();
                    $team = $this->hjk_vbphoenix_team;
                    $mode = $this->hjk_vbphoenix_games;
                    
                    foreach ( $download->getDbEntries() as $entry ) {
                        if ( ($entry->team_home == $team && ($mode == "team" || $mode == "team_home"))
                                || ( $entry->team_guest == $team && ( $mode == "team" || $mode == "team_away") ) ) {
                            $entries[] = $entry;
                        }
                    }
                } else {
                    $entries = $download->getDbEntries ();
                }
                
                $this->Template->entries = $entries;
                $this->Template->download = $download;
                $this->Template->team = $this->hjk_vbphoenix_team;
            } else {
                $this->Template->downloadError = true;
            }
        }

}