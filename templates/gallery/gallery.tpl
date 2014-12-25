<div id="galleryHolder">
{login assign="login"}
{if $login.isLoggedIn == true}
    {gallery assign="scrapbooks"}
    <div class="bookshelf">
		<div class="shelf">
                    {if sizeof($scrapbooks) > 0}
                        {foreach from=$scrapbooks item=books}
			<div class="row">
				<div class="loc">
                                        {foreach from=$books item=book}
                                            <div>                                               
                                                <div class="sample thumb1" id="{$book.id}">
                                                </div> 
                                                 <div class="title" action="{$book.id}">                                                     
                                                    {$book.name}
                                                </div>
                                            </div>
					{/foreach}
				</div>
			</div>		
                        {/foreach}
                     {/if}
		</div>		
	</div>

    <script type="text/javascript">
      
        $(document).ready(function(){      
           
           var bookself = new Bookself();  
           bookself.loadBooks();
           bookself.addEvent();
        });
    </script>
    {else}

    <span>You're not signed in!</span>
    </div>
   
{/if}
</div>