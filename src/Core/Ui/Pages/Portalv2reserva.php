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
                        //$this->sendEmail("Nueva reserva en www.NOSVAMOOS.com", "manialonso@gmail.com", "Portal NOSVAMOOS", "manialonso@gmail.com", "Sales Team", $toSalesNotificationEmailTemplate);

                        $toClientNotificationEmailTemplate      = "<strong>Gracias por su reserva!!</strong><hr />
                                                                    En la brevedad posible, uno de nuestros agentes se pondrá en contacto con usted!";

                        //SEND TANKYOUEMAIL TO USER
                        //$this->sendEmail("Tu reserva en www.NOSVAMOOS.com", "manialonso@gmail.com", "Portal NOSVAMOOS", $clienteData['reserva_email'], $clienteFullName, $toClientNotificationEmailTemplate);

                    }

                }else {
                    
                    $messageIcon                    = "times circle outline red";
                    $messageTitle                   = "¡OPS!";
                    $messageText                    = "No se pudieron enviar los datos!";
                }
            }else{

                $messageIcon                        = "times circle outline red";
                $messageTitle                       = "¡OPS!";
                $messageText                        = "No se pudieron enviar los datos!";
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

        //ROBOTS
        $this->addMetaTag("robots", "noindex, nofollow");  
    }

    private function sendEmail($p_subject, $p_fromEmail, $p_fromName, $p_toEmail, $p_toName, $p_template){
        $result                 = true;

        $curl                   = curl_init();

        $data                   = new  \stdClass();

        $data->sender           = new  \stdClass();
        $data->sender->name     = $p_fromName;
        $data->sender->email    = $p_fromEmail;

        $data->to               = array();
        $toTmp                  = new  \stdClass();
        $toTmp->name            = $p_toName;
        $toTmp->email           = $p_toEmail;
        $data->to[]             = $toTmp;

        $data->htmlContent      = $p_template;
        $data->subject          = $p_subject;


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "api-key: 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",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        /*/
        var_dump($response);
        var_dump($err);
        exit();
        //*/

        if ($err) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
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
