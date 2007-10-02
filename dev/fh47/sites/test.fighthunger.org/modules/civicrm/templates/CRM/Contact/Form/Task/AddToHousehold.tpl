<div class='spacer'></div>
<fieldset>
<legend>
{ts}Choose Relationship Type and Target Household{/ts}
</legend>
<br />
<div class="form-item">
    <table class="form-layout-compressed"> 
        <tr><td></td><td>{include file="CRM/Contact/Form/Task.tpl"}</td></tr>
            {if $action EQ 2} {* action = update *}
                <tr><td><label>{$sort_name}</label></td></tr>
            {else} {* action = add *}
                <tr><td>{$form.relationship_type_id.label}</td><td>{$form.relationship_type_id.html}</td></tr>   
                <tr><td></td></tr>
                <tr><td>{$form.name.label}</td><td>{$form.name.html}</td></tr>
                <tr><td></td><td>{$form._qf_AddToHousehold_refresh.html}&nbsp;&nbsp;{$form._qf_AddToHousehold_cancel.html}</td></tr>
     </table>
         {if $searchDone } {* Search button clicked *}
             {if $searchCount}
                 {if $searchRows} {* we've got rows to display *}
                     <fieldset><legend>{ts}Mark Target Contact(s) for this Relationship{/ts}</legend>
                         <div class="description">
                         {ts}Mark the target contact(s) for this relationship if it appears below. Otherwise you may modify the search name above and click Search again.{/ts}
                         </div>
                        {strip}
                        <table>
                        <tr class="columnheader">
                        <th>&nbsp;</th>
                        <th>{ts}Name{/ts}</th>
                        <th>{ts}City{/ts}</th>
                        <th>{ts}State{/ts}</th>
                        <th>{ts}Email{/ts}</th>
                        <th>{ts}Phone{/ts}</th>
                        </tr>
                        {foreach from=$searchRows item=row}
                        <tr class="{cycle values="odd-row,even-row"}">
                            <td>{$form.contact_check[$row.id].html}</td>
                            <td>{$row.type} {$row.name}</td>
                            <td>{$row.city}</td>
                            <td>{$row.state}</td>
                            <td>{$row.email}</td>
                            <td>{$row.phone}</td>
                        </tr>
                        {/foreach}
                        </table>
                        {/strip}
                        </fieldset>
                    {else} {* too many results - we're only displaying 50 *}
                        </div></fieldset>
                        {capture assign=infoMessage}{ts}Too many matching results. Please narrow your search by entering a more complete target contact name.{/ts}{/capture}
                        {include file="CRM/common/info.tpl"}
                    {/if}
                {else} {* no valid matches for name + contact_type *}
                        </div></fieldset>
                        {capture assign=infoMessage}{ts 1=$form.name.value 2=$contact_type_display}No matching results for <ul><li>Name like: %1</li><li>Contact type: %2</li></ul><br />Check your spelling, or try fewer letters for the target contact name.{/ts}{/capture}
                        {include file="CRM/common/info.tpl"}                
                {/if} {* end if searchCount *}
              {else}
                </div></fieldset>
              {/if} {* end if searchDone *}
        {/if} {* end action = add *}

        {* Only show buttons if action=update, OR if we have $contacts (results)*}
        {if $searchRows OR $action EQ 2}
            <div class="form-item">
                <dl>
                  <dt> </dt>
                    <dd class="description">

                    </dd>
                <dt></dt><dd>{$form.buttons.html}</dd>
                </dl>
            </div>
	<div class="form-item">
	{$form.status.label} {$form.status.html}
	</div>


            </div></fieldset>
	{/if}



