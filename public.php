<?php
require('Ogone/Form.php');
use Ogone\Form;
class plugins_ogone_public extends DBOgone{
    protected $template,$modelSystem;
    /**
     * @var array
     */
    public $urlogone = array(
        'seturlok'          =>'/payment/success/',
        'seturlnook'        =>'/payment/success/',
        'seturlcancel'      =>'/payment/cancel/',
        'seturlexception'   =>'/payment/exception/',
        'seturlack'         =>'/payment/process/'
    );
    /**
     * constructeur
     */
    public function __construct()
    {
        $this->template = new frontend_controller_plugins();
        $this->modelSystem = new magixglobal_model_system();
    }
    public function setUrl(){
        return $this->urlogone;
    }
    /**
     * Retourne les données enregistrées dans la base de données pour le compte ogone
     * @return array
     */
    private function setData(){
        $data = parent::selectOne();
        return array(
            'pspid'       =>  $data['pspid_og'],
            'passphrase'  =>  $data['passphrase_og'],
            'formaction'  =>  $data['formaction_og']
        );
    }

    /**
     * @param $setParams
     * @return string
     */
    public function getData($setParams){
        //Charge le fichier de traduction
        frontend_model_smarty::getInstance()->configLoad(
            $this->modelSystem->base_path().'plugins/ogone/i18n/public_local_'.frontend_model_template::current_Language().'.conf'
        );
        $data = $this->setData();
        if($data['formaction'] === 'test'){
            $urlOgone = Form::OGONE_TEST_URL;
        }elseif($data['formaction'] === 'production'){
            $urlOgone = Form::OGONE_PRODUCTION_URL;
        }
        $url =  magixcjquery_html_helpersHtml::getUrl().'/'.frontend_model_template::current_Language().'/';
        // Define form options
        // See Ogone_Form for list of supported options
        $options = array(
            'sha1InPassPhrase' => $data['passphrase'],
            'formAction'       => $urlOgone,
            'formSubmitType'   => 'image',
            'formSubmitImageUrl'   => $setParams['formSubmitImageUrl'],
            'formSubmitButtonName'=> $this->template->getConfigVars('form_submit_button'),
            'formSubmitButtonValue'=> $this->template->getConfigVars('form_submit_button'),
            'formSubmitButtonClass'=> frontend_model_template::current_Language().'_'.'ogoneSubmitButton'
        );
        $seturl = $this->setUrl();
        // https://github.com/jvandemo/Ogone
        // Define form parameters (see Ogone documentation for list)
        // Default parameter values can be set in Ogone_Form if required
        $params = array(
            'PSPID'         => $data['pspid'],
            'orderID'       => 'your_order_id / transaction ID',
            'amount'        => /*'amount transaction'*/100,
            'currency'      => 'EUR',
            'language'      => 'en_EN',
            'COMPLUS'       => 'custom data',
            'OPERATION'     => 'SAL',
            'CN'            => 'name of your client',
            'EMAIL'         => 'email of your client',
            'accepturl'     => $url.$setParams['plugin'] .$seturl['seturlok'],
            'declineurl'    => $url.$setParams['plugin'] .$seturl['seturlnook'],
            'exceptionurl'  => $url.$setParams['plugin'] .$seturl['seturlexception'],
            'cancelurl'     => $url.$setParams['plugin'] .$seturl['seturlcancel'],
        );
        // Instantiate form
        $form = new Form($options, $params);

        // You can also add parameters after instantiation
        // with the addParam() method
        $form->addParam('CN', $setParams['lastname'].' '.$setParams['firstname'])
            ->addParam('EMAIL', $setParams['email'])
            ->addParam('language', $setParams['language'])
            ->addParam('orderID', $setParams['transaction'])
            ->addParam('amount', $setParams['amount'])
            ->addParam('COMPLUS', $setParams['COMPLUS'])
            ->addParam('PARAMVAR', $setParams['PARAMVAR']);

        // Automatically generate HTML form with all params and SHA1Sign
        return $form->render();
    }
}
class DBOgone
{
    /**
     * @return array
     */
    protected function selectOne()
    {
        $query = 'SELECT og.*
            FROM mc_plugins_ogone AS og';
        return magixglobal_model_db::layerDB()->selectOne($query);
    }
}