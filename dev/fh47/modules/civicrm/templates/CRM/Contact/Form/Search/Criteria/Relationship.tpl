    <div id="relationship">
    <fieldset class="collapsible">
    <table class="form-layout">
         <tr>
            <td class="label">
                {$form.relation_type_id.label}
            </td>
            <td>
                {$form.relation_type_id.html}
            </td>
            <td class="label">
                {$form.relation_target_name.label}
            </td>
            <td>
                {$form.relation_target_name.html|crmReplace:class:large}
                <div class="description font-italic">
                    {ts}Complete OR partial contact name.{/ts}
                </div>
            </td>    
        </tr>
      </table>         
    </fieldset>
    </div>

