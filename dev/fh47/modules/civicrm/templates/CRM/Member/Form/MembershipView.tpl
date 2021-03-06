<div class="form-item"> 
<fieldset>
      <legend>{ts}View Membership{/ts}</legend>
      <dl>  
        <dt class="font-size12pt">{ts}Member{/ts}</dt><dd class="font-size12pt"><strong>{$displayName}</strong>&nbsp;</dd>
        {if $owner_display_name}
            <dt>{ts}By Relationship{/ts}</dt><dd>{$relationship}&nbsp;&nbsp;{$owner_display_name}&nbsp;</dd>
        {/if}
        <dt>{ts}Membership Type{/ts}</dt><dd>{$membership_type}&nbsp;</dd>
        <dt>{ts}Status{/ts}</dt><dd>{$status}&nbsp;</dd>
        <dt>{ts}Source{/ts}</dt><dd>{$source}&nbsp;</dd>
        <dt>{ts}Join date{/ts}</dt><dd>{$join_date}&nbsp;</dd>
        <dt>{ts}Start date{/ts}</dt><dd>{$start_date}&nbsp;</dd>
        <dt>{ts}End date{/ts}</dt><dd>{$end_date}&nbsp;</dd>
        <dt>{ts}Reminder date{/ts}</dt><dd>{$reminder_date}&nbsp;</dd>
        {include file="CRM/Contact/Page/View/InlineCustomData.tpl" mainEditForm=1}
        <dt></dt><dd>{$form.buttons.html}</dd>
    </dl>

</fieldset>  
</div>  
 
