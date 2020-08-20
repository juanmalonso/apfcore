<?php

namespace Nubesys\Core\Ui\Pages;

use Nubesys\Vue\Services\VueUiService;

//PAGE
use Nubesys\Core\Ui\Pages\PortalPage;

//COMPONENTS
use Nubesys\Core\Ui\Components\Portal\V2\Slider;
use Nubesys\Core\Ui\Components\Portal\V2\ImageCards;
use Nubesys\Core\Ui\Components\Portal\V2\ContentCards;
use Nubesys\Core\Ui\Components\Portal\V2\ContentDetail;
use Nubesys\Core\Ui\Components\Portal\V2\HtmlContent;
use Nubesys\Core\Ui\Components\Portal\V2\Message;

use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class Portalv2nosotros extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();
        
        $this->setTitle($this->getLocal("title"));

        $content                                = new HtmlContent($this->getDI());

        $contentParams                          = array();
        $contentParams['content']               = $this->getContent();

        $this->addMainSection($content, $contentParams);

        //ROBOTS
        $this->addMetaTag("robots", "index, follow");  
    }

    private function getContent(){

        $result = "<h1>hola mundo</h1>";


        return $result;
    }
}
