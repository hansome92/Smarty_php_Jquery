<div id="profileHolder">
{login assign="login"}
{if $login.isLoggedIn == true}
    {myProfile assign="profile"}


    <div id="profileBody">
        <div style="clear:both;"></div>

        <div id="scrapbooks">
            <img src="{$urlPrefix}images/scrapbooksCreateNewBook.png" id="scrapbooksButton" class="profileSaveButton">

            <div id="scrapbookSorts">Sort:
                <div id="sortAlpha">Book Name</div>
                <div id="sortDate">Creation Date</div>
            </div>

            <div class="profileItem" id="books">
                <div style="clear:both;margin-bottom: 40px;"></div>

                {if sizeof($profile.scrapbooks) > 0}
                    {foreach from=$profile.scrapbooks item=book}
                        <div class="item_book" bookid="{$book.id}" bookname="{$book.name}">

                            <div class="bookIconHolder">
                                <img src="{$urlPrefix}images/{$book.coverUrl}" ">
                                <!-- div class="book_icon"></div -->
                            </div>

                            <div class="view-book">
                                <div class="book_title" onclick="rename(this);" bookid="{$book.id}">
                                    {$book.name}
                                </div>
                                <div class="book_created">
                                    Created: {date('m/d/y', $book.created->sec)}
                                </div>
                                <div class="scrapbooksEditBook">
                                    Add to your scrapbook at any time!<br>
                                    <a bookid="{$book.id}" onclick="view(this);">Edit my scrapbook >></a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <a class="lb-terms" bookid="{$book.id}" onclick="deleteBook(this);">(delete)</a>
                                </div>

                                <div class="scrapbookButtonHolder">
                                    <img src="{$urlPrefix}images/viewYourBook.png" onclick="show(this);" bookid="{$book.id}">
                                    <a href="{$urlPrefix}scrapshare/"><img src="{$urlPrefix}images/shareYourBook.png"></a>
                                </div>

                            </div>

                            <div>

                                <div class="vrd">
                                    <input type="hidden" value="{$book.id}" />
                                </div>
                            </div>
                        </div>
                    {/foreach}
                {/if}
            </div>
            <br>

            <div style="clear:both;"><br></div>

            <div class="new_book">

            </div>
        </div>

        <input type="hidden" id="sel_book_name" value="" />
        <input type="hidden" id="urlPrefix" value="{$urlPrefix}" />
    </div>
    <script type="text/javascript">
        $( document ).ready( function() {

        } );
    </script>


    {else}

    <span>You're not signed in!</span>

{/if}
</div>