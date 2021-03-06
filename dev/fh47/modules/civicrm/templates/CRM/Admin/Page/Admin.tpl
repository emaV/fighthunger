{* Displays Administer CiviCRM Control Panel *}
{if $newVersion}
    <div class="messages status">
        <dl>
        <dt><img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}"/></dt>
        <dd>
            <p>{ts 1=$newVersion 2=$localVersion}A newer version of CiviCRM is available: %1 (this site is currently running %2).{/ts}</p>
            <p>{ts 1='http://civicrm.org/' 2='http://civicrm.org/download/'}Read about the new version on <a href="%1">our website</a> and <a href="%2">download it here</a>.{/ts}</p>
        </dd>
      </dl>
    </div>
{/if}

<div id="help" class="description section-hidden-border">
{ts}Administer your CiviCRM site using the links on this page. Click <img src="{$config->resourceBase}/i/TreePlus.gif" alt="Plus sign." style="vertical-align: bottom; height: 20px; width: 20px;"> for descriptions of the options in each section.{/ts}
</div>

{strip}
{foreach from=$adminPanel key=groupName item=group name=adminLoop}
 <div id = "id_{$groupName}_show" class="section-hidden label{if $smarty.foreach.adminLoop.last eq false} section-hidden-border{/if}">
    <table class="form-layout">
    <tr>
        <td width="20%" class="font-size11pt" style="vertical-align: top; padding: 0px;">{$group.show} {$groupName}</td>
        <td width="80%" style="white-space: nowrap; padding: 0px;">

            <table class="form-layout" width="100%">
            <tr>
                <td width="50%" style="padding: 0px;">
                {foreach from=$group item=panelItem  key=panelName name=groupLoop}
                    {if $panelName != 'show' AND $panelName != 'hide' AND $panelName != 'perColumn'}
                        &raquo;&nbsp;<a href="{$panelItem.url}"{if $panelItem.extra} {$panelItem.extra}{/if} id="idc_{$panelItem.id}">{$panelItem.title}</a><br />
                        {if $smarty.foreach.groupLoop.iteration EQ $group.perColumn}
                            </td><td width="50%" style="padding: 0px;">
                        {/if}
                    {/if}
                {/foreach}
                </td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
 </div>
 
 <div id="id_{$groupName}">
    <fieldset><legend><strong>{$group.hide}{$groupName}</strong></legend>
        <table class="form-layout">
                
        {foreach from=$group item=panelItem  key=panelName name=groupLoop}
          {if $panelName != 'show' AND $panelName != 'hide' AND $panelName != 'perColumn'}
            <tr>
                <td style="vertical-align: top;">
                    <a href="{$panelItem.url}"{if $panelItem.extra} {$panelItem.extra}{/if} ><img src="{$config->resourceBase}i/
                    {$panelItem.icon}" alt="{$panelItem.title}"/></a>
                </td>
                <td class="report font-size11pt" style="vertical-align: text-top;" width="20%">
                    <a href="{$panelItem.url}"{if $panelItem.extra} {$panelItem.extra}{/if} id="id_{$panelItem.id}">{$panelItem.title}</a>
                </td>
                <td class="description"  style="vertical-align: text-top;" width="75%">
                    {$panelItem.desc}
                </td>
            </tr>
          {/if}
        {/foreach}
        
        </table>
    </fieldset>
  </div>
{/foreach}
{/strip}

{* Include Javascript to hide and display the appropriate blocks as directed by the php code *}
{include file="CRM/common/showHide.tpl"}