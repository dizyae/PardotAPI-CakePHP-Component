<?php
/**
 * Pardot Component
 * Payscape Advisors (http://payscape.com)
 * @author Dustin Weaver <dustin.weaver@payscape.com>
 * @copyright August 18, 2015
 *
 * A collection of methods used for integrating the Pardot API
 *
 * Public Methods:
 * create_prospect, 
 */

App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');

class PardotComponent extends Component
{
    const pardot_url = 'https://pi.pardot.com/api/';

    //replace with pardot api user key
    const user_key = 'xxxxxxxxxxxx';

    private $campaign_id = '';
    /**
     * startup method
     *
     * @param Controller $controller
     * @return void
     * @throws exception
     *
     * Start up Component by loading controller
     */
    public function startup(Controller $controller)
    {
        $this->Controller = $controller;
    }

    /**
     * authenticate method
     *
     * Authenticate and get Pardot api key 
     */
    private function authenticate()
    {
        $HttpSocket = new HttpSocket();

        $data = array(
            'email'=>'marketing@payscape.com',
            'password'=>'0ow337!D0g',
            'user_key'=>self::user_key
        );

        $response = $HttpSocket->post(self::pardot_url . 'login/version/3', $data);
        $response_array = Xml::toArray(Xml::build($response->body));
        if(!$response->isOk())
        {
            //Todo send email to dev
            error_log(json_encode($response_array));
        }

        return $response_array['rsp']['api_key'];
    }

    /**
     * send_request method
     *
     * @param array $data
     *
     * requests an api key then assembles url with query string and sends request to api
     */
    private function send_request($data)
    {
        $api_key = $this->authenticate();
        
        if(!$api_key)
        {
            return false;
        }

        $data['params']['api_key'] = $api_key;
        $data['params']['user_key'] = self::user_key;

        $HttpSocket = new HttpSocket();
        $url = self::pardot_url . $data['object'] . '/version/3/do/' . $data['action'] . '/email/' . $data['email'] . '?';
        $url .= http_build_query($data['params']);
        $response = $HttpSocket->get($url);
        $response_array = Xml::toArray(Xml::build($response->body));
        if(!$response->isOk())
        {
            error_log(json_encode($response_array));
        }
        
        $this->campaign_id = '';
        return $response_array;
    }

    /**
     * create_prospect method
     *
     * @param string $email
     * @param array $params
     *
     * creates new prospect in pardot
     */
    public function create_prospect($email, $params)
    {
        $data = array(
            'object'=>'prospect',
            'action'=>'create',
            'email'=>$email,
            'params'=>$params
        );
        
        $data['params']['campaign_id'] = $this->campaign_id;

        return $this->send_request($data);
    }

    /**
     * set_campaign_id method
     *
     * @param string $campaign_id
     *
     * sets the campaign id of the pardot campaign to save to
     */
    public function set_campaign_id($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }
}

?>