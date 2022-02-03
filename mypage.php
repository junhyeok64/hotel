<?php
    $page = "mypage";
    include "./common/top.php";
?>      
        <!--================ Accomodation Area  =================-->
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_color">Reservation Confirm</h2>
                    <p>Please enter your reservation information</p>
                </div>
            </div>
        </section>
        <!--================ Accomodation Area  =================-->
        <!--================Booking Tabel Area =================-->
        <section class="hotel_booking_area pb-10" style="">
            <form name="reserve">
            <input type="hidden" name="mode" value="reserve_check">
            <div class="container">
                <div class="row hotel_booking_table">
                    <div class="col-md-3">
                        <h2>Your <br/> information</h2>
                    </div>
                    <div class="col-md-9">
                        <div class="boking_table">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Booker" name="reserve_name" value="<?=$_POST['reserve_name']?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Phone Number" name="phone" maxlength="11" value="<?=$_POST['phone']?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='password' class="form-control" placeholder="Password" name="password" maxlength="20" value="<?=$_POST['password']?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <a id="book_now" class="book_now_btn button_hover" href="javascript:reserve.booking_form('reserve', 'reserve_check');">Reserve Check</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <!--================Booking Tabel Area  =================-->
        
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="row mb_30" id="reserve_check">
                </div>
            </div>
        </section>
        <?php
            include "./reserve/review_form.php";
        ?>
        
<?php
    include "./common/footer.php";
    if($_POST["mode"] == "price") {
?>
<script type="text/javascript">
    $(document).ready(function(){
        reserve.booking_form('reserve', 'reserve_check');
    });
</script>
<?php
   }
?>
<script type="text/javascript">
    $(".pop-open").click(function(){
      $("#pop").show();
      $("#Mask").show();
    });
    $("#pop .close").click(function(){
      $("#pop").hide();
      $("#Mask").hide();
    });
</script>