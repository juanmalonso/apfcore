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
use Nubesys\Core\Ui\Components\Portal\V2\ReservaForm;
use Nubesys\Core\Ui\Components\Portal\V2\Message;

use Nubesys\Data\DataSource\DataSource;
use Nubesys\Data\DataSource\DataSourceAdapters\Objects as ObjectsDataSource;

class Portalv2reserva extends PortalPage {

    //MAIN ACTION
    public function mainAction(){

        parent::mainAction();

        $this->addJsSource("https://www.google.com/recaptcha/api.js");
        
        $this->setTitle($this->getLocal("title"));

        $contenidoDetalleData                       = $this->getContenidoDetalle();

        $showExperiencias                           = false;
        
        if($this->hasPostParam("reserva_token")){

            if($this->validateReservaToken($this->getPostParam("reserva_token"), $contenidoDetalleData['id'])){

                $clienteData                            = array();
                $clienteData['reserva_nombres']         = $this->getPostParam("reserva_nombres");
                $clienteData['reserva_apellidos']       = $this->getPostParam("reserva_apellidos");
                $clienteData['reserva_email']           = $this->getPostParam("reserva_email");
                $clienteData['reserva_telefono']        = $this->getPostParam("reserva_telefono");

                $saveClienteResult                      = $this->addCliente($clienteData);

                if($saveClienteResult !== false){

                    $expedienteData                     = array();
                    $expedienteData['paquete']          = $contenidoDetalleData['id'];
                    $expedienteData['cliente']          = $saveClienteResult;
                    $expedienteData['message']          = $this->getPostParam("reserva_message");

                    $categoriasTmp                      = array();
                    foreach($contenidoDetalleData['categorias'] as $categoria){

                        $categoriasTmp[]                = $categoria['id'];
                    }

                    $expedienteData['categorias']       = $categoriasTmp;

                    $expedienteData['destino']          = $contenidoDetalleData['destino']['id'];

                    $saveExpedienteResult               = $this->addExpediente($expedienteData);

                    if($saveExpedienteResult !== false){
                        
                        $messageIcon                    = "check circle outline green";
                        $messageTitle                   = "¡VAMOOS!";
                        $messageText                    = "Tu solicitud fue enviada con éxito.<br /> En la brevedad un Asesor de Viajes se pondrá en contacto con Usted.";

                        $clienteFullName                = $clienteData['reserva_nombres'] . " " . $clienteData['reserva_apellidos'];

                        $toSalesNotificationEmailTemplate   = "<strong>Nueva reserva</strong><hr />
                                                                <strong>ID</strong>: " . $saveExpedienteResult . "<br />
                                                                <strong>Cliente</strong>: " . $clienteFullName . "<br />
                                                                <strong>Mensage</strong>: " . $expedienteData['message'] . "<br />";

                        //SEND EMAIL TO ADMIN
                        $this->sendEmail("Nueva reserva en www.nosvamoos.com", "administrador@nosvamoos.com", "Portal www.nosvamoos.com", "ventas@nosvamoos.com", "Equipo de ventas de www.nosvamoos.com", $toSalesNotificationEmailTemplate);

                        $toClientNotificationEmailTemplate      = "<strong>¡VAMOOS!</strong><hr />
                                                                    Muchas Gracias por su solicitud.<br />
                                                                    Este correo se trata de un pedido de presupuesto. El precio de los paquetes y/o servicios están sujetos a disponibilidad.<br />
                                                                    En la brevedad un Asesor de Viajes se pondrá en contacto con Usted.";

                        //SEND TANKYOUEMAIL TO USER
                        $this->sendEmail("Tu reserva en www.nosvamoos.com", "ventas@nosvamoos.com", "Equipo de ventas de www.nosvamoos.com", $clienteData['reserva_email'], $clienteFullName, $toClientNotificationEmailTemplate);

                        $showExperiencias               = true;

                    }

                }else {
                    
                    $messageIcon                    = "times circle outline red";
                    $messageTitle                   = "OOPPS!!";
                    $messageText                    = "Sentimos el inconveniente, pero la página que intentas solicitar no se encuentra disponible en este momento.<br />Por favor vuelve a intentarlo en unos minutos.";

                    $showExperiencias               = true;
                }
            }else{

                $messageIcon                    = "times circle outline red";
                $messageTitle                   = "OOPPS!!";
                $messageText                    = "Sentimos el inconveniente, pero la página que intentas solicitar no se encuentra disponible en este momento.<br />Por favor vuelve a intentarlo en unos minutos.";

                $showExperiencias                   = true;
            }

            $message                                = new Message($this->getDI());

            $messageParams                          = array();
            $messageParams['title']                 = $messageTitle;
            $messageParams['icon']                  = $messageIcon;
            $messageParams['message']               = $messageText;
            
            $this->addMainSection($message, $messageParams);

        }else{

            $reserva                                = new ReservaForm($this->getDI());

            $reservaParams                          = array();
            $reservaParams['imgSrcMap']             = $this->getLocal("detalles.sliderImgSrcMap");
            $reservaParams['data']                  = $contenidoDetalleData;
            $reservaParams['token']                 = $this->getReservaToken($contenidoDetalleData['id']);

            $this->addMainSection($reserva, $reservaParams);
        }

        if($showExperiencias){

            //EXPERIENCIAS
            $query                          = array();
            $query['rows']                  = 8;
            $query['filters']               = array();
            $query['filters']['parent']     = array("taxonomy_experiencias");

            $experiencias                           = new ImageCards($this->getDI());

            $experienciasParams                     = array();
            $experienciasParams['title']            = "Experiencias";
            $experienciasParams['urlLinkMap']       = $this->getLocal("experiencias.linkMap");
            $experienciasParams['bigImgSrcMap']     = $this->getLocal("experiencias.cardBigImgSrcMap");
            $experienciasParams['smallImgSrcMap']   = $this->getLocal("experiencias.cardSmallImgSrcMap");
            $experienciasParams['data']             = $this->getTopTaxonomies($query);
            $experienciasParams['size']             = "big";

            $this->addMainSection($experiencias, $experienciasParams);
        }

        //ROBOTS
        $this->addMetaTag("robots", "noindex, nofollow");  
    }

    protected function getReservaToken($p_id){
        $result                         = false;

        $tokenData                      = array();
        $tokenData['id']                = $p_id;
        
        $secret                         = $this->getDI()->get('config')->crypt->privatekey;

        return \Nubesys\Core\Utils\Utils::jwtGenerate($secret, $tokenData, 500);
    }

    //EDITOR TOKEN
    protected function validateReservaToken($p_token, $p_id){
        
        $result                         = false;

        $tokenData                      = array();
        $tokenData['id']                = $p_id;

        $secret                         = $this->getDI()->get('config')->crypt->privatekey;
        
        return \Nubesys\Core\Utils\Utils::jwtValidate($secret, $tokenData, $p_token);
    }

    protected function addCliente($p_data){

        $result = false;

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = "clientes";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $data                               = array();
        $data['_id']                        = md5($p_data['reserva_email']);
        $data['nombre']                     = $p_data['reserva_nombres'];
        $data['apellido']                   = $p_data['reserva_apellidos'];
        $data['telefono']                   = $p_data['reserva_telefono'];
        $data['email']                      = $p_data['reserva_email'];

        return                              $dataSource->addData($data);
    }

    protected function addExpediente($p_data){

        $result = false;

        $dataSourceOptions                  = array();
        $dataSourceOptions['model']         = "expedientes";

        $dataSource                         = new DataSource($this->getDI(), new ObjectsDataSource($this->getDI(), $dataSourceOptions));

        $data                               = array();
        $data['paquete']                    = $p_data['paquete'];
        $data['cliente']                    = $p_data['cliente'];
        $data['message']                    = $p_data['message'];
        $data['categorias']                 = $p_data['categorias'];
        $data['destino']                    = $p_data['destino'];

        return                              $dataSource->addData($data);
    }
}
