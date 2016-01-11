{script src="/{baseadmin}/min/?f=plugins/{$pluginName}/js/admin.js" concat={$concat} type="javascript"}
<script type="text/javascript">
    $(function(){
        if (typeof MC_Ogone == "undefined"){
            console.log("MC_Ogone is not defined");
        }else{
            MC_Ogone.run(baseadmin);
        }
    });
</script>