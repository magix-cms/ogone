<?php
class plugins_ogone_admin extends DBOgone
{
    protected $header, $template, $message;
    public static $notify = array('plugin' => 'true');
    public $getlang, $plugin, $edit, $id, $getpage, $pspid_og, $passphrase_og, $formaction_og;

    /**
     * constructeur
     */
    public function __construct()
    {
        if (class_exists('backend_model_message')) {
            $this->message = new backend_model_message();
        }
        // Global
        if (magixcjquery_filter_request::isGet('getlang')) {
            $this->getlang = magixcjquery_filter_isVar::isPostNumeric($_GET['getlang']);
        }
        if (magixcjquery_filter_request::isGet('edit')) {
            $this->edit = magixcjquery_filter_isVar::isPostNumeric($_GET['edit']);
        }
        if (magixcjquery_filter_request::isGet('action')) {
            $this->action = magixcjquery_form_helpersforms::inputClean($_GET['action']);
        }
        if (magixcjquery_filter_request::isGet('tab')) {
            $this->tab = magixcjquery_form_helpersforms::inputClean($_GET['tab']);
        }
        // Dédié
        if (magixcjquery_filter_request::isGet('plugin')) {
            $this->plugin = magixcjquery_form_helpersforms::inputClean($_GET['plugin']);
        }
        if (magixcjquery_filter_request::isGet('id')) {
            $this->id = (integer)magixcjquery_filter_isVar::isPostNumeric($_GET['id']);
        }
        if (magixcjquery_filter_request::isPost('pspid_og')) {
            $this->pspid_og = magixcjquery_form_helpersforms::inputClean($_POST['pspid_og']);
        }
        if (magixcjquery_filter_request::isPost('passphrase_og')) {
            $this->passphrase_og = magixcjquery_form_helpersforms::inputClean($_POST['passphrase_og']);
        }
        if (magixcjquery_filter_request::isPost('formaction_og')) {
            $this->formaction_og = magixcjquery_form_helpersforms::inputClean($_POST['formaction_og']);
        }
        $this->header = new magixglobal_model_header();
        $this->template = new backend_controller_plugins();
    }

    /**
     * @access private
     * Installation des tables mysql du plugin
     */
    private function install_table($create)
    {
        if (parent::c_show_table() == 0) {
            $create->db_install_table('db.sql', 'request/install.tpl');
        } else {
            //$magixfire = new magixcjquery_debug_magixfire();
            //$magixfire->magixFireInfo('Les tables mysql sont installés', 'Statut des tables mysql du plugin');
            return true;
        }
    }

    /**
     * Prépare les données utilisateur
     * @param $id
     * @return array
     */
    private function setData($id){
        $data = parent::selectOne($id);
        return array(
            'pspid'       =>  $data['pspid_og'],
            'passphrase'  =>  $data['passphrase_og'],
            'formaction'  =>  $data['formaction_og']
        );
    }

    /**
     * Assign table data
     */
    private function getData(){
        $data = $this->setData($this->getlang);
        $this->template->assign('dataOgone', $data, true);
    }

    /**
     * @param $data
     */
    private function add($data){
        parent::insert($data);
    }

    /**
     * @param $data
     */
    private function update($data){
        parent::uData($data);
    }

    /**
     * @param $data
     */
    private function save($data){
        if($data['edit'] != null){
            $this->update($data);
            $this->message->getNotify('update',self::$notify);
        }else{
            $this->add($data);
            $this->message->getNotify('add',self::$notify);
        }
    }
    /**
     * Execute plugin
     */
    public function run()
    {
        if (self::install_table($this->template) == true) {
            if (isset($this->pspid_og)) {
                $control = parent::selectOne();
                $this->save(
                    array(
                        'edit'          =>  $control['idogone'],
                        'pspid'         =>  $this->pspid_og,
                        'passphrase'    =>  $this->passphrase_og,
                        'formaction'    =>  $this->formaction_og
                    )
                );
            }else{
                $this->getData();
                $this->template->display('list.tpl');
            }
        }
    }
    public function setConfig(){
        return array(
            'url'=> array(
                'lang'  => 'none',
                'action'=>''
            ),
            'icon'=> array(
                'type'=>'font',
                'name'=>'fa fa-credit-card'
            )
        );
    }
}
class DBOgone
{
    /**
     * Vérifie si les tables du plugin sont installé
     * @access protected
     * return integer
     */
    protected function c_show_table()
    {
        $table = 'mc_plugins_ogone';
        return magixglobal_model_db::layerDB()->showTable($table);
    }

    /**
     * @param $data
     * @return array
     */
    protected function selectOne()
    {
        $query = 'SELECT og.*
            FROM mc_plugins_ogone AS og';
        return magixglobal_model_db::layerDB()->selectOne($query);
    }
    /**
     * @param $idcatalog
     * @param $data
     */
    protected function insert($data){
        if(is_array($data)){
            $sql = 'INSERT INTO mc_plugins_ogone (pspid_og,passphrase_og,formaction_og)
		    VALUE(:pspid_og,:passphrase_og,:formaction_og)';
            magixglobal_model_db::layerDB()->insert($sql,
                array(
                    ':pspid_og'	       =>  $data['pspid'],
                    ':passphrase_og'   =>  $data['passphrase'],
                    ':formaction_og'   =>  $data['formaction']
                ));
        }

    }

    /**
     * @param $idcatalog
     * @param $data
     */
    protected function uData($data){
        if(is_array($data)){
            $sql = 'UPDATE mc_plugins_ogone
            SET pspid_og=:pspid_og,passphrase_og=:passphrase_og,formaction_og=:formaction_og
            WHERE idogone=:edit';
            magixglobal_model_db::layerDB()->update($sql,
                array(
                    ':edit'	           =>  $data['edit'],
                    ':pspid_og'	       =>  $data['pspid'],
                    ':passphrase_og'   =>  $data['passphrase'],
                    ':formaction_og'   =>  $data['formaction']
                ));
        }
    }
}
