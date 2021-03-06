{* This file provides the HTML for the big add contact form *}
{* It provides the templating for Name, Demographics and Contact notes *}
{* The templating for Location and Communication preferences block has been plugged by including the Location.tpl file *}    

{* @var $form Contains the array for the form elements and other form associated information assigned to the template by the controller *}

 {* Including the javascript source code from the Individual.js *}
 <script type="text/javascript" src="{$config->resourceBase}js/Individual.js"></script>
 
 <div class="crm-submit-buttons">
    {$form.buttons.html}
 </div>

{if $contact_type eq 'Individual'}
 <div id="name">
 <fieldset><legend>{ts}Name and Greeting{/ts}</legend>
	<table class="form-layout">
    <tr>
		<td>{$form.prefix_id.label}</td>
		<td>{$form.first_name.label}</td>
		<td>{$form.middle_name.label}</td>
		<td>{$form.last_name.label}</td>
		<td>{$form.suffix_id.label}</td>
	</tr>
	<tr>
		<td>{$form.prefix_id.html}</td>
		<td>{$form.first_name.html}</td>
		<td>{$form.middle_name.html|crmReplace:class:eight}</td>
		<td>{$form.last_name.html}</td>
		<td>{$form.suffix_id.html}</td>
	</tr>
   	 <tr>
        <td>&nbsp;</td>
        <td>{$form.contact_source.label}</td>
        <td>{$form.nick_name.label}</td>
        <td>{$form.greeting_type.label} &nbsp; </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>{$form.contact_source.html|crmReplace:class:big}</td>
        <td>{$form.nick_name.html|crmReplace:class:big}</td>
        <td>{$form.greeting_type.html}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>{$form.job_title.label}</td>
        <td>{$form.current_employer.label}</td>
        <td>{$form.home_URL.label}</td>
        <td>{$form.external_identifier.label}</td>        
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td> {$form.job_title.html}</td>
        <td>{$form.current_employer.html}</td>
        <td>{$form.home_URL.html|crmReplace:class:big}</td>
        <td>{$form.external_identifier.html}</td>        
    </tr>
    </table>

    {$form._qf_Edit_refresh_dedupe.html}
    {if $isDuplicate}&nbsp;&nbsp;{$form._qf_Edit_next_duplicate.html}{/if}
    {if $isSharedHouseholdDuplicate}&nbsp;&nbsp;{$form._qf_Edit_next_sharedHouseholdDuplicate.html}{/if}
    <div class="spacer"></div>
 </fieldset>
 </div>
{elseif $contact_type eq 'Household'}
<div id="name">
 <fieldset><legend>{ts}Household{/ts}</legend>
   	<table class="form-layout">
    <tr>
		<td>{$form.household_name.label}</td>
        <td>{$form.contact_source.label}</td>
    </tr>
    <tr>
        <td>{$form.household_name.html|crmReplace:class:big}</td>
        <td>{$form.contact_source.html}</td>
    </tr>
    <tr>
       	<td>{$form.nick_name.label}</td>
        <td>{$form.external_identifier.label}</td>        
	</tr>
    <tr>
        <td>{$form.nick_name.html|crmReplace:class:big}</td>
        <td>{$form.external_identifier.html}</td>        
    </tr>
    </table>
    {$form._qf_Edit_refresh_dedupe.html}    
    {if $isDuplicate}&nbsp;&nbsp;{$form._qf_Edit_next_duplicate.html}{/if}
    <div class="spacer"></div>

 </fieldset>
 </div>


{elseif $contact_type eq 'Organization'}
<div id="name">
 <fieldset><legend>{ts}Organization{/ts}</legend>
	<table class="form-layout">
    <tr>
		<td>{$form.organization_name.label}</td>
		<td>{$form.legal_name.label}</td>
		<td>{$form.sic_code.label}</td>
        <td>{$form.contact_source.label}</td>
        
    </tr>
    <tr>
        <td>{$form.organization_name.html|crmReplace:class:big}</td>
        <td>{$form.legal_name.html|crmReplace:class:big}</td>
        <td>{$form.sic_code.html |crmReplace:class:big}</td>
        <td>{$form.contact_source.html}</td>
    </tr>
    <tr>
        <td>{$form.home_URL.label}</td>
		<td>{$form.nick_name.label}</td>
        <td>{$form.external_identifier.label}</td>        
	</tr>
    <tr>
        <td>{$form.home_URL.html|crmReplace:class:big}</td>
        <td>{$form.nick_name.html|crmReplace:class:big}</td>
        <td>{$form.external_identifier.html}</td>        
    </tr>
    </table>
    {$form._qf_Edit_refresh_dedupe.html}    
    {if $isDuplicate}&nbsp;&nbsp;{$form._qf_Edit_next_duplicate.html}{/if}
    <div class="spacer"></div>

</fieldset>
</div>
{/if}

{* Plugging the Communication preferences block *} 
{if $showCommBlock}
 {include file="CRM/Contact/Form/CommPrefs.tpl"}
{/if}
 
{* Conditionally insert any inline custom data groups *} 
{include file="CRM/Contact/Page/View/CustomData.tpl" mainEditForm=1}

{* Plugging the Location block *}
{include file="CRM/Contact/Form/Location.tpl"}

{if $showDemographics}
{if $contact_type eq 'Individual'}
 <div id = "id_demographics_show" class="section-hidden section-hidden-border label">
    {$demographics.show}{ts}Demographics{/ts}
 </div>
 
 <div id="id_demographics">
 <fieldset><legend>{$demographics.hide}{ts}Demographics{/ts}</legend>
    <div class="form-item">
        <span class="labels">
        {$form.gender_id.label}
        </span>
        <span class="fields">
        {$form.gender_id.html}
        </span>
    </div>
	<div class="form-item">
        <span class="labels">
        {$form.birth_date.label}
        </span>
        <span class="fields">
		{$form.birth_date.html}
                
        </span>
        <div class="description"> 
                   {include file="CRM/common/calendar/desc.tpl" trigger=trigger_demographics_1}
        </div>
        {include file="CRM/common/calendar/body.tpl" dateVar=birth_date startDate=1905 endDate=currentYear trigger=trigger_demographics_1 }
    </div>
	<div class="form-item">
        {$form.is_deceased.html}
        {$form.is_deceased.label}
    </div>
	<div id="showDeceasedDate" class="form-item">
        <span class="labels">
        {$form.deceased_date.label}
        </span>
        <span class="fields">
		{$form.deceased_date.html}
        </span>
                <div class="description"> 
                   {include file="CRM/common/calendar/desc.tpl" trigger=trigger_demographics_2}
                </div>
        
        {include file="CRM/common/calendar/body.tpl" dateVar=deceased_date startDate=1905 endDate=currentYear trigger=trigger_demographics_2 }
    </div>

  </fieldset>
 </div>

{literal}
<script type="text/javascript">
    if (document.getElementsByName("is_deceased")[0].checked) {
      	    show('showDeceasedDate');
    } else {
           	hide('showDeceasedDate');
    }
        
    function showDeceasedDate()
    {
        if (document.getElementsByName("is_deceased")[0].checked) {
      	    show('showDeceasedDate');
        } else {
           	hide('showDeceasedDate');
        }
    }
</script>
{/literal}

{/if}  
{/if}

 {******************************** ENDING THE DEMOGRAPHICS SECTION **************************************}

 {* Notes block only included for Add Contact (since it navigates from Edit form...) *}
 {if $showNotes and $action eq 1}
     <div id = "id_notes_show" class="section-hidden section-hidden-border">
        {$notes.show}<label>{ts}Notes{/ts}</label>
     </div>

     <div id = "id_notes">
         <fieldset><legend>{$notes.hide}{ts}Contact Notes{/ts}</legend>
            <div class="form-item">
                {$form.note.html}
            </div>
         </fieldset>
     </div>
{/if}
 {* -- End of "notes" div -- *}

{if $showTagsAndGroups}
 {* Groups and Tags block *} 
<div id="group_show" class="section-hidden section-hidden-border">
    <a href="#" onclick="hide('group_show'); show('group'); return false;"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon" alt="{ts}open section{/ts}"/></a><label>{ts}Tags and Groups{/ts}</label><br />
</div>

<div id="group">
    <fieldset><legend><a href="#" onclick="hide('group'); show('group_show'); return false;"><img src="{$config->resourceBase}i/TreeMinus.gif" class="action-icon" alt="{ts}close section{/ts}"/></a>{ts}Tags and Groups{/ts}</legend>
    {strip}
    <div class="form-item">
	<table class="form-layout">
    <tr>
        <td>
        <div class="label">{ts}Group(s){/ts}</div>
        <div class="listing-box">
	{$form.group.html}
        </div>
        </td>
        <td>
        <div class="label">{ts}Tag(s){/ts}</div>
        <div class="listing-box">
	{$form.tag.html}
        </div>
        </td>
    </tr>
    </table>
    </div>
    {/strip}
    <div class="spacer"> </div>
    </fieldset>
</div>
{/if}

<div class="crm-submit-buttons">
    {$form.buttons.html}
</div>

{* Include Javascript to hide and display the appropriate blocks as directed by the php code *}
{include file="CRM/common/showHide.tpl"}

{* Set Default for Shared Household-Name *}
{if $contact_type eq 'Individual' and $defaultSharedHousehold}
{literal}
<script type="text/javascript">
    dojo.addOnLoad( setSharedHouseholdDefault );
    function setSharedHouseholdDefault( )
    {
        var sharedHousehold = dojo.widget.byId('shared_household');
        sharedHousehold.textInputNode.value = "{/literal}{$defaultSharedHousehold}{literal}";
    }
</script>
{/literal}
{/if}
