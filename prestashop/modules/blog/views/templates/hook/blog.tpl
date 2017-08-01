<!-- Block blog -->
<div id="blog_block_left" class="block">
  <h4>{l s='Blog!' mod='blog'}</h4>
  <div class="block_content">
    <p>
      {if !isset($blog_name) || !$blog_name}
        {capture name='blog_tempvar'}{l s='World' mod='blog'}{/capture}
        {assign var='blog_name' value=$smarty.capture.blog_tempvar}
      {/if}
      {l s='Hello %1$s!' sprintf=$blog_name mod='blog'}
    </p>
    <ul>
      <li><a href="{$blog_link}"  title="{l s='Click this link' mod='blog'}">{l s='Click me!' mod='blog'}</a></li>
    </ul>
    <p> Total Products :{$blog_count} </p>
    <p>Last Product : {$blog_last_product}</p>
  </div>
</div>
<!-- /Block blog -->
