        <div id="Mask"></div>
        <!--  pop   -->
        <div id="review_pop" class="pop-wrap">
            <div class="pop-layer">
            <div class="close">x</div>
                <div class="comment-form">
                    <h4>Write Review</h4>
                    <form method="POST" name="review_form">
                        <input type="hidden" name="mode" value="review_write">
                        <input type="hidden" name="review_num" value="">
                        <div class="form-group">
                            <textarea class="form-control mb-10" rows="5" name="message" placeholder="Messege" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Messege'" required=""></textarea>
                        </div>
                        <a href="javascript:reserve.booking_form('review_form','html');" class="primary-btn button_hover">Post Comment</a>   
                    </form>
                </div>
            </div>
        </div>
        <!--  //pop -->