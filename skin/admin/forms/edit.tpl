{assign var="collectionformAction" value=[
"test"=>"Test",
"production"=>"Production"
]}
<form id="forms_plugins_ogone" method="post" action="">
    <div class="row">
        <div class="form-group">
            <div class="col-lg-6 col-md-6">
                <label for="pspid_og">PSPID  :</label>
                <input type="text" class="form-control" id="pspid_og" name="pspid_og" value="{$dataOgone.pspid}" size="50" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <label for="passphrase_og">Passphrase :</label>
            <input type="text" class="form-control" id="passphrase_og" name="passphrase_og" value="{$dataOgone.passphrase}" size="50" />
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <div class="col-lg-3 col-md-3">
                <label for="formaction_og">Action :</label>
                <select class="form-control" id="formaction_og" name="formaction_og">
                    <option value="">Sélectionner une action</option>
                    {foreach $collectionformAction as $key => $value}
                        {$selected  =   ''}
                        {if $dataOgone.formaction == $key}
                            {$selected  =   ' selected'}
                        {/if}
                        <option{$selected} value="{$key}">{$value}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div class="btn-row">
        <input type="submit" class="btn btn-primary" value="{#send#|ucfirst}" />
    </div>
</form>