{if $context EQ 'Search'}
    {include file="CRM/common/pager.tpl" location="top"}
{/if}

{if $context EQ 'Contact Summary'}
    {assign var='columnHeaders' value=$contribute_columnHeaders}
    {assign var='rows' value=$contribute_rows}
    {assign var='single' value=$contribute_single}
    {assign var='limit' value=$contribute_limit}
{/if}

{strip}
<table class="selector">
  <tr class="columnheader">
{if ! $contribute_single and ! $contribute_limit}
  <th scope="col" title="Select Rows">{$form.toggleSelect.html}</th> 
{/if}
  {foreach from=$columnHeaders item=header}
    <th scope="col">
    {if $header.sort}
      {assign var='key' value=$header.sort}
      {if $context EQ 'Contact Summary'}	
         {$contribute_sort->_response.$key.link}
      {else}
         {$sort->_response.$key.link}
      {/if}
    {else}
      {$header.name}
    {/if}
    </th>
  {/foreach}
  </tr>

  {counter start=0 skip=1 print=false}
  {foreach from=$rows item=row}
  <tr id='rowid{$row.contribution_id}' class="{cycle values="odd-row,even-row"}{if $row.cancel_date} disabled{/if}">
{if ! $contribute_single and ! $contribute_limit}
    {assign var=cbName value=$row.checkbox}
    <td>{$form.$cbName.html}</td> 
{/if}
{if ( $contribute_context eq 'dashboard' ) or ( $contribute_context eq 'search' ) }
    <td>{$row.contact_type}</td>	
    <td><a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$row.contact_id`"}">{$row.sort_name}</a></td>
{/if}
    <td class="right bold nowrap">{$row.total_amount|crmMoney} {if $row.amount_level } - {$row.amount_level} {/if}
    {if $row.contribution_recur_id}
     <br /> {ts}(Recurring Contribution){/ts}
    {/if}
    </td>
    <td>{$row.contribution_type}</td>
    <td>{$row.contribution_source}</td>
    <td>{$row.receive_date|truncate:10:''|crmDate}</td>
    <td>{$row.thankyou_date|truncate:10:''|crmDate}</td>
    <td> 
        {$row.contrib_status}<br />
        {if $row.cancel_date}    
        {$row.cancel_date|truncate:10:''|crmDate}
        {/if}
    </td>
    <td>{$row.product_name}</td>
    <td>{$row.action}</td>
  </tr>
  {/foreach}
{* Link to "View all contributions" for Contact Summary selector display *}
{if ($context EQ 'Contact Summary') AND $contribute_pager->_totalItems GT $limit}
  <tr class="even-row">
    <td colspan="8"><a href="{crmURL p='civicrm/contact/view' q="reset=1&force=1&selectedChild=contribute&cid=$contactId"}">&raquo; {ts}View all contributions from this contact{/ts}... </a></td>
  </tr>
{/if}
{if ($context EQ 'dashboard') AND $pager->_totalItems GT $limit}
  <tr class="even-row">
    <td colspan="10"><a href="{crmURL p='civicrm/contribute/search' q='reset=1&force=1'}">&raquo; {ts}List more contributions{/ts}... </a></td>
  </tr>
{/if}
</table>
{/strip}

{if $context EQ 'Search'}
 <script type="text/javascript">
 {* this function is called to change the color of selected row(s) *}
    var fname = "{$form.formName}";	
    on_load_init_checkboxes(fname);
 </script>
{/if}

{if $context EQ 'Search'}
    {include file="CRM/common/pager.tpl" location="bottom"}
{/if}
