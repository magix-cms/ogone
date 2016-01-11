var MC_Ogone = (function($, window, document, undefined){
    /**
     * set ajax load data
     * @param type
     * @param baseadmin
     * @param getlang
     * @param edit
     * @returns {string}
     */
    function setAjaxUrlLoad(baseadmin){
        return '/'+baseadmin+'/plugins.php?name=ogone';
    }
    /**
     * Save
     * @param id
     * @param collection
     * @param type
     */
    function save(baseadmin,id){
        $(id).validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                pspid_og: {
                     required: true,
                     minlength: 2
                 },
                 passphrase_og: {
                     required: true,
                     minlength: 2
                 },
                formaction_og: {
                    required: true
                }
            },
            submitHandler: function(form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: setAjaxUrlLoad(baseadmin),
                    typesend: 'post',
                    idforms: $(form),
                    resetform: false,
                    beforeParams:function(){},
                    successParams:function(e){
                        $.nicenotify.initbox(e,{
                            display:true
                        });
                    }
                });
                return false;
            }
        });
    }
    return {
        run:function(baseadmin){
            save(baseadmin,'#forms_plugins_ogone');
        }
    }
})(jQuery, window, document);