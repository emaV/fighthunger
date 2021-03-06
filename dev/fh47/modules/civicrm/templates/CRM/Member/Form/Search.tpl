<div id="help">
    {ts}Use this form to find member(s) by member name or email address, membership type, status, source, and/or membership period start and end dates. Multiple selections for Membership Type and Status are combined as OR criteria (e.g. checking "Membership Type A" and "Membership Type B" will find contacts who have either membership). All other search fields are combined as AND criteria (e.g. selecting Status is "Expired" AND Source is "Phone-banking" returns only those contacts who meet both criteria).{/ts}
</div>
<fieldset><legend>{ts}Find Members{/ts}</legend>
{strip}
     <table class="form-layout">
		<tr>
            <td class="font-size12pt label">{$form.sort_name.label}</td>
            <td>{$form.sort_name.html|crmReplace:class:'twenty'}
                <div class="description font-italic">
                    {ts}Complete OR partial name OR email.{/ts}
                </div>
            </td>
            <td>{$form.buttons.html}</td>       
        </tr>

        {include file="CRM/Member/Form/Search/Common.tpl"}

        <tr>
            <td colspan="2">&nbsp;</td>
            <td>{$form.buttons.html}</td>
        </tr>
    </table>
{/strip} 
</fieldset>

{if $rowsEmpty}
    {include file="CRM/Member/Form/Search/EmptyResults.tpl"}
{/if}

{if $rows}
    {* Search request has returned 1 or more matching rows. *}
    <fieldset>
    
       {* This section handles form elements for action task select and submit *}
       {include file="CRM/Member/Form/Search/ResultTasks.tpl"}

       {* This section displays the rows along and includes the paging controls *}
       <p></p>
       {include file="CRM/Member/Form/Selector.tpl" context="Search"}
       
    </fieldset>
    {* END Actions/Results section *}

{/if}
