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
use Nubesys\Core\Ui\Components\Portal\V2\ContactoForm;
use Nubesys\Core\Ui\Components\Portal\V2\Message;

use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class Portalv2contacto extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        $this->addJsSource("https://www.google.com/recaptcha/api.js");
        
        $this->setTitle($this->getLocal("title"));

        $showRecervas                               = false;
        
        if($this->hasPostParam("contacto_nombre") && $this->hasPostParam("contacto_email") && $this->hasPostParam("contacto_telefono")){

            $contacto_menssaje                      = ($this->hasPostParam("contacto_menssaje")) ? $this->getPostParam("contacto_menssaje") : "";

            //SEND EMAIL TO ADMIN
            $toContactNotificationEmailTemplate     = "<strong>Nueva solicitud de contacto en www.NOSVAMOOS.com</strong><hr />
                                                                <strong>Cliente</strong>: " . $this->getPostParam("contacto_nombre") . "<br />
                                                                <strong>Teléfono</strong>: " . $this->getPostParam("contacto_telefono") . "<br />
                                                                <strong>Email</strong>: " . $this->getPostParam("contacto_email") . "<br />
                                                                <strong>Mensage</strong>: " . $contacto_menssaje . "<br />";            

            $this->sendEmail("Nueva solicitud de contacto en www.NOSVAMOOS.com", $this->getPostParam("contacto_email"), $this->getPostParam("contacto_nombre"), "ventas@nosvamoos.com", "Sales Team", $toContactNotificationEmailTemplate);

            $message                                = new Message($this->getDI());

            $messageIcon                            = "check circle outline green";
            $messageTitle                           = "¡VAMOOS!";
            $messageText                            = "Tu datos fueron enviados con éxito.<br /> En la brevedad un Asesor de Viajes se pondrá en contacto con Usted.";

            $messageParams                          = array();
            $messageParams['title']                 = $messageTitle;
            $messageParams['icon']                  = $messageIcon;
            $messageParams['message']               = $messageText;
            
            $this->addMainSection($message, $messageParams);

        }else{

            $contacto                               = new ContactoForm($this->getDI());

            $contactoParams                         = array();

            $this->addMainSection($contacto, $contactoParams);
        }

        //ROBOTS
        $this->addMetaTag("robots", "noindex, nofollow");  
    }
}
