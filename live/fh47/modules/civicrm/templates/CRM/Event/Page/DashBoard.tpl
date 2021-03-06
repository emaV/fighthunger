{* CiviEvent DashBoard (launch page) *}
<div id="help" class="solid-border-bottom">
    {capture assign=findContactURL}{crmURL p="civicrm/contact/search/basic" q="reset=1"}{/capture}
    {capture assign=importURL}{crmURL p="civicrm/event/import" q="reset=1"}{/capture}
    {capture assign=newEventURL}{crmURL p="civicrm/admin/event" q="action=add&reset=1"}{/capture}
    {capture assign=configPagesURL}{crmURL p="civicrm/admin/event" q="reset=1"}{/capture}
    <p>{ts 1=$newEventURL 2=$configPagesURL}CiviEvent allows you to create customized page(s) for creating and registering online events. Administrators can create <a href="%1"><strong>new events</strong></a> and <a href="%2"><strong>manage existing events</strong></a>.{/ts}</p>
    <p>{ts 1=$findContactURL 2=$importURL}You can also input and track offline Events. To enter events manually for individual contacts, use <a href="%1">Find Contacts</a> to locate the contact. Then click <strong>View</strong> to go to their summary page and click on the <strong>New Event</strong> link. You can also <a href="%2"><strong>import batches of participants</strong></a> from other sources.{/ts}</p>
</div>

<h3>{ts}Event Summary{/ts}</h3>
<div class="description">
    {capture assign=findEventsURL}{crmURL p="civicrm/event/search/basic" q="reset=1"}{/capture}
    <p>{ts 1=$findEventsURL}This table provides a summary of up to ten scheduled and recent <strong>Events</strong>. Click the <strong>Event name</strong> to view the event as it will be displayed to site visitors. Click the <strong>Participants count</strong> to see a list of participants. To run your own customized searches - click <a href="%1">Find Participants</a>. You can search by Participant Name, Event, Date Range and Status.{/ts}</p>
</div>

{if $eventSummary.total_events}
<table class="report">
<tr class="columnheader-dark">
    <th scope="col">{ts}Event{/ts}</th>
    <th scope="col">{ts}ID{/ts}</th>
    <th scope="col">{ts}Type{/ts}</th>
    <th scope="col">{ts}Public{/ts}</th>
    <th scope="col">{ts}Participants{/ts}</th>
    <th scope="col">{ts}Date(s){/ts}</th>
{if $eventAdmin or $eventMap}
    <th></th>
{/if}
</tr>
{foreach from=$eventSummary.events item=values key=id}
<tr>
    <td><a href="{crmURL p="civicrm/event/info" q="reset=1&id=`$id`"}">{$values.eventTitle}</a></td>
    <td>{$id}</td>
    <td>{$values.eventType}</td>
    <td>{$values.isPublic}</td>
    <td class="right">
        {if $values.participant_url}<a href="{$values.participant_url}">{$values.participants} (show)</a>{else}{$values.participants}{/if}
        {if $values.maxParticipants}<br />{ts 1=$values.maxParticipants}(max %1){/ts}{/if}
    </td>
    <td>{$values.startDate}&nbsp;{if $values.endDate}to{/if}&nbsp;{$values.endDate}</td>
{if $eventAdmin or $eventMap}
    <td>
{if $values.isMap}
  <a href="{$values.isMap}">{ts}Map{/ts}</a>&nbsp;|&nbsp;
{/if}
{if $eventAdmin}
  <a href="{$values.configure}">{ts}Configure{/ts}</a>
{/if}
{/if}
    </td>
</tr>
{/foreach}

{if $eventSummary.total_events GT 10}
<tr>
    <td colspan="7"><a href="{crmURL p='civicrm/admin/event' q='reset=1'}">&raquo; {ts}Browse more events{/ts}...</a></td>
</tr>
{/if}
</table>
{/if}

{if $pager->_totalItems}
    <h3>{ts}Recent Registrations{/ts}</h3>
    <div class="form-item">
        {include file="CRM/Event/Form/Selector.tpl" context="dashboard"}
    </div>
{/if}
