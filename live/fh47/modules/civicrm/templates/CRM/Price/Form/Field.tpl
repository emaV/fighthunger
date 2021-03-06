{*Javascript function controls showing and hiding of form elements based on html type.*}
{literal}
<script type="text/Javascript">
    function option_html_type(form) { 
        var html_type = document.getElementById("html_type");
        var html_type_name = html_type.options[html_type.selectedIndex].value;

        if (html_type_name == "Text") {
            document.getElementById("price").style.display="block";
            //document.getElementById("is_enter_qty").style.display="none";
            document.getElementById("showoption").style.display="none";
        } else {
            document.getElementById("price").style.display="none";
            //document.getElementById("is_enter_qty").style.display="block";
            document.getElementById("showoption").style.display="block";
        }

        if (html_type_name == 'Radio' || html_type_name == 'CheckBox') {
			      document.getElementById("optionsPerLine").style.display="block";
			      document.getElementById("optionsPerLineDef").style.display="block";
        } else {
			      document.getElementById("optionsPerLine").style.display="none";
			      document.getElementById("optionsPerLineDef").style.display="none";
        }

        var radioOption, checkBoxOption;

        for (var i=1; i<=11; i++) {
            radioOption = 'radio'+i;
            checkBoxOption = 'checkbox'+i	
            if (html_type_name == 'Radio' || html_type_name == 'CheckBox') {
                if (html_type_name == "CheckBox") {
                    document.getElementById(checkBoxOption).style.display="block";
                    document.getElementById(radioOption).style.display="none";
                } else {
                    document.getElementById(radioOption).style.display="block";	
                    document.getElementById(checkBoxOption).style.display="none";
                }
            }
        }
	
    }
</script>
{/literal}
<fieldset><legend>{ts}Price Field{/ts}</legend>

    <div class="form-item">
        <dl>
        <dt>{$form.label.label}</dt><dd>{$form.label.html}</dd>
        <dt>{$form.html_type.label}</dt><dd>{$form.html_type.html}</dd>
        {if $action neq 4 and $action neq 2}
            <dt>&nbsp;</dt><dd class="description">{ts}Select the html type used to offer options for this field{/ts}</dd>
        {/if}
        </dl>

        <div id="price" {if $action eq 2 && $form.html_type.value.0 eq 'Text'} class="show-block" {else} class="hide-block" {/if}>
        <dt>{$form.price.label}</dt><dd>{$form.price.html}</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}Unit price{/ts}
        {/if}
        </div>

    {if $action eq 1}
        {* Conditionally show table for setting up selection options - for field types = radio, checkbox or select *}
        <div id='showoption' class="hide-block">{ include file="CRM/Price/Form/OptionFields.tpl"}</div>
    {/if}
        <dl>
	<dt id="optionsPerLine" {if $action eq 2 && ($form.html_type.value.0 eq 'CheckBox' || $form.html_type.value.0 eq 'Radio')}class="show-block"{else} class="hide-block" {/if}>{$form.options_per_line.label}</dt>	
	    <dd id="optionsPerLineDef" {if $action eq 2 && ($form.html_type.value.0 eq 'CheckBox' || $form.html_type.value.0 eq 'Radio')}class="show-block"{else} class="hide-block"{/if}>{$form.options_per_line.html|crmReplace:class:two}</dd>
<!--
        <div id="is_enter_qty" {if $action eq 2 && $form.html_type.value.0 eq 'Text'} class="hide-block" {else} class="show-block" {/if}>
        <dt>{$form.is_enter_qty.label}</dt><dd>{$form.is_enter_qty.html}</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}If the user should enter a quantity in addition to choosing an option.{/ts}
        {/if}
        </div>
-->
        <dt>{$form.is_display_amounts.label}</dt><dd>{$form.is_display_amounts.html}</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}Display amount next to each option?  If no, then the amount should be in the option description.{/ts}</dd>
        {/if}

	      <dt>{$form.weight.label}</dt><dd>{$form.weight.html|crmReplace:class:two}</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}Weight controls the order in which fields are displayed in a group. Enter a positive or negative integer - lower numbers are displayed ahead of higher numbers.{/ts}</dd>
        {/if}

        <dt>{$form.help_post.label}</dt><dd>&nbsp;{$form.help_post.html|crmReplace:class:huge}&nbsp;</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}Explanatory text displayed to users for this field.{/ts}</dd>
        {/if}
<!--
        <dt>{$form.active_on.label}</dt><dd>{$form.active_on.html}</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}Date this field becomes effective (optional){/ts}</dd>
        {/if}

        <dt>{$form.expire_on.label}</dt><dd>{$form.expire_on.html}</dd>
        {if $action neq 4}
        <dt>&nbsp;</dt><dd class="description">{ts}Date this field expires (optional){/ts}</dd>
        {/if}
-->
        <dt>{$form.is_required.label}</dt><dd>&nbsp;{$form.is_required.html}</dd>
    </dl>
        <dl>
        <dt>{$form.is_active.label}</dt><dd>&nbsp;{$form.is_active.html}</dd>
        </dl>    
   </div>
    
    <div id="crm-submit-buttons" class="form-item">
    <dl>
    {if $action ne 4}
        <dt>&nbsp;</dt><dd>{$form.buttons.html}</dd>
    {else}
        <dt>&nbsp;</dt><dd>{$form.done.html}</dd>
    {/if} {* $action ne view *}
    </dl>    
    </div> 
</fieldset>

<script type="text/javascript">
    option_html_type(this.form);
</script>

{* Give link to view/edit choice options if in edit mode and html_type is one of the multiple choice types *}
{if $action eq 2 AND ($form.data_type.value.1.0 eq 'CheckBox' OR $form.data_type.value.1.0 eq 'Radio' OR $form.data_type.value.1.0 eq 'Select') }
    <div class="action-link">
        <a href="{crmURL p="civicrm/admin/event/field/option" q="reset=1&action=browse&fid=`$id`"}">&raquo; {ts}View / Edit Multiple Choice Options{/ts}</a>
    </div>
{/if}
