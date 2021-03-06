{* CiviMember DashBoard (launch page) *}
<div id="help" class="solid-border-bottom">
    {capture assign=findContactURL}{crmURL p="civicrm/contact/search/basic" q="reset=1"}{/capture}
    {capture assign=contribPagesURL}{crmURL p="civicrm/admin/contribute" q="reset=1"}{/capture}
    {capture assign=memberTypesURL}{crmURL p="civicrm/admin/member/membershipType" q="reset=1"}{/capture}
    {capture assign=importURL}{crmURL p="civicrm/member/import" q="reset=1"}{/capture}
    {capture assign=docURLTitle}{ts}Opens online documentation in a new window.{/ts}{/capture}
    <p>{ts 1=$contribPagesURL 2=$memberTypesURL}CiviMember allows you to create customized membership types as well as page(s) for online membership sign-up and renewal. Administrators can create or modify Membership Types <a href="%2">here</a>, and configure Online Contribution Pages which include membership sign-up <a href="%1">here</a>.{/ts}</p>
    <p>{ts 1=$findContactURL 2="http://wiki.civicrm.org/confluence//x/ui" 3=$importURL 4=$docURLTitle}You can also input and track membership sign-ups offline. To record memberships manually for individual contacts, use <a href="%1">Find Contacts</a> to locate the contact. Then click <strong>View</strong> to go to their summary page and click on the <strong>New Membership</strong> link. You can also <a href="%3">import batches of membership records</a> from other sources. Refer to the <a href="%2" target="_blank" title="%4">CiviMember Guide</a> for more information.{/ts}</p>
</div>

<h3>{ts}Membership Summary{/ts}</h3>
<div class="description">
    {capture assign=findMembersURL}{crmURL p="civicrm/member/search/basic" q="reset=1"}{/capture}
    <p>{ts 1=$findMembersURL}This table provides a summary of <strong>Membership Signup and Renewal Activity</strong>, and includes shortcuts to view the details for these commonly used search criteria. To run your own customized searches - click <a href="%1">Find Members</a>. You can search by Member Name, Membership Type, Status, and Signup Date Ranges.{/ts}</p>
</div>
<table class="report">
<tr class="columnheader-dark">
    <th scope="col">{ts}Members by Type{/ts}</th>
    <th scope="col">{ts}{$currentMonth}-New/Renew (MTD){/ts}</th>
    <th scope="col">{ts}{$currentYear}-New/Renew (YTD){/ts}</th>
    <th scope="col">{ts}Current #{/ts}</th>
</tr>

{foreach from=$membershipSummary item=row}
<tr>
    <td><strong>{$row.month.name}</strong></td>
    <td class="label"><a href="{$row.month.url}" title="view details">{$row.month.count}</a></td> {* member/search?reset=1&force=1&membership_type_id=1&current=1&start=20060601000000&end=20060612174244 *}
    <td class="label"><a href="{$row.year.url}" title="view details">{$row.year.count}</a></td> {* member/search?reset=1&force=1&membership_type_id=1&current=1&start=20060101000000&end=20060612174244 *}
    <td class="label"><a href="{$row.current.url}" title="view details">{$row.current.count}</a></td> {* member/search?reset=1&force=1&membership_type_id=1&current=1 *}
</tr>
{/foreach}

<tr class="columnfooter">
    <td><strong>{ts}Totals (all types){/ts}</strong></td>
    <td class="label"><a href="{$totalCount.month.url}" title="view details">{$totalCount.month.count}</a></td> {* member/search?reset=1&force=1&current=1&start=20060601000000&end=20060612174244 *}
    <td class="label"><a href="{$totalCount.year.url}" title="view details">{$totalCount.year.count}</a></td> {* member/search?reset=1&force=1&current=1&start=20060101000000&end=20060612174244 *}
    <td class="label"><a href="{$totalCount.current.url}" title="view details">{$totalCount.current.count}</a></td> {* member/search?reset=1&force=1&current=1 *}
</tr>
</table>

{if $rows}
{* if $pager->_totalItems *}
    <h3>{ts}Recent Memberships{/ts}</h3>
    <div class="form-item">
        { include file="CRM/Member/Form/Selector.tpl" context="dashboard" }
    </div>
{* /if *}
{/if}
