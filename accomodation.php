<?php
    $page = "accomodation";
    include "./common/top.php";
?>      
    <style type="text/css">
        .reserve_ment {
            color:white;display:block;
        }
        .reserve_pay {
            float:right;font-size:2rem;
        }
        .datepicker {
            cursor:pointer;
        }
        #ui-datepicker-div {z-index:99;}
        #reserve_detail { opacity:0;height:0; }
    </style>
        <!--================ Accomodation Area  =================-->
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <h2 class="title_color">Special Accomodation</h2>
                    <p>We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,</p>
                </div>
                <div class="row mb_30">
                    <?php
                        $rqry = "SELECT MIN(price) AS price, room_type, name, img, c.date AS date, cnt, person
                            FROM reserve_check AS c
                            INNER JOIN room AS r
                            ON r.num = c.room_type
                            WHERE 1=1
                            AND r.state = 'Y'
                            AND c.cnt > 0";
                        if(date("H") > "18") {
                            $rqry .= " and c.date >= '".date("Y-m-d", strtotime(date("Y-m-d")." + 1 days"))."'";
                        } else {
                            $rqry .= " and c.date >= '".date("Y-m-d")."'";
                        }
                        $rqry .= " GROUP BY room_type";
                        $rres = mysqli_query($dbconn, $rqry);
                        $room_arr = array();
                        while($rrow = mysqli_fetch_array($rres)) {

                            $book_button = ($rrow["cnt"] > 0) ? "최저가 예약하기" : "Sold Out";
                            $book_css = ($rrow["cnt"] > 0) ? "" : " sold_out";
                            $person = ($rrow["person"] == 0 || $rrow["person"] == "") ? "" : " (".$rrow["person"]."인)";
                    ?>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="<?=$rrow['img']?>" alt="">
                                <a href="javascript:reserve.booking_state('<?=$rrow['room_type']?>', '<?=$rrow['date']?>', 'booking_select', 'html')" class="btn theme_btn button_hover<?=$book_css?>"><?=$book_button?></a>
                            </div>
                            <a href="#"><h4 class="sec_h4"><?=$rrow["name"].$person?></h4></a>
                            <h5><?=won?> <?=@number_format($rrow["price"])?><small>/night</small></h5>
                            <h6><?=$rrow["date"]?></h6>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </section>
        <!--================ Accomodation Area  =================-->
        <!--================Booking Tabel Area =================-->
        <section class="hotel_booking_area">
            <form name="book_form">
            <input type="hidden" name="mode" value="price">
            <div class="container">
                <div class="row hotel_booking_table">
                    <div class="col-md-3">
                        <h2>Book<br> Your Room</h2>
                    </div>
                    <div class="col-md-9">
                        <div class="boking_table">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker11'>
                                                <input type='text' class="form-control datepicker" placeholder="Arrival Date" name="sdate" onchange="reserve.booking('','','select')"/>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' class="form-control datepicker" placeholder="Departure Date" name="edate" onchange="reserve.booking('','','select')"/>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <div class="input-group">
                                            <select class="wide" name="room_cnt">
                                                <option data-display="number of room">number of room</option>
                                                <?php 
                                                    for($i=1; $i<10; $i++) { 
                                                        echo "<option value='".$i."'>0".$i."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <!--<div class="input-group">
                                            <select class="wide" name="child">
                                                <option data-display="Child">Child</option>
                                                <?php 
                                                    for($i=1; $i<10; $i++) { 
                                                        echo "<option value='".$i."'>0".$i."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>-->
                                        <div class="input-group">
                                            <select class="wide" name="room_type">
                                                <option data-display="Room Type">Room Type</option>
                                                <?php
                                                    $room_qry = "select num, name from room where state = 'Y' order by num asc";
                                                    $room_res = mysqli_query($dbconn, $room_qry);
                                                    while($room_row = mysqli_fetch_array($room_res)) {
                                                ?>
                                                <option value="<?=$room_row["num"]?>"><?=$room_row["name"]?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <a id="book_now" class="book_now_btn button_hover" href="javascript:reserve.booking();">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row hotel_booking_table" id="reserve_detail">
                    <div class="col-md-3">
                        
                    </div>
                    <div class="col-md-9">
                        <div class="boking_table">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Booker" name="reserve_name"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Phone Number" name="phone"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group' id=''>
                                                <input type='text' class="form-control" placeholder="Password" name="password"/>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="book_tabel_item">
                                        <div class="form-group">
                                            <div class='input-group reserve_ment' id=''>
                                                <?php
                                                    switch(date("w", date("Y-m-d"))) {
                                                        case 5:
                                                            $show_date = date("Y-m-d", strtotime(date("Y-m-d")." + 3days"));
                                                        break;
                                                        case 6:
                                                            $show_date = date("Y-m-d", strtotime(date("Y-m-d")." + 2days"));
                                                        break;
                                                        default;
                                                            $show_date = date("Y-m-d", strtotime(date("Y-m-d")." + 1days"));
                                                        break;
                                                    }
                                                ?>
                                                <p>
                                                    익일 영업일까지(<?=date("Y-m-d", strtotime(date("Y-m-d")." + 1days"))?> 18:00 PM) 미입금시 예약이 취소 될 수 있으며, 
                                                    <br>명절 및 휴일은 확인이 지연 될 수 있습니다.
                                                    <br><br>
                                                    xx은행 : 입금자 이준혁
                                                </p>
                                                
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class='input-group reserve_ment'>
                                                <p class="reserve_pay">
                                                   <?=won?> <b>1000</b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </section>
        <!--================Booking Tabel Area  =================-->
        <!--================ Accomodation Area  =================-->
        <section class="accomodation_area section_gap">
            <div class="container">
                <div class="section_title text-center">
                    <?php
                    //객실수 많은애들부터 털기

                    ?>
                    <h2 class="title_color">Normal Accomodation</h2>
                    <p>We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,</p>
                </div>
                <div class="row accomodation_two">
                    <?php
                        $qry = "select * from reserve_check where 1=1";
                    ?>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room1.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Double Deluxe Room</h4></a>
                            <h5>$250<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room2.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Single Deluxe Room</h4></a>
                            <h5>$200<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room3.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Honeymoon Suit</h4></a>
                            <h5>$750<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room4.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Economy Double</h4></a>
                            <h5>$200<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room1.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Double Deluxe Room</h4></a>
                            <h5>$250<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room2.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Single Deluxe Room</h4></a>
                            <h5>$200<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room3.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Honeymoon Suit</h4></a>
                            <h5>$750<small>/night</small></h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="image/room4.jpg" alt="">
                                <a href="#" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="#"><h4 class="sec_h4">Economy Double</h4></a>
                            <h5>$200<small>/night</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--================ Accomodation Area  =================-->
        <div id="script"></div>
        <style type="text/css">
            #reserve_div {
                position:fixed;
                top:10rem;
                border:1px solid black;
                width:50rem;
                height:30rem;
                left:50%;
                margin-left:-20%;
                background-color:white;
                z-index:999;
                padding:10px;
                display: none;
            }
            .reserve_close { 
                float:right;
                font-size:30px;
            }
        </style>
        <div id="reserve_div">
            <a class="reserve_close" href="javascript:reserve.reserve_close();">X</a>
            <table> 
                <tr>
                    <td></td>
                </tr>
            </table>
            
        </div>
        <!--================ start footer Area  =================-->	
        <footer class="footer-area section_gap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3  col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">About Agency</h6>
                            <p>The world has become so fast paced that people don’t want to stand by reading a page of information, they would much rather look at a presentation and understand the message. It has come to a point </p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">Navigation Links</h6>
                            <div class="row">
                                <div class="col-4">
                                    <ul class="list_style">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Feature</a></li>
                                        <li><a href="#">Services</a></li>
                                        <li><a href="#">Portfolio</a></li>
                                    </ul>
                                </div>
                                <div class="col-4">
                                    <ul class="list_style">
                                        <li><a href="#">Team</a></li>
                                        <li><a href="#">Pricing</a></li>
                                        <li><a href="#">Blog</a></li>
                                        <li><a href="#">Contact</a></li>
                                    </ul>
                                </div>										
                            </div>							
                        </div>
                    </div>							
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6 class="footer_title">Newsletter</h6>
                            <p>For business professionals caught between high OEM price and mediocre print and graphic output, </p>		
                            <div id="mc_embed_signup">
                                <form target="_blank" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="subscribe_form relative">
                                    <div class="input-group d-flex flex-row">
                                        <input name="EMAIL" placeholder="Email Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email Address '" required="" type="email">
                                        <button class="btn sub-btn"><span class="lnr lnr-location"></span></button>		
                                    </div>									
                                    <div class="mt-10 info"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-footer-widget instafeed">
                            <h6 class="footer_title">InstaFeed</h6>
                            <ul class="list_style instafeed d-flex flex-wrap">
                                <li><img src="image/instagram/Image-01.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-02.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-03.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-04.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-05.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-06.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-07.jpg" alt=""></li>
                                <li><img src="image/instagram/Image-08.jpg" alt=""></li>
                            </ul>
                        </div>
                    </div>						
                </div>
                <div class="border_line"></div>
                <div class="row footer-bottom d-flex justify-content-between align-items-center">
                    <p class="col-lg-8 col-sm-12 footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                    <div class="col-lg-4 col-sm-12 footer-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                        <a href="#"><i class="fa fa-behance"></i></a>
                    </div>
                </div>
            </div>
        </footer>
		<!--================ End footer Area  =================-->
        
        
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="js/jquery-ui.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
        <script src="js/jquery.ajaxchimp.min.js"></script>
        <script src="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js"></script>
        <script src="vendors/nice-select/js/jquery.nice-select.js"></script>
        <script src="js/mail-script.js"></script>
        <script src="js/stellar.js"></script>
        <script src="vendors/lightbox/simpleLightbox.min.js"></script>
        <script src="js/custom.js"></script>
        <script type="text/javascript" src="js/total.js"></script>
    </body>
</html>