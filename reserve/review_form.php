        <div id="Mask"></div>
        <script type="text/javascript">
            function star(num) {
                $(".review_star").removeClass("fa-star-o");
                $(".review_star").removeClass("fa-star");
                $("input[name='star']").val(num);
                for(i=0; i<5; i++){
                    if(i<num) {
                        $(".review_star").eq(i).addClass("fa-star");
                    } else {
                        $(".review_star").eq(i).addClass("fa-star-o");
                    }
                }
            }
        </script>
        <!--  pop   -->
        <div id="review_pop" class="pop-wrap">
            <div class="pop-layer">
            <div class="close">x</div>
                <div class="comment-form">
                    <h4>Write Review</h4>
                    <form method="POST" name="review_form">
                        <input type="hidden" name="star" value="5">
                        <div class="star star_div" style="padding:20px 0; font-size:30px;">
                            <a href="javascript:star(1);"><i class="review_star fa fa-star"></i></a>
                            <a href="javascript:star(2);"><i class="review_star fa fa-star"></i></a>
                            <a href="javascript:star(3);"><i class="review_star fa fa-star"></i></a>
                            <a href="javascript:star(4);"><i class="review_star fa fa-star"></i></a>
                            <a href="javascript:star(5);"><i class="review_star fa fa-star"></i></a>
                        </div>
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